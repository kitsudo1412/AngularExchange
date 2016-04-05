<?php

class AE_User
{
    public $id = 0;

    public $login = null;

    private $pass = null;

    public $display_name = "Guest";

    public $role = 0;

    public $email = null;

    public $registered = null;

    public $post_count = 0;

    public $topic_count = 0;

    public $likes_given = 0;

    public $likes_received = 0;

    /* variables outside database */

    private $session_id;

    private $token_expire;

    /*
     * role: 0: guest, 1: registered, 2: admin
     */

    public function __construct($user = array())
    {
        if (count($user) > 0)
            foreach ($user as $key => $value)
                $this->$key = $value;
        else {
            $this->id = 0;
            $this->role = 1;
        }
    }

    public static function get_user($user_id)
    {
        $user_id = (int)$user_id;
        if (!$user_id)
            return new AE_User();

        return new AE_User(db_user_get_by_id($user_id));
    }

    public static function get_users($offset = 0, $limit = 40)
    {
        $users = array();
        $user_arrays = db_user_get_users($offset, $limit);
        foreach ($user_arrays as $user_array) {
            $users[] = new AE_User($user_array);
        }
        return $users;
    }

    public function get_id()
    {
        return $this->id;
    }

    public function get_login()
    {
        return $this->login;
    }

    public function check_password($plain_password)
    {
        if (db_user_check_password($this->id, $plain_password))
            return true;
        return false;
    }

    public function change_password($new_plain_password)
    {
        $this->pass = md5($new_plain_password);
        if ($this->id != 0)
            db_user_change_password($this->id, $new_plain_password);
    }

    public function get_display_name()
    {
        return $this->display_name;
    }

    public function change_display_name($new_display_name)
    {
        $this->display_name = $new_display_name;
        if ($this->id != 0)
            db_user_change_display_name($this->id, $new_display_name);
    }

    public function get_role()
    {
        return $this->role;
    }

    public function get_text_role()
    {
        switch ($this->role) {
            case 2:
                $text_role = 'Administrator';
                break;
            case 1:
                $text_role = 'Registered';
                break;
            default:
                $text_role = 'Guest';
                break;
        }
        return $text_role;
    }

    public function get_email()
    {
        return $this->email;
    }

    public static function is_email_valid($email_address)
    {
        if (count(explode('@', $email_address)) == 2)
            return true;
        return false;
    }

    public function change_email($new_email_address)
    {
        $this->email = $new_email_address;
        if ($this->id != 0)
            db_user_change_email_address($this->id, $new_email_address);
    }

    public function get_registered_date($format_string = 'Y-m-d H:i:s')
    {
        $registered_date = new DateTime($this->registered);
        return $registered_date->format($format_string);
    }

    public function get_post_count()
    {
        return $this->post_count;
    }

    public function post_count_increment()
    {
        $this->post_count++;
        if ($this->id != 0)
            db_user_post_count_increment($this->id);
    }

    public function post_count_minus()
    {
        $this->post_count--;
        if ($this->id != 0)
            db_user_post_count_minus($this->id);
    }

    public function get_topic_count()
    {
        return $this->topic_count;
    }

    public function topic_count_increment()
    {
        $this->topic_count++;
        if ($this->id != 0)
            db_user_topic_count_increment($this->id);
    }

    public function topic_count_minus()
    {
        $this->topic_count--;
        if ($this->id != 0)
            db_user_topic_count_minus($this->id);
    }

    public function get_likes_given()
    {
        return $this->likes_given;
    }

    public function likes_given_increment()
    {
        $this->likes_given++;
        if ($this->id != 0)
            db_user_likes_given_increment($this->id);
    }

    public function likes_given_minus()
    {
        $this->likes_given--;
        if ($this->id != 0)
            db_user_likes_given_minus($this->id);
    }

    public function get_likes_received()
    {
        return $this->likes_received;
    }

    public function likes_received_increment()
    {
        $this->likes_received++;
        if ($this->id != 0)
            db_user_likes_received_minus($this->id);
    }

    public function likes_received_minus()
    {
        $this->likes_received--;
        if ($this->id != 0)
            db_user_likes_received_minus($this->id);
    }

    public function get_posts($offset = 0, $num = 10)
    {
        $posts = array();
        $user_post_arrays = db_user_get_posts($this->id, $offset, $num);
        foreach ($user_post_arrays as $user_post_array) {
            $posts[] = new AE_Post($user_post_array);
        }
        return $posts;
    }

    public function get_topics($offset = 0, $num = 10)
    {
        $topics = array();
        $user_topic_arrays = db_user_get_topics($this->id, $offset, $num);
        foreach ($user_topic_arrays as $user_topic_array) {
            $topics[] = new AE_Topic($user_topic_array);
        }
        return $topics;
    }

    public function get_notifications($offset = 0, $num = 5)
    {
        $notifications = array();
        $user_notification_arrays = db_user_get_notifications($this->id, $offset, $num);
        foreach ($user_notification_arrays as $user_notification_array) {
            $notifications[] = new AE_Notification($user_notification_array);
        }
        return $notifications;
    }

    public function get_session_id()
    {
        return $this->session_id;
    }

    public function set_session_id($session_id)
    {
        $this->session_id = $session_id;
    }

    public function get_token_expire()
    {
        return $this->token_expire;
    }

    public function set_token_expire($token_expire)
    {
        $this->token_expire = $token_expire;
    }

    public function add_notification($is_new_reply, $topic_id, $is_read)
    {
        if ($this->id != 0)
            db_user_add_notification($this->id, $is_new_reply, $topic_id, $is_read);
    }

    public function delete()
    {
        if ($this->id != 0)
            db_user_delete($this->id);
    }

    public function submit()
    {
        if ($this->id == 0)
        {
            return db_user_insert($this);
        }
        return 0;
    }

    public function update($array)
    {
        $login = (isset($array['login']) && $array['login']!= '') ? $array['login'] : $this->login;
        $pass = (isset($array['pass']) && $array['pass'] != '') ? $array['pass'] : $this->pass;
        $display_name = (isset($array['display_name']) && $array['display_name'] != '') ? $array['display_name'] : $this->display_name;
        $role = (isset($array['role'])) ? $array['role'] : $this->role;
        $email = (isset($array['email'])) ? $array['email'] : $this->email;
        if ($this->id != 0)
            db_user_update($this->get_id(), $login, $pass, $display_name, $role, $email);
    }
}