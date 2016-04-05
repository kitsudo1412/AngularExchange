<?php include 'header.php'; ?>
<div id="xptContentOuter">
    <table align="center" border="0" cellpadding="0" cellspacing="0" id="xptContentInner">
        <tbody>
        <tr valign="top">
            <td>
                <table align="center" border="0" cellpadding="5" cellspacing="0" width="100%" id="xptLeftNav">
                    <tbody>
                    <tr>
                        <td class="heading">ID</td>
                        <td class="heading">Title</td>
                        <td class="heading">Category</td>
                        <td class="heading">Views</td>
                        <td class="heading">Comments</td>
                        <td class="heading">Author</td>
                        <td class="heading" style="width: 130px;">Actions</td>
                    </tr>
                    <?php
                    $all_topics = AE_Topic::get_topics();

                    foreach($all_topics as $topic): ?>
                        <tr>
                            <td class="off"><?php echo $topic->id ;?></td>
                            <td class="off"><?php echo $topic->title; ?></td>
                            <td class="off"><?php $this_category = $topic->get_category(); echo $this_category->name; ?></td>
                            <td class="off"><?php echo $topic->view_count; ?></td>
                            <td class="off"><?php echo $topic->comment_count; ?></td>
                            <td class="off"><?php $this_author = $topic->get_author(); echo $this_author->display_name; ?></td>
                            <td class="off">
                                <form class="admin-actions" method="post">
                                    <button><a target="_blank" href="<?php echo $topic->get_permalink(); ?>">View</a></button>
                                    <button type="submit" name="action" value="edit">Posts</button>
                                    <button type="submit" name="action" value="delete">Delete</button>
                                    <input type="hidden" name="region" value="topics" />
                                    <input type="hidden" name="id" value="<?php echo $topic->get_id(); ?>" />
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </td>
        </tr>
        </tbody>
    </table>
</div>
<?php include 'footer.php'; ?>