<?php
include('inc/header.php');
$message = $_REQUEST['message'];
?>
<section class="box">
	<h2><?=htmlspecialchars($message)?></h2>
	<form action="listitem.php">
		<input type="submit" value="Back to list" class="table_btn" id="btn" />
	</form>
</section>
<?php include('inc/footer.php'); ?>