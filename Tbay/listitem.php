<?php 
include('inc/header.php');
require_once '/u/ryooit/Documents/CS105/myDB/openDatabase.php';
$openAuctionQuery = $database->prepare(<<<'SQL'
   SELECT
        AUCTION.AUCTION_ID,
        AUCTION.SELLER,
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
        WHERE SELLER = :seller;
SQL
);
$seller = $_SESSION['user'];
$openAuctionQuery->bindValue(':seller', $seller, PDO::PARAM_INT);
$openAuctionQuery->execute();
?>
<section>
    <form action = "addnew.php" id="addnew">
      <i class="fa fa-star fa-1x"></i> <input type="submit" value="Add New Item" class="table_btn" id = "btn" />
    </form>
    <table id="auctionlist" class="display">
      <caption><h2>Auction List</h2></caption>
      <thead>
        <tr>
          <th>Status</th>
          <th>Category</th>
          <th>Name</th>
          <th>Description</th>
          <th>Photo</th>
          <th>Reserve Price</th>
          <th>Deadline</th>
          <th>Update</th>
          <th>Cancel</th>
        </tr>
      </thead>
      <tbody>
        <?php
          foreach($openAuctionQuery->fetchAll() as $auction):
        ?>
        <tr>
          <td><?=htmlspecialchars($auction['STATUS'])?></td>
          <td><?=htmlspecialchars($auction['ITEM_CATEGORY'])?></td>
          <td><a href="itemdetails.php?id=<?=urlencode($auction['AUCTION_ID'])?>"><?=htmlspecialchars($auction['ITEM_CAPTION'])?></a></td>
          <td><?=htmlspecialchars($auction['ITEM_DESCRIPTION'])?></td>
          <td><img src="auctionPhoto.php?id=<?=$auction['AUCTION_ID']?>" height="100" width="100" alt="<?=htmlspecialchars($auction['ITEM_CAPTION'])?>"></td>
          <td>$ <?=htmlspecialchars($auction['AMOUNT'])?></td>
          <td><?=htmlspecialchars($auction['CLOSE_TIME'])?></td>
          <td>
            <form action="updatelist.php?id=<?=urlencode($auction['AUCTION_ID'])?>" method="get">
            <input type="hidden" name="id" value="<?=$auction['AUCTION_ID']?>" />
            <input type="submit" value="Update" class="table_btn">
          </form></td>
          <td>
            <form class="cancel" method="POST" action="confirm.php?id=<?=urlencode($auction['AUCTION_ID'])?>">
            <input type="hidden" name="id" value="<?=$auction['AUCTION_ID']?>" /> 
            <input type="submit" value="Cancel" class="table_btn" />
          </form></td>
        </tr>
        <?php
        endforeach;
        $openAuctionQuery->closeCursor();
        ?>
      </tbody>
    </table>
</section>

    <script type="text/javascript">
    $(document).ready( function () {
      $('#auctionlist').DataTable();
    } );
    </script>

<?php include('inc/footer.php'); ?>
