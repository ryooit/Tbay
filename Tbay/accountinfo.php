<?php include('inc/header.php');?>
    <section class="box" id="account">
    	<?php 
    	if($_SESSION['errorarray']):
    	?>
    	<p class="warning"><i class="fa fa-exclamation-triangle"></i> Please fix the following error(s):</p>
    	<?php
    	foreach($_SESSION['errorarray'] as $error):
    	?>
    	<p class="warning"><?=htmlspecialchars($error)?></p>
    	<?php
    	endforeach;
    	?>
    	<?php
    	unset($_SESSION['errorarray']);
    	endif;
    	?>
    	<form action="process.php?mode=register" method="POST" enctype="multipart/form-data">
    		Email: <br /><input type="text" required="required" name="username" /><br />
    		Password: <br /><input type="password" required="required" name="pass" /><br />
    		Password Confirm: <br /><input type="password" required="required" name="pass_confirm" /><br />
    		First Name: <br /><input type="text" required="required" name="forename" /><br />
    		Last Name: <br /><input type="text" name="surname" /><br />
    		<input type="submit" value="register" class="table_btn">
    	</form>
    </section>
<?php include('inc/footer.php'); ?>