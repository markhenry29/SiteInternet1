<?php 
	include('inc/functions.inc.php');
    $bdd = bdd_connexion();

    if(isset($_POST['inputLien']))
   	{
   		$lien = htmlspecialchars($_POST['inputLien']);
   		if(!empty($lien))
   		{
   			$check = $bdd->prepare("SELECT * FROM videos WHERE lien = ?");
   			$check->execute(array($lien));
   			if($check->rowCount() == 0)
   			{
	   			if(is_youtube($lien))
	   			{
					$youtubeid = after("=", $lien);
	   				$content = file_get_contents("https://www.youtube.com/get_video_info?video_id=".$youtubeid);
	                parse_str($content, $contenu_youtube);
	                $insert = $bdd->prepare("INSERT INTO videos(titre, lien, vues) VALUES(?,?,?)");
	                $insert->execute(array($contenu_youtube['title'], $lien, $contenu_youtube['view_count']));
	                $success = "Vidéo Youtube enregistré !";
	   			}

	   			else if(is_pornhub($lien))
	   			{
	   				if(!insert_info_bdd_pornhub($lien, $bdd))
	   				{
						$erreur = "le lien pornhub n'est pas valide !";	   					
	   				}
	   			}

   				else
   				{
   					$erreur = "N'accepte que des liens youtubes ! (pour l'instant)";
   				}
   			}
   			else
	   			$erreur = "Cette vidéo existe déjà !";
   		}

   		else
   			$erreur = "Ce champs ne doit pas être vide !";
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
			<div class="line"></div>
			<form method="POST" action="">
				<div class="form-group">
					<label for="inputLien">Lien vidéo:</label>
					<input class="form-control col-sm-12 col-md-8" type="text" name="inputLien" placeholder="https://www.youtube.com/watch?v=5ANmDdmAMt8">
				</div>
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

				if(isset($success))
				{
					?>
					<div class="alert alert-success">
						<?= $success; ?>
					</div>
					<?php 
				}
			 ?>
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