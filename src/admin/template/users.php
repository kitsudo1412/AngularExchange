<?php include 'header.php'; ?>
<div id="xptContentOuter">
    <?php echo get_notice(); ?>
    <table align="center" border="0" cellpadding="0" cellspacing="0" id="xptContentInner">
        <tbody>
        <tr valign="top">
            <td>
                <table align="center" border="0" cellpadding="5" cellspacing="0" width="100%" id="xptLeftNav">
                    <tbody>
                    <tr>
                        <td class="heading">ID</td>
                        <td class="heading">Login</td>
                        <td class="heading">Display Name</td>
                        <td class="heading">Email</td>
                        <td class="heading">Role</td>
                        <td class="heading">Registered</td>
                        <td class="heading">Post Count</td>
                        <td class="heading">Topic Count</td>
                        <td class="heading">Likes Given</td>
                        <td class="heading">Likes Received</td>
                        <td class="heading">Actions</td>
                    </tr>
                    <?php
                    $all_users = AE_User::get_users();

                    foreach($all_users as $user): ?>
                        <tr>
                            <td class="off"><?php echo $user->get_id() ;?></td>
                            <td class="off"><?php echo $user->get_login(); ?></td>
                            <td class="off"><?php echo $user->get_display_name(); ?></td>
                            <td class="off"><?php echo $user->get_email(); ?></td>
                            <td class="off"><?php echo $user->get_text_role(); ?></td>
                            <td class="off"><?php echo $user->get_registered_date(); ?></td>
                            <td class="off"><?php echo $user->get_post_count(); ?></td>
                            <td class="off"><?php echo $user->get_topic_count(); ?></td>
                            <td class="off"><?php echo $user->get_likes_given(); ?></td>
                            <td class="off"><?php echo $user->get_likes_received(); ?></td>
                            <td class="off" width="100px">
                                <form class="admin-actions" method="post">
                                    <button type="submit" name="action" value="edit"><strong>Edit</strong></button>
                                    <button type="submit" name="action" value="delete">Delete</button>
                                    <input type="hidden" name="region" value="users"/>
                                    <input type="hidden" name="id" value="<?php echo $user->get_id(); ?>"/>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </td>
        </tr>
        <tr>
            <td class="add-new-category">
                <form method="post">
                    <button type="submit" name="action" value="new">+ Add New User</button>
                    <input type="hidden" name="id" value="0"/>
                    <input type="hidden" name="region" value="users"/>
                </form>
            </td>
        </tr>
        </tbody>
    </table>
</div>
<?php include 'footer.php'; ?>