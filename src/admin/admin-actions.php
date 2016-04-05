<?php
if (isset($_POST['id']) && isset($_POST['region']) && isset($_POST['action'])) {
    switch ($_POST['region']) {
        case 'topics':
            switch ($_POST['action']) {
                case 'edit':
                    header("Location: " . admin_url() . '?view=posts&topic_id=' . $_POST['id']);
                    exit;
                    break;
                case 'delete':
                    $topic_to_delete = AE_Topic::get_topic($_POST['id']);
                    $topic_to_delete->delete();
                    break;
            }
            break;
        case 'posts':
            switch ($_POST['action']) {
                case 'edit':
                    header("Location: " . admin_url() . "?view=post_edit&post_id=" . $_POST['id']);
                    exit;
                    break;
                case 'delete':
                    $post_to_delete = AE_Post::get_post($_POST['id']);
                    $post_to_delete->delete();
                    break;
            }
            break;
        case 'categories':
            switch ($_POST['action']) {
                case 'edit':
                    $category_to_edit = AE_Category::get_category($_POST['id']);
                    $category_to_edit->update($_POST['category_slug'], $_POST['category_name']);
                    break;
                case 'delete':
                    $category_to_delete = AE_Category::get_category($_POST['id']);
                    $category_to_delete->delete();
                    break;
                case 'add':
                    if (isset($_POST['category_name'])) {
                        $new_category = new AE_Category();
                        $new_category->set_name($_POST['category_name']);
                        $new_category->submit();
                    }
                    break;
            }
            break;
        case 'users':
            switch($_POST['action']) {
                case 'edit':
                    header("Location: ".admin_url()."?view=user_detail&user_id=".$_POST['id']);
                    break;
                case 'new':
                    header("Location: ".admin_url()."?view=user_detail");
                    break;
                case 'delete':
                    $user_to_delete = AE_User::get_user($_POST['id']);
                    $user_to_delete->delete();
                    break;
            }
            break;
        case 'user-detail':
            switch($_POST['action']) {
                case 'save':
                    $user_to_edit = AE_User::get_user($_POST['id']);
                    $attr_array = array();

                    foreach ($_POST as $post_name => $post_value)
                    {
                        if (substr($post_name, 0, 5) == "user_")
                        {
                            $attr = substr($post_name, 5);
                            $attr_array[$attr] = $post_value;
                        }
                    }
                    $user_to_edit->update($attr_array);
                    $notice = "User saved!";
                    header("Location: ".admin_url().'?view=users&notice='.$notice); exit;
                    break;
                case 'create':
                    $new_user = new AE_User();
                    foreach ($_POST as $post_name => $post_value)
                    {
                        if (substr($post_name, 0, 5) == "user_")
                        {
                            $attr = substr($post_name, 5);
                            $new_user->$attr = $post_value;
                        }
                    }
                    $insert_id = $new_user->submit();
                    if ($insert_id > 0)
                    {
                        $notice = "User Insert Successfully";
                    }
                    else
                    {
                        $notice = "Email/Username used! Please choose another identifier";
                    }
                    header("Location: ".admin_url().'?view=users&notice='.$notice); exit;
                    break;
            }
            break;
    }
}