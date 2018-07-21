<?php 
	session_start();
	include('inc/functions.inc.php');
    $bdd = bdd_connexion();

if(isset($_GET['id']) AND $_GET['id'] > 0)
{
	$getid = intval($_GET['id']);
	$requser = $bdd->prepare('SELECT * FROM membre WHERE id = ?');
	$requser->execute(array($getid));
	$userInfo = $requser->fetch();

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
		
		<div align="center">
			<h2> Profil de <?php echo $userInfo['pseudo']; ?> </h2><br /><br />
			<?php 
				if(!empty($userInfo['avatar']))
				{
			?>
				<img src="membre/avatar/<?php echo $userInfo['avatar'] ?>" width=150><br />
			<?php  		
				}
			 ?>
			<p>Pseudo : <?php echo $userInfo['pseudo']; ?></p>
			<p>Mail : <?php echo $userInfo['mail']; ?></p>
			<?php
				if(isset($_SESSION['id']) AND $userInfo['id'] == $_SESSION['id'])
				{
					
			?>
					<a href="editeurprofil.php"> Editer le profil</a>
					<a href="deconnexion.php">Se deconnecter</a>
				<?php
				}
				?>
		</div>
	</div>

	<?php 
		include('inc/script.inc.php');
	 ?>
</body>
</html>

<?php 
}
 ?>