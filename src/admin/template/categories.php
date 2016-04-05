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
                            <td class="heading">Slug</td>
                            <td class="heading">Name</td>
                            <td class="heading" width="86px">Actions</td>
                        </tr>
                        <?php
                        $all_categories = AE_Category::get_categories();

                        foreach ($all_categories as $category): ?>
                            <tr>
                                <td class="off"><?php echo $category->id; ?></td>
                                <td class="off"><input style="width: 100%" form="category-form-<?php echo $category->id; ?>" title="category slug" type="text" value="<?php echo $category->slug; ?>" name="category_slug" /></td>
                                <td class="off"><input style="width: 100%" form="category-form-<?php echo $category->id; ?>" title="category name" type="text" value="<?php echo $category->name; ?>" name="category_name" /></td>
                                <td class="off">
                                    <form id="category-form-<?php echo $category->id; ?>" class="admin-actions" method="post">
                                        <button type="submit" name="action" value="edit"><strong>Save</strong></button>
                                        <button type="submit" name="action" value="delete">Delete</button>
                                        <input type="hidden" name="region" value="categories"/>
                                        <input type="hidden" name="id" value="<?php echo $category->get_id(); ?>"/>
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
                        <input title="category name" type="text" name="category_name" />
                        <button type="submit" name="action" value="add">+ Add New Category</button>
                        <input type="hidden" name="id" value="0"/>
                        <input type="hidden" name="region" value="categories"/>
                    </form>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
<?php include 'footer.php'; ?>