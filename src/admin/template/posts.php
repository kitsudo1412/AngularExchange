<?php include 'header.php'; ?>
    <div id="xptContentOuter">
        <?php
        if (isset($_GET['topic_id'])):
        $this_topic = AE_Topic::get_topic($_GET['topic_id']);
        ?>
        <h1>Posts in topic: <?php echo ($this_topic) ? $this_topic->get_title() : 'Not found' ?></h1>
        <br />
        <table align="center" border="0" cellpadding="0" cellspacing="0" id="xptContentInner">
            <tbody>
            <tr valign="top">
                <td>
                    <table align="center" border="0" cellpadding="5" cellspacing="0" width="100%" id="xptLeftNav">
                        <tbody>
                        <tr>
                            <td class="heading">ID</td>
                            <td class="heading">Author</td>
                            <td class="heading">Content</td>
                            <td class="heading">Created/Modified</td>
                            <td class="heading">IP Address</td>
                            <td class="heading" style="width: 85px;">Actions</td>
                        </tr>
                        <?php
                            if ($this_topic):
                        $all_posts = $this_topic->get_posts();
                                if (count($all_posts) > 0):
                                foreach($all_posts as $post): ?>
                                    <tr>
                                        <td class="off"><?php echo $post->get_id() ;?></td>
                                        <td class="off"><?php $this_author = AE_User::get_user($post->author); echo $this_author->get_display_name(); ?></td>
                                        <td class="off"><?php $content = $post->get_content(); echo substr($content,0, 100).'...'; ?></td>
                                        <td class="off"><?php echo $post->get_created_date(); ?><br /><?php echo $post->get_modified_date(); ?></td>
                                        <td class="off"><?php echo $post->get_ip_address(); ?></td>
                                        <td class="off">
                                            <form class="admin-actions" method="post">
                                                <button><a target="_blank" href="<?php echo $this_topic->get_permalink(); ?>">View</a></button>
                                                <button type="submit" name="action" value="delete">Delete</button>
                                                <input type="hidden" name="region" value="posts" />
                                                <input type="hidden" name="id" value="<?php echo $post->get_id(); ?>" />
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach;
                                else: ?>
                                    <tr>
                                        <td colspan="6" style="text-align: center">This topic is empty!</td>
                                    </tr>
                                <?php
                                endif;
                            endif;
        endif; ?>
                        </tbody>
                    </table>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
<?php include 'footer.php'; ?>