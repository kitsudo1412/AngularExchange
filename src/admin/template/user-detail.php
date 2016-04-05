<?php include 'header.php';
if (isset($_GET['user_id'])) {
    $page_title = "Edit User: #" . $_GET['user_id'];
    $user = AE_User::get_user($_GET['user_id']);
} else {
    $page_title = "Add new user";
    $user = new AE_User();
    $user->display_name = '';
}

?>
    <div id="xptContentOuter">
        <h1><?php echo $page_title; ?></h1>
        <br/>
        <form method="post">
            <table align="center" border="0" cellpadding="0" cellspacing="0" id="xptContentInner" class="user-detail">
                <tbody>
                <?php if (isset($_GET['user_id'])): ?>
                <tr valign="top">
                    <td><label for="user_id">User ID: </label></td>
                    <td><input type="text" id="user_id" name="user_id" value="<?php echo $user->get_id(); ?>" readonly/>
                    </td>
                </tr>
                <?php endif; ?>
                <tr>
                    <td><label for="user_login">User Login: </label></td>
                    <td><input type="text" id="user_login" name="user_login" value="<?php echo $user->get_login(); ?>"/></td>
                </tr>
                <tr>
                    <td><label for="user_display_name">Display name: </label></td>
                    <td><input type="text" id="user_display_name" name="user_display_name"
                               value="<?php echo $user->get_display_name(); ?>"/></td>
                <tr>
                    <td><label for="user_role">Role: </label></td>
                    <td>
                        <select id="user_role" name="user_role">
                            <option value="2"<?php echo ($user->get_role() == 2) ? ' selected' : ''; ?>>
                                Administrator
                            </option>
                            <option value="1"<?php echo ($user->get_role() == 1) ? ' selected' : ''; ?>>
                                Registered
                            </option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><label for="user_email">Email: </label></td>
                    <td><input type="text" id="user_email" name="user_email" value="<?php echo $user->get_email(); ?>"</td>
                </tr>
                <tr>
                    <td><label for="user_post_count">Post Count: </label></td>
                    <td><input type="number" id="user_post_count" name="user_post_count"
                               value="<?php echo $user->get_post_count(); ?>"/></td>
                </tr>
                <tr>
                    <td><label for="user_topic_count">Topic Count: </label></td>
                    <td><input type="number" id="user_topic_count" name="user_topic_count"
                               value="<?php echo $user->get_topic_count(); ?>"/></td>
                </tr>
                <tr>
                    <td><label for="user_likes_given">Likes given: </label></td>
                    <td><input type="number" id="user_likes_given" name="user_likes_given"
                               value="<?php echo $user->get_likes_given(); ?>"/></td>
                </tr>
                <tr>
                    <td><label for="user_likes_received">Likes received: </label></td>
                    <td><input type="number" id="user_likes_received" name="user_likes_received"
                               value="<?php echo $user->get_likes_received(); ?>"/></td>
                </tr>
                <tr>
                    <td></td>
                    <td><?php echo (isset($_GET['user_id'])) ? '<button type="submit" name="action" value="save">Save</button>' : '<button type="submit" name="action" value="create">Create New User</button>'; ?></td>
                </tr>
                </tbody>
            </table>
            <input type="hidden" name="region" value="user-detail" />
            <input type="hidden" name="id" value="<?php echo isset($_GET['user_id']) ? $_GET['user_id'] : '' ?>" />
        </form>
    </div>
<?php include 'footer.php'; ?>