<?php 
	session_start();
	include('inc/functions.inc.php');
		
	$nbCharMin = 4;
	$nbCharMax = 25;
	if(isset($_POST['formInscription']))
	{
		$pseudo = htmlspecialchars($_POST['pseudo']);
		$mail1 = htmlspecialchars($_POST['mail1']);
		$mail2 = htmlspecialchars($_POST['mail2']);
		$mdp1 = sha1($_POST['mdp1']);
		$mdp2 = sha1($_POST['mdp2']);

		if(!empty($pseudo) AND !empty($mail1) AND !empty($mail2) AND !empty($mdp1) AND !empty($mdp2))
		{
			$plength = strlen($pseudo);
			if($plength <= 255)
			{
				if(preg_match("#^[\w]{2,}$#", $pseudo))
				{


					$r1 = $bdd->prepare("SELECT * FROM membre WHERE id = ?");
					$r1->execute(array($pseudo));
					if($r1->rowCount() == 0)
					{
						if($mail1 == $mail2)
						{
							if(filter_var($mail1, FILTER_VALIDATE_EMAIL))
							{
								$r2 = $bdd->prepare("SELECT * FROM membre WHERE mail = ?");
								$r2->execute(array($mail1));

								if($r2->rowCount() == 0)
								{
									if($mdp1 == $mdp2)
									{
										if(preg_match("#.{".$nbCharMin.",".$nbCharMax."}#", $_POST['mdp1']))
										{
											$avatar = "default.png";
											$insert = $bdd->prepare("INSERT INTO membre(pseudo, mail, mdp, avatar) VALUES(?, ?, ?, ?)");
											$insert->execute(array($pseudo, $mail1, $mdp1, $avatar));
											$_SESSION['compte_cree'] = "Votre compte a bien été enregistré !";
											header('Location: confirmation_compte.php');
										}
										else
											$erreur = "Votre mot de passe doit être compris entre ".$nbCharMin." et ".$nbCharMax." caractères !";
										 
									}
									else
										$erreur = "Vos mot de passes ne sont pas les mêmes !";
								}

								else
									$erreur = "Ce mail est déjà utilisé !";
							}
							else
								$erreur = "Votre mail est invalide !";
						}
						else
							$erreur = "Vos mails ne sont pas les mêmes !";
					}
					else
						$erreur = "Désolé, mais ce pseudo est déjà pris !";
				}
				else
					$erreur ="Le pseudo doit contenir seulements des caractères ou des chiffres ! (2 caractères minimum, sans espaces)";
			}
			else
				$erreur = "Votre pseudo ne doit pas avoir plus de 255 caractères !";
		}
		else
		{
			$erreur = "Tout les champs doivent être remplis !";
		}
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
				<h2>Zone d'inscription</h2>
				<form method="POST" action="">
					<table class="col-6">
						<tr >
							<td align="right">
								<label for="pseudo">Pseudo: </label>
							</td>
							<td>
								<input class="form-control form-control-sm col-12" type="text" name="pseudo" placeholder="Pseudo" value="<?php if(isset($pseudo)){ echo $pseudo;} ?>">
							</td>
						</tr>
						<tr>
							<td align="right">
								<label for="mail1">Mail: </label>
							</td>
							<td>
								<input class="form-control form-control-sm col-12" type="email" name="mail1" placeholder="B.B.C@gmail.com" value="<?php if(isset($mail1)){ echo $mail1;} ?>">
							</td>
						</tr>
						<tr>
							<td align="right">
								<label for="mail2">Confirmation du mail: </label>
							</td>
							<td>
								<input class="form-control form-control-sm col-12" type="email" name="mail2" placeholder="B.B.C@gmail.com" value="<?php if(isset($mail2)){ echo $mail2;} ?>">
							</td>
						</tr>
						<tr>
							<td align="right">
								<label for="mdp1">Mot de passe: </label>
							</td>
							<td>
								<input class="form-control form-control-sm col-12" type="password" name="mdp1" placeholder="Votre mot de passe" >
							</td>
						</tr>
						<tr>
							<td align="right">
								<label for="mdp2">Confirmation mot de passe: </label>
							</td>
							<td>
								<input class="form-control form-control-sm col-12" type="password" name="mdp2" placeholder="Confirmation mot de passe">
							</td>
						</tr>
						<tr>
							<td></td>
							<br />
							<td align="right">
								<input class="btn" type="submit" name="formInscription" value="Valider !">
							</td>
						</tr>
					</table>
				</form>

				<?php 
					if(isset($erreur))
					{
						?>
						<div class="alert alert-danger alert-dissmissible fade show">
	                        <button type="button" class="close" data-dismiss="alert">&times</button>
	                        <?php 
	                            echo $erreur;
	                         ?>
	                    </div>
				<?php  
					}
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