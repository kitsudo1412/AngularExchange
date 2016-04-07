<?php

final class AE_Post
{

    public $id = 0;

    public $topic = 0;

    public $author = 0;

    public $created = '0000-00-00 00:00:00';

    public $modified = '0000-00-00 00:00:00';

    public $content = '';

    public $like_count = 0;

    private $ip = '000.000.000.000';

    public function __construct($post)
    {
        if (count($post) > 0)
            foreach ($post as $key => $value)
                $this->$key = $value;
    }

    public static function get_post($post_id)
    {
        $post_id = (int)$post_id;
        if (!$post_id)
            return false;

        return new AE_Post(db_post_get_by_id($post_id));
    }

    public function get_id()
    {
        return $this->id;
    }

    public function get_content()
    {
        return $this->content;
    }

    public function set_content($new_content)
    {
        $this->content = $new_content;
        if ($this->id != 0)
            db_post_update_content($this->id, $new_content);
    }

    public function get_topic_id()
    {
        return $this->topic;
    }

    public function get_topic()
    {
        return AE_Topic::get_topic($this->topic);
    }

    public function get_author_id()
    {
        return $this->author;
    }

    public function get_author()
    {
        return AE_User::get_user($this->author);
    }

    public function get_created_date($format_string = 'Y-m-d H:i:s')
    {
        $date = new DateTime($this->created);
        return $date->format($format_string);
    }

    public function get_modified_date($format_string = 'Y-m-d H:i:s')
    {
        $date = new DateTime($this->modified);
        return $date->format($format_string);
    }

    public function get_like_count()
    {
        return $this->like_count;
    }

    public function add_like($liker_id)
    {
        $this->like_count++;
        if ($this->id != 0):
            db_post_like_increment($this->id);
            db_post_add_like($this->id, $liker_id);
        endif;
    }

    public function remove_like($liker)
    {
        $this->like_count--;
        if ($this->id != 0):
            db_post_like_minus($this->id);
            db_post_remove_like($this->id, $liker);
        endif;
    }

    public function get_like_ids($offset = 0, $num = 3)
    {
        return db_post_get_likes($this->id, $offset, $num);
    }

    public function get_likes($offset = 0, $num = 3)
    {
        $users_who_liked = array();
        $like_ids = $this->get_like_ids($offset, $num);
        for ($i = 0; $i < count($like_ids); $i++) {
            $users_who_liked[] = AE_User::get_user($like_ids[$i]);
        }
        return $users_who_liked;
    }

    public function get_ip_address()
    {
        return $this->ip;
    }

    public function delete()
    {
        $this_topic = $this->get_topic();
        $posts_same_topic = $this_topic->get_posts();
        if (count($posts_same_topic) <= 1)
            $this_topic->delete(); // cascade
        else {
            db_post_delete($this->id);
            $this_author = $this->get_author();
            $this_author->post_count_minus();
        }
    }

    public function submit()
    {
        if ($this->id == 0)
        {
            $insert_id = db_post_submit($this->topic, $this->author, $this->content, $this->ip);
            db_user_post_count_increment($this->author);

            $this_topic = $this->get_topic();
            $this_topic->update_statistics();

            return $insert_id;
        }
        return 0;
    }
}