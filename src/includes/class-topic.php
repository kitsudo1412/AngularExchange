<?php

final class AE_Topic
{
    public $id = 0;

    public $slug = '';

    public $category = 0;

    public $title = '';

    public $view_count = 0;

    public $comment_count = 0;

    public $is_private_message = false;

    public $original_poster = 0;

    public $frequent_poster_1 = 0;

    public $frequent_poster_2 = 0;

    public $most_recent_poster = 0;

    public function __construct($topic)
    {
        if (count($topic) > 0)
            foreach ($topic as $key => $value)
                $this->$key = $value;
    }

    public static function get_topic($topic_id)
    {
        $topic_id = (int)$topic_id;
        if (!$topic_id)
            return false;

        return new AE_Topic(db_topic_get_by_id($topic_id));
    }

    public static function get_topics($category_id = 0, $offset = 0, $num = 10)
    {
        $topics = array();
        $category_topic_arrays = db_category_get_topics($category_id, $offset, $num);
        foreach ($category_topic_arrays as $category_topic_array) {
            $topics[] = new AE_Topic($category_topic_array);
        }
        return $topics;
    }

    public function get_id()
    {
        return $this->id;
    }

    public function get_slug()
    {
        return $this->slug;
    }

    public function get_category()
    {
        return AE_Category::get_category($this->category);
    }

    public function get_title()
    {
        return $this->title;
    }

    public function get_permalink()
    {
        return "";
    }

    public function change_title($new_topic_title)
    {
        $this->title = $new_topic_title;
        if ($this->id != 0)
            db_topic_change_title($this->id, $new_topic_title);
    }

    public function get_view_count()
    {
        return $this->view_count;
    }

    public function view_count_increment()
    {
        $this->view_count++;
        if ($this->id != 0)
            db_topic_view_count_increment($this->id);
    }

    public function get_comment_count()
    {
        return $this->comment_count;
    }

    public function comment_count_increment()
    {
        $this->comment_count++;
        if ($this->id != 0)
            db_topic_comment_count_increment($this->id);
    }

    public function comment_count_minus()
    {
        $this->comment_count--;
        if ($this->id != 0)
            db_topic_comment_count_minus($this->id);
    }

    public function is_private_message()
    {
        return $this->is_private_message;
    }

    public function get_author()
    {
        return AE_User::get_user($this->original_poster);
    }

    public function get_active_users()
    {
        return array(
            'original_poster' => AE_User::get_user($this->original_poster),
            'frequent_poster_1' => AE_User::get_user($this->frequent_poster_1),
            'frequent_poster_2' => AE_User::get_user($this->frequent_poster_2),
            'most_recent_poster' => AE_User::get_user($this->most_recent_poster)
        );
    }

    public function get_posts($offset = 0, $num = 10)
    {
        $posts = array();
        $topic_post_arrays = db_topic_get_posts($this->id, $offset, $num);
        foreach ($topic_post_arrays as $topic_post_array) {
            $posts[] = new AE_Post($topic_post_array);
        }
        return $posts;
    }

    public function delete()
    {
        $this_author = $this->get_author();
        $this_author->topic_count_minus();

        $this_topic_posts = $this->get_posts();
        foreach ($this_topic_posts as $v_post) {
            $v_post_author = $v_post->get_author();
            $v_post_author->post_count_minus();
        }

        if ($this->id != 0)
            db_topic_delete($this->id);
    }

    public function submit()
    {

    }
}