<?php 
include('inc/header.php');
require_once '/u/ryooit/Documents/CS105/myDB/openDatabase.php';
$openAuctionQuery = $database->prepare(<<<'SQL'
   SELECT
        AUCTION.AUCTION_ID,
        AUCTION.OPEN_TIME,
        AUCTION.CLOSE_TIME,
        AUCTION.ITEM_CAPTION,
        AUCTION.ITEM_DESCRIPTION,
        AUCTION.ITEM_PHOTO,
        ITEM_CATEGORY.NAME AS ITEM_CATEGORY,
        AUCTION_STATUS.NAME AS STATUS,
        BID.AMOUNT AS AMOUNT
        FROM AUCTION
            JOIN ITEM_CATEGORY ON ITEM_CATEGORY = ITEM_CATEGORY_ID
            JOIN AUCTION_STATUS ON STATUS = AUCTION_STATUS_ID
            JOIN BID ON AUCTION_ID = BID_ID
        WHERE AUCTION.AUCTION_ID = :auctionId;
SQL
);
$thisAuctionId = $_GET['id'];
$openAuctionQuery->bindValue(':auctionId', $thisAuctionId, PDO::PARAM_INT);
$openAuctionQuery->execute();
$auction = $openAuctionQuery->fetch();
$openAuctionQuery->closeCursor();

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
      <form action="process.php?mode=update" method="POST" enctype="multipart/form-data">
        <h2>Update listing</h2>
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
          </select>
          <dt>Name</dt><dd>
            <input type="text" name="item_name" required="required" maxlength="78" size="78" value="<?=htmlspecialchars($auction['ITEM_CAPTION'])?>" /><dd>
          <dt>Description</dt><dd>
            <textarea name="description" required="required" cols="50" rows="20"><?=htmlspecialchars($auction['ITEM_DESCRIPTION'])?></textarea><dd>
          <dt>Photo</dt><dd>
            <input type="file" name="photo" accept="image/jpeg" /><dd>
          <dt>Start Price</dt><dd>
            <input type="text" name="price" value="<?=htmlspecialchars($auction['AMOUNT'])?>" /><dd>
          <dt>Deadline</dt><dd>
            <input type="datetime-local" name="closetime" required="required" value="<?=htmlspecialchars($auction['CLOSE_TIME'])?>"/><dd>
          <dt><button type="submit">Submit</button></dt><dd>
          <dt><input type="hidden" name="user" value="<?=htmlspecialchars($thisAuctionId)?>"/></dt><dd>
        </dl>
      </form>
    </section>
<?php include('inc/footer.php'); ?>