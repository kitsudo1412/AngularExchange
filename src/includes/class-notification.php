<?php

final class AE_Notification
{
    public $id = 0;

    public $user = 0;

    public $is_new_reply = true;

    public $topic = 0;

    public $is_read = 0;

    public function __construct($notification)
    {
        foreach ($notification as $key => $value)
            $this->$key = $value;
    }

    public static function get_notification($notification_id)
    {
        $notification_id = (int)$notification_id;
        if (!$notification_id)
            return false;

        return new AE_Notification(db_notification_get_by_id($notification_id));
    }

    public function get_id()
    {
        return $this->id;
    }

    public function get_user_id()
    {
        return $this->user;
    }

    public function get_user()
    {
        return AE_User::get_user($this->user);
    }

    public function is_new_reply()
    {
        return $this->is_new_reply;
    }

    public function get_topic_id()
    {
        return $this->topic;
    }

    public function get_topic()
    {
        return AE_Topic::get_topic($this->topic);
    }

    public function is_read()
    {
        return $this->is_read;
    }
}