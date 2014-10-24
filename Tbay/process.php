<?php
session_start();
if (!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] !== "on") {
	header('HTTP/1.1 403 Forbidden: TLS Required');
	exit(1);
}

switch($_GET['mode']){
	case 'add':
		require_once '/u/ryooit/Documents/CS105/myDB/openDatabase.php';
		$addItem = $database->prepare('
			INSERT INTO AUCTION
			(STATUS, SELLER, OPEN_TIME, CLOSE_TIME, ITEM_CATEGORY, ITEM_CAPTION, ITEM_DESCRIPTION, ITEM_PHOTO)
			VALUES
			(1, :seller, NOW(), :closetime, :category, :itemname, :description, :photo)
			');
		$seller = $_SESSION['user'];
		$closetime = $_POST['closetime'];
		$category = $_POST['category'];
		$name = $_POST['item_name'];
		$description = $_POST['description'];
		$photoFile = fopen($_FILES['photo']['tmp_name'], 'rb');
		$addItem->bindValue(':seller', $seller, PDO::PARAM_STR);
		$addItem->bindValue(':closetime', $closetime, PDO::PARAM_STR);
		$addItem->bindValue(':category', $category, PDO::PARAM_STR);
		$addItem->bindValue(':description',$description,PDO::PARAM_STR);
		$addItem->bindValue(':itemname',$name,PDO::PARAM_STR);
		$addItem->bindValue(':photo',$photoFile,PDO::PARAM_LOB);

		$addItem->execute();
		$addItem->closeCursor();

		$addNewBid = $database->prepare('
			INSERT INTO BID
			(AMOUNT)
			VALUES
			(:amount)
			');
		$amount = $_POST['price'];

		$addNewBid->bindValue(':amount',$amount,PDO::PARAM_INT);
		$addNewBid->execute();
		$addNewBid->closeCursor();
		header('Location: listitem.php');
		break;

	case 'delete':
		require_once '/u/ryooit/Documents/CS105/myDB/openDatabase.php';
		$deleteItem = $database->prepare('
			UPDATE AUCTION
			SET STATUS = 2
			WHERE AUCTION_ID = :auctionId;
			');
		$id = $_POST['id']; 		
		$deleteItem->bindValue(':auctionId', $id, PDO::PARAM_INT);
		$deleteItem->execute();
		$deleteItem->closeCursor();
		header('Location: listitem.php');
		break;

	case 'update':
		require_once '/u/ryooit/Documents/CS105/myDB/openDatabase.php';
		$updateItem = $database->prepare('
			UPDATE AUCTION
			SET ITEM_CATEGORY = :cate,
				ITEM_CAPTION = :caption,
				ITEM_DESCRIPTION = :desc,
				CLOSE_TIME = :dead,
				ITEM_PHOTO = :photo
			WHERE AUCTION_ID = :auctionId;
			');
		$thisAuctionId = $_POST['user'];
		$category = $_POST['category'];
		$caption = $_POST['item_name'];
		$description = $_POST['description'];
		$deadline = $_POST['closetime'];
		$photoFile = fopen($_FILES['photo']['tmp_name'], 'rb');

		$updateItem->bindValue(':auctionId',$thisAuctionId, PDO::PARAM_INT);
		$updateItem->bindValue(':cate',$category, PDO::PARAM_INT);
		$updateItem->bindValue(':caption',$caption, PDO::PARAM_STR);
		$updateItem->bindValue(':desc',$description,PDO::PARAM_STR);
		$updateItem->bindValue(':dead',$deadline,PDO::PARAM_STR);
		$updateItem->bindValue(':photo',$photoFile,PDO::PARAM_LOB);

		$status1=$updateItem->execute();
		$updateItem->closeCursor();

		$updateBid = $database->prepare('
			UPDATE BID
			SET AMOUNT = :amount
			WHERE BID_ID = :auctionId;
		');
		$amount = $_POST['price'];

		$updateBid->bindValue(':auctionId', $thisAuctionId, PDO::PARAM_INT);
		$updateBid->bindValue(':amount',$amount,PDO::PARAM_STR);

		$status2=$updateBid->execute();
		$updateBid->closeCursor();
		if($status1 && $status2)
			header('Location: success.php?message="Successfully updated!"');
		else
			header('Location: failure.php?message="Error!"');
		break;

	case 'bid':
		require_once '/u/ryooit/Documents/CS105/myDB/openDatabase.php';

		$thisAuctionId = $_POST['id'];
		$amount = $_POST['amount'];
		$highBid = $database->prepare('
		SELECT AMOUNT
		FROM BID
		WHERE BID_ID = :auctionId;
		');

		$highBid->bindValue(':auctionId',$thisAuctionId, PDO::PARAM_INT);
		$highBid->execute();
		$getValue = $highBid->fetch();
		$highBid->closeCursor();

		if($getValue['AMOUNT'] > $amount) {
			$message = 'Current price is $' . $getValue['AMOUNT']. '. Please bid higher than this.';
			$_SESSION['bid_error'] = htmlspecialchars($message);
			header('Location: bid.php?id=' . $thisAuctionId);
		}
		else {

			$updateBid = $database->prepare('
				UPDATE BID
				SET AMOUNT = :amount
				WHERE BID_ID = :auctionId;
				');
			$thisAuctionId = $_POST['id'];
			$amount = $_POST['amount'];

			$updateBid->bindValue(':auctionId', $thisAuctionId, PDO::PARAM_INT);
			$updateBid->bindValue(':amount',$amount,PDO::PARAM_INT);
			$updateBid->execute();
			$updateBid->closeCursor();

			$message='Successfully Bid!';
			$_SESSION['bid_success'] = htmlspecialchars($message);
			header('Location: auctionlist.php');
		}
		break;

	case 'register':
		require_once '/u/ryooit/Documents/CS105/myDB/openDatabase.php';
		require('password.php');
		$errors = array();
		$emailAddress = $_POST['username'];
		if( !preg_match("/([\w\-]+\@[\w\-]+\.[\w\-]+)/", $emailAddress) ){
			$errors[] = "Invalid email format.";
		}
		$surname = $_POST['surname'];
		if ( !preg_match("/^[a-zA-Z ]*$/", $surname) ){
			$errors[] = "Only letters and white space allowed in first name.";
		}
		$forename = $_POST['forename'];
		if ( !preg_match("/^[a-zA-Z ]*$/", $forename) ){
			$errors[] = "Only letters and white space allowed in last name.";
		}
		if( $_POST['pass'] !== $_POST['pass_confirm'] ){
			$errors[] = "Passwords do not match.";
		}
		$checkEmail = $database->prepare('
			SELECT 1
			FROM PERSON
			WHERE EMAIL_ADDRESS = :email;
			');
		$checkEmail->bindValue(':email', $emailAddress, PDO::PARAM_STR);
		$checkEmail->execute();
		$isEmail = $checkEmail->fetch();
		$checkEmail->closeCursor();
		if($isEmail){
			$errors[] = "Email address is already being used. Choose another one";
		}
		if (sizeof($errors)){
			$_SESSION['errorarray'] = $errors;
			header('Location: accountinfo.php');
		}
		else{
			$encrypted = password_hash($_POST['pass'], PASSWORD_BCRYPT);
			$registerUser = $database->prepare('
				INSERT INTO PERSON
				(SURNAME, FORENAME, PASSWORD, EMAIL_ADDRESS)
				VALUES
				(:surname, :forename, :password, :email_address);
				');
			$registerUser->bindValue(':surname', $surname, PDO::PARAM_STR);
			$registerUser->bindValue(':forename', $forename, PDO::PARAM_STR);
			$registerUser->bindValue(':password', $encrypted, PDO::PARAM_STR);
			$registerUser->bindValue(':email_address', $emailAddress, PDO::PARAM_STR);

			$registerUser->execute();
			$registerUser->closeCursor();
			$_SESSION['login_message'] = htmlspecialchars("You've been added! Fill out fields to log in!");
			header('Location: index.php');
			break;
		}

	case 'login':
	require_once '/u/ryooit/Documents/CS105/myDB/openDatabase.php';
	require('password.php');
	$login_success = $database->prepare('
		SELECT PERSON_ID, PASSWORD, CONCAT(FORENAME, " ", SURNAME) AS USER
		FROM PERSON
		WHERE EMAIL_ADDRESS = :email;
		');
	$login_success->bindValue(':email', $_POST['username'], PDO::PARAM_STR);
	$login_success->execute();
	$login_info = $login_success->fetch();
	$_SESSION['username'] = $login_info['USER'];
	if ( password_verify($_POST['pass'], $login_info['PASSWORD']) ){
		$_SESSION['user'] = $login_info['PERSON_ID'];
		header('Location:index.php');
	}
	else{
		$_SESSION['login_message'] = htmlspecialchars("Invalid email or password!");
		header('Location: login.php');
	}


	break;

}
?>
