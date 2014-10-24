<?php
session_start(); 
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
            JOIN BID ON AUCTION_ID = BID_ID;
SQL
);
$openAuctionQuery->execute();
?>
  <?php
  if($_SESSION['bid_success']):
  ?>
  <center><h1><?=htmlspecialchars($_SESSION['bid_success'])?></h1></center>
  <?php
  unset($_SESSION['bid_success']);
  endif;
  ?>

    <table id="auctionlist" class="display">
      <caption><h2>Auction List</h2></caption>
      <thead>
        <tr>
          <th>Status</th>
          <th>Category</th>
          <th>Name</th>
          <th>Description</th>
          <th>Photo</th>
          <th>Current Bid</th>
          <th>Deadline</th>
          <th>Place bid</th>
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
          <td><?=htmlspecialchars($auction['AMOUNT'])?></td>
          <td><?=htmlspecialchars($auction['CLOSE_TIME'])?></td>
          <td><form method="POST" action="bid.php?id=<?=urlencode($auction['AUCTION_ID'])?>">
            <input type="hidden" name="id" value="<?=$auction['AUCTION_ID']?>" />
            <input type="submit" onclick="getConfirmation()" value="Bidding" class="table_btn" /></form></td>
        </tr>
        <?php
          endforeach;
          $openAuctionQuery->closeCursor();
        ?>
      </tbody>
    </table>

    <script type="text/javascript">
    $(document).ready( function () {
      $('#auctionlist').DataTable();
    } );
    </script>
<?php include('inc/footer.php'); ?>
