<?php 
	include("inc/functions.inc.php");
	$bdd = bdd_connexion();
	 session_start();

	if(isset($_GET['t'], $_GET['id']) AND !empty($_GET['t']) AND !empty($_GET['id']))
	{
		$getid = intval($_GET['id']);
		$gett = intval($_GET['t']);
		if(isset($_SESSION['id']))
			$sessionid = $_SESSION['id'];

		$check = $bdd->prepare("SELECT * FROM videos WHERE id = ?");
		$check->execute(array($getid));

		if($check->rowCount() == 1)
		{
			if($gett == 1)
			{
				$check_like = $bdd->prepare("SELECT id FROM likes WHERE id_video = ? AND id_membre = ?");
				$check_like->execute(array($getid, $sessionid));
				if($check_like->rowCount() >= 1)
				{
					$del = $bdd->prepare("DELETE FROM likes WHERE id_video = ? AND id_membre = ?");
					$del->execute(array($getid, $sessionid));
				}

				else
				{
					$del = $bdd->prepare("DELETE FROM dislikes WHERE id_video = ? AND id_membre = ?");
					$del->execute(array($getid, $sessionid));
					$ins = $bdd->prepare("INSERT INTO likes(id_video, id_membre) VALUES(?,?)");
					$ins->execute(array($getid, $sessionid));
				}
				
			}

			elseif($gett == 2)
			{
				$check_dislike = $bdd->prepare("SELECT id FROM dislikes WHERE id_video = ? AND id_membre = ?");
				$check_dislike->execute(array($getid, $sessionid));
				if($check_dislike->rowCount() >= 1)
				{
					$del = $bdd->prepare("DELETE FROM dislikes WHERE id_video = ? AND id_membre = ?");
					$del->execute(array($getid, $sessionid));
				}

				else
				{
					$del = $bdd->prepare("DELETE FROM likes WHERE id_video = ? AND id_membre = ?");
					$del->execute(array($getid, $sessionid));
					$ins = $bdd->prepare("INSERT INTO dislikes(id_video, id_membre) VALUES(?,?)");
					$ins->execute(array($getid, $sessionid));
				}
			}
			header("Location: http://engineersdoporn.com/EDP/display.php?id=".$getid);
		}

		else
		{
			exit("Erreur fatale");
		}
	}

	else
		exit("Erreur fatale");
 ?>