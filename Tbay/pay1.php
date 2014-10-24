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
?>
    <section class="box">
      <h2>Payment Details</h2>
      <ul>
        <li>Status: <?=htmlspecialchars($auction['STATUS'])?></li>
        <li>Category: <?=htmlspecialchars($auction['ITEM_CATEGORY'])?></li>
        <li>Name: <?=htmlspecialchars($auction['ITEM_CAPTION'])?></li>
        <li>Description: <?=htmlspecialchars($auction['ITEM_DESCRIPTION'])?></li>
        <li>Photo<br /><img src="auctionPhoto.php?id=<?=$auction['AUCTION_ID']?>" alt="<?=htmlspecialchars($auction['ITEM_CAPTION'])?>"></li>
        <li>Reserve Price: $ <?=htmlspecialchars($auction['AMOUNT'])?></li>
        <li>Deadline: <?=htmlspecialchars($auction['CLOSE_TIME'])?></li>
        <li>
          <form action="pay.php">
            <button onclick="alert('Successfully Paid!')">Confirm</button>
          </form>
        </li>
      </ul>
    </section>

<?php include('inc/footer.php'); ?>