<?php

final class AE_Category
{
    public $id = 0;

    public $slug;

    public $name;

    public $color = "#000000";

    public function __construct($category = array())
    {

        if (count($category) > 0)
            foreach ($category as $key => $value)
                $this->$key = $value;
    }

    public function copy($category_object)
    {
        if (count($category_object) > 0)
            foreach (get_object_vars($category_object) as $key => $value)
                $this->$key = $value;
    }

    public static function get_category($category_id)
    {
        $category_id = (int)$category_id;
        if (!$category_id)
            return false;

        return new AE_Category(db_category_get_by_id($category_id));
    }

    public static function get_categories()
    {
        $categories = array();
        $category_arrays = db_category_get_categories();
        foreach ($category_arrays as $category_array) {
            $categories[] = new AE_Category($category_array);
        }
        return $categories;
    }

    public function get_id()
    {
        return $this->id;
    }

    public function get_slug()
    {
        return $this->slug;
    }

    public function get_name()
    {
        return $this->name;
    }

    public function set_name($new_name)
    {
        $this->name = $new_name;
    }

    public function change_name($new_category_name)
    {
        $this->name = $new_category_name;
        if ($this->id != 0)
            db_category_change_name($this->id, $new_category_name);
    }

    public function get_color()
    {
        return $this->color;
    }

    public function set_color($color_string)
    {
        db_category_update_color($this->id, $color_string);
    }

    public function get_topics($offset = 0, $num = 10)
    {
        $topics = array();
        $category_topic_arrays = db_category_get_topics($this->id, $offset, $num);
        foreach ($category_topic_arrays as $category_topic_array) {
            $topics[] = new AE_Topic($category_topic_array);
        }
        return $topics;
    }

    public function delete()
    {
        $this_topics = $this->get_topics(0, 9999);
        foreach ($this_topics as $v_topic) {
            $v_topic->delete();
        }
        if ($this->id != 0)
            db_category_delete($this->id);
    }

    public function submit()
    {
        if ($this->id == 0) {
            $insert_id = db_category_insert($this->get_name());
            $insert_category = AE_Category::get_category($insert_id);
            $this->copy($insert_category);
        }
    }

    public function update($new_slug, $new_name)
    {
        if ($new_slug != null && $new_slug != $this->slug && $this->id != 0) {
            db_category_update_slug($this->id, $new_slug);
        }

        if ($new_name != null && $new_name != $this->name && $this->id != 0) {
            db_category_update_name($this->id, $new_name);
        }
    }
}