<?php 
	session_start();
 ?>

<!DOCTYPE html>
<html>
<head>
	<?php 
		include('inc/head.inc.php');
	 ?>
</head>
<body>
	<header>
		<?php 
			include('inc/header.inc.php');
		 ?>
	</header>
	<div class="container">
			<div class="row">
				<div class="alert alert-success">
					<?php echo $_SESSION['compte_cree'] ?>
					<a href="accueil.php">Retour</a>
				</div>
			</div>
	</div>
		<footer>
			<?php 
				include('inc/footer_discours.inc.php');
			 ?>
		</footer>
		<?php 
			include('inc/script.inc.php');
		 ?>
</body>
</html>