<?php 
include('inc/header.php'); 
require_once '/u/ryooit/Documents/CS105/myDB/openDatabase.php';
$CategoriesQuery = $database->prepare('
  SELECT
    ITEM_CATEGORY_ID,
    NAME
    FROM ITEM_CATEGORY;
    ');
$CategoriesQuery->execute();
$categories = $CategoriesQuery->fetchAll();
$CategoriesQuery->closeCursor();
?>
    <section class="box">
        <form action="process.php?mode=add" method="POST" enctype="multipart/form-data">
            <h2>Add New Item</h2>
            <dl>
                <dt>Category</dt><dd>
                <select name="category" required="required">
                    <option value="">Choose</option>
                    <?php 
                    foreach ($categories as $category):
                        ?>
                    <option value="<?=htmlspecialchars($category['ITEM_CATEGORY_ID'])?>"><?=htmlspecialchars($category['NAME'])?></option>
                    <?php
                    endforeach;
                    ?>
                </select></dd>
                <dt>Name</dt><dd>
                <input type="text" name="item_name" required="required" maxlength="78" size="78" value="<?=htmlspecialchars($auction['ITEM_CAPTION'])?>" /></dd>
                <dt>Description</dt><dd>
                <textarea name="description" required="required" cols="50" rows="20"><?=htmlspecialchars($auction['ITEM_DESCRIPTION'])?></textarea></dd>
                <dt>Photo</dt><dd>
                <input type="file" name="photo" accept="image/jpeg"/><dd>
                <dt>Start Price</dt><dd>
                <input type="text" name="price" /><dd>
                <dt>Deadline</dt><dd>
                <input type="datetime-local" name="closetime" required="required" /> YYYY-MM-DD HH:MM:SS<dd>
                <dt><button type="submit">Submit</button></dt><dd>
            </dl>
        </form>
    </section>
<?php include('inc/footer.php'); ?>