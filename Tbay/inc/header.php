<?php session_start(); ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
    <title>T bay</title>
    <meta charset="utf-8" />
    <link rel="stylesheet" href="css/stylesheet.css" />
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet" />
    <!-- Google Fonts -->
    <link href='http://fonts.googleapis.com/css?family=Changa+One%7cOpen+Sans:400italic,700italic,400,700,800' rel='stylesheet' type='text/css' />
    <!-- DataTables CSS -->
    <link rel="stylesheet" type="text/css" href="DataTables/media/css/jquery.dataTables.css" />
    <!-- DataTables jQuery -->
    <script type="text/javascript" src="DataTables/media/js/jquery.js"></script>
    <script type="text/javascript" src="DataTables/media/js/jquery.dataTables.js"></script>
</head>
<body>
    <?php
    if(isset($_SESSION['user']) && !empty($_SESSION['user'])):
    ?>
    Hi <?=htmlspecialchars($_SESSION['username'])?>! How's it going?
    <a href="logout.php" id="login"><i class="fa fa-sign-out"></i>Log out</a>
    <?php
    else:
    ?>
    <a href="login.php" id="login"><i class="fa fa-sign-in"></i> My T bay</a>
    <?php
    endif;
    ?>
    <header>
        <h1><a href="index.php">T bay</a></h1>
        <nav>
            <ul>
            <?php
            if(isset($_SESSION['user']) && !empty($_SESSION['user'])):
            ?>
                <li><a href="index.php"><i class="fa fa-list"></i> Browse</a></li>
                <li><a href="auctionlist.php"><i class="fa fa-btc"></i> Bidding</a></li>
                <li><a href="pay.php"><i class="fa fa-credit-card"></i> Paying</a></li>
                <li><a href="listitem.php"><i class="fa fa-list-ol"></i> My listing</a></li>
            <?php
            else:
            ?>
                <li><i class="fa fa-list fa-1x"></i><a href="index.php">Browse</a></li>
            <?php
            endif;
            ?>
            </ul>
        </nav>
    </header>