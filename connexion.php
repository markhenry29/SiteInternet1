<?php 
	session_start();
	include('inc/functions.inc.php');
    $bdd = bdd_connexion();


	if(isset($_POST['formconnect']))
	{
		$mail = htmlspecialchars($_POST['mailconnect']);
		$mdp = sha1($_POST['mdpconnect']);

		if(!empty($mail) AND !empty($mdp))
		{
			$requser = $bdd->prepare("SELECT * FROM membre WHERE mail = ? AND mdp = ?");
			$requser->execute(array($mail, $mdp));
			if($requser->rowCount() == 1)
			{
				$userInfo = $requser->fetch();
				$_SESSION['id'] = $userInfo['id'];
				$_SESSION['pseudo'] = $userInfo['pseudo'];
				$_SESSION['mail'] = $userInfo['mail'];

				header("Location: profil.php? id=".$_SESSION['id']);
			}
			else
				$erreur = "Identifiant ou mot de passe incorrect !";

		}
		else
			$erreur = "Tout les champs doivent être remplis";
	}

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
				<h2> Zone de connexion </h2><br /><br />
				<form method="POST" action="">
					<div class="form-group">	
						<label>Email: </label>
						<input class="form-control col-xs-12 col-sm-8 col-md-5" type="email" name="mailconnect" placeholder="Mail">
					</div>
					<div class="form-group">
						<label>Mot de passe:</label>
						<input class="form-control col-xs-12 col-sm-8 col-md-5" type="password" name="mdpconnect" placeholder="Mot de passe">
					</div>
					<input class="btn" type="submit" name="formconnect" value="Se connecter !">
				</form>
				<?php
					if(isset($erreur))
						echo '<font color=red>'.$erreur.'</font>';
				?>
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