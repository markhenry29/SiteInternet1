<?php 
	session_start();
	include('inc/functions.inc.php');
    $bdd = bdd_connexion();


	if(isset($_SESSION['id']))
	{
		$requser = $bdd->prepare("SELECT * FROM membre WHERE id = ?");
	$requser->execute(array($_SESSION['id']));
	$user = $requser->fetch();

	if(isset($_POST['newpseudo']) AND !empty($_POST['newpseudo']) AND $_POST['newpseudo'] != $user['pseudo'])
	{

		$newpseudo = htmlspecialchars($_POST['newpseudo']);
		$insertpseudo = $bdd->prepare("UPDATE membre SET pseudo = ? WHERE id = ?");
		$insertpseudo->execute(array($newpseudo, $_SESSION['id']));
		header("Location: profil.php?id=".$_SESSION['id']);
	}

	if(isset($_POST['newmail']) AND !empty($_POST['newmail']) AND $_POST['newmail'] != $user['mail'])
	{

		$newmail = htmlspecialchars($_POST['newmail']);
		$insertpseudo = $bdd->prepare("UPDATE membre SET mail = ? WHERE id = ?");
		$insertpseudo->execute(array($newmail, $_SESSION['id']));
		header("Location: profil.php?id=".$_SESSION['id']);
	}

	if(isset($_POST['newmdp1']) AND !empty($_POST['newmdp1']) AND isset($_POST['newmdp2']) AND !empty($_POST['newmdp2']))
	{
		$mdp1 = sha1($_POST['newmdp1']);
		$mdp2 = sha1($_POST['newmdp2']);

		if($mdp1 == $mdp2)
		{
			$insertmdp = $bdd->prepare("UPDATE membre SET mdp = ? WHERE id = ?");
			$insertmdp->execute(array($mdp1, $_SESSION['id']));
			header("Location: profil.php?id=".$_SESSION['id']);
		}

		else
		{
			$msgerreur = "Vos mots de passe ne sont pas les mêmes !";
		}
	}

	if(isset($_FILES['avatar']) AND !empty($_FILES['avatar']['name']))
	{
		$tmax = 2097152;
		$extensionsValides = array('jpg', 'jpeg', 'png', 'gif');
		if($_FILES['avatar']['size'] <= $tmax)
		{
			$extensionUpload = strtolower(substr(strrchr($_FILES['avatar']['name'], '.'), 1));

			if(in_array($extensionUpload, $extensionsValides))
			{
				$chemin = "membre/avatar/".$_SESSION['id'].'.'.$extensionUpload;
				$res = move_uploaded_file($_FILES['avatar']['tmp_name'], $chemin);

				if($res)
				{
					$updateAvatar = $bdd->prepare("UPDATE membre SET avatar = :avatar WHERE id = :id");
					$updateAvatar->execute(array(
						'avatar' => $_SESSION['id'].".".$extensionUpload,
						'id' => $_SESSION['id']
					));
				}
				else
					$msgerreur = "Erreur durant l'importation de votre photo de profil";
			}

			else
				$msgerreur = "Votre photo de profil doit être au format jpg, jpeg, png ou gif !";
		}

		else
			$msgerreur = "Votre photo de profil ne doit pas dépasser 2 Mo";
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
			<h2> Editeur de profil</h2>
			<div align="right">
				<a href="profil.php?id=<?php echo $_SESSION['id'] ?>">Retour à votre profil</a>
			</div>
			<br />
			<div align="center">
				<form method="POST" action="" enctype="multipart/form-data">
					<table class="col-6">
						<tr>
							<td align="right"><label >Pseudo: </label></td>						
							<td><input class="form-control form-control-sm col-12" type="text" name="newpseudo" placeholder="Pseudo" value="<?php echo $user['pseudo']?>"></td>
						</tr>
						<tr>
							<td align="right"><label>Mail: </label></td>
							<td><input class="form-control form-control-sm col-12" type="email" name="newmail" placeholder="Mail" value="<?php echo $user['mail'] ?>"></td>
						</tr>
						<tr>
							<td align="right"><label>Mot de passe: </label></td>
							<td><input class="form-control form-control-sm col-12" type="password" name="newmdp1" placeholder="Mot de passe"></td>
						</tr>
						<tr>
							<td align="right"><label>Confirmation de mot de passe: </label></td>
							<td><input class="form-control form-control-sm col-12" type="password" name="newmdp2" placeholder="Confirmation de mot de passe"></td>
						</tr>
						<tr>
							<td align="right"><label>Avatar: </label></td>
							<td><input type="file" name="avatar"></td>
						</tr>
						<tr><td><input class="btn" type="submit" value="Valider les changements !"></td></tr>
					</table>
				</form>
				<?php if(isset($msgerreur)){ echo $msgerreur;} ?>
			</div>
		</div>
	</div>

	<?php 
		include('inc/script.inc.php');
	 ?>
</body>
</html>

<?php 
	}

	else
		header("Location: connexion.php");
 ?>