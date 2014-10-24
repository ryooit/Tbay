<?php
header('Content-Type: image/jpeg');
require_once '/u/ryooit/Documents/CS105/myDB/openDatabase.php';
$openPhotoQuery = $database->prepare(<<<'SQL'
	SELECT ITEM_PHOTO 
	FROM AUCTION
	WHERE AUCTION_ID = :auctionId;
SQL
);
$thisAuctionId=$_GET['id'];
$openPhotoQuery->bindValue(':auctionId',$thisAuctionId, PDO::PARAM_INT);
$openPhotoQuery->execute();
$photo=$openPhotoQuery->fetch();
header('Content-Length: '.strlen($photo['ITEM_PHOTO']));
echo $photo['ITEM_PHOTO'];
$openPhotoQuery->closeCursor();
// if ($openPhotoQuery->execute()){
//  	$photo=$openPhotoQuery->fetch();
//  	header('Content-Length: '.strlen($photo['ITEM_PHOTO']));
//  	if (strlen($photoContents['ITEM_PHOTO']))
//  		echo $photo['ITEM_PHOTO'];
//  	elseif ($photo['ITEM_PHOTO'] == 0 || !$photo['ITEM_PHOTO'])
//  		echo '<img src="https://encrypted-tbn1.gstatic.com/images?q=tbn:ANd9GcQav7E6RoVzYv--WqzjeHQCxhH5Dsi8BnX5Lq619rAgtoeY8qh-Ow" />'
//  }
