<?php 
if (!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] !== "on") {
    header('HTTP/1.1 403 Forbidden: TLS Required');
    // Optionally output an error page here
    exit(1);
}
include('inc/header.php');
?>
	<section class="box" id="login_box">
        <?php 
        if ( $_SESSION['login_message']):
        ?>
        <h1><?=htmlspecialchars($_SESSION['login_message'])?></h1>
        <?php
        unset($_SESSION['login_message']);
        endif;
        ?>
    	<form action = "process.php?mode=login" method="POST" enctype="multipart/form-data">
            E-mail<br />
    		<input type="text" required="required" name="username" /><br />
    		Password <br />
    		<input type="password" required="required" name="pass" /><br />
    		<input type="submit" value="Log-in" class="table_btn" />
    	</form>
    	<form action = "register.php">
    		<input type="submit" value="Register" class="table_btn" />
    	</form>
   </section>
   <section class="box" id="login_box">
    <p>For test1<br />E-mail : user1@test.com<br />Password: user1<p>
    <p>For test2<br />E-mail : user2@test.com<br />Password: user2<p>
   </section>
<?php include('inc/footer.php'); ?>