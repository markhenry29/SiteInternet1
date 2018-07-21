<?php 
    include('inc/functions.inc.php');
    $bdd = bdd_connexion();

    session_start();


    if(isset($_GET['id']) AND $_GET['id'] > 0)
    {
        $getid = intval($_GET['id']);
        $reqvideo = $bdd->prepare("SELECT * FROM videos WHERE id = ?");
        $reqvideo->execute(array($getid));
        $infovideo = $reqvideo->fetch();

        /* code pour mettre le nombre de vues à jour */
        // $temps_session = 10;
        // $temps_actuel = date("U");
        // $ip_user = $_SERVER['REMOTE_ADDR'];
        

        $ajouter_vue_x1 = $bdd->query("UPDATE videos SET vues = vues + 1 WHERE id=".$getid);

        /* code pour la saisie de commentaire */
        if(isset($_POST['commentSection']) AND !empty($_POST['commentSection']))
        {
            if(isset($_POST['comment']) AND !empty($_POST['comment']))
            {
                $commentaire = htmlspecialchars($_POST['comment']);
                $reqcommentaire = $bdd->prepare("INSERT INTO espace_commentaire(id_video, id_membre, commentaire) VALUES(?, ?, ?)");
                $reqcommentaire->execute(array($infovideo['id'], $_SESSION['id'], $commentaire));
            }

            else
                $erreurForm = "Il faut écrire un commentaire !";
        }

        /* code pour afficher les commentaires */
        $nbCommentaireMax = 10;
        $reqAffichageCommentaire = $bdd->prepare("SELECT * FROM espace_commentaire WHERE id_video = ?");
        $reqAffichageCommentaire->execute(array($infovideo['id']));

        /* requetes pour trouver le nombre de likes et dislikes de la video. */
        $likes = like_count($infovideo['id']);
        $dislikes = dislike_count($infovideo['id']);
        $ratio = like_dislike_ratio($infovideo['id']);

        if(isset($_SESSION['id']))
        {
            $m_like = member_vote("likes", $infovideo['id'], $_SESSION['id']);
            $m_dislike = member_vote("dislikes", $infovideo['id'], $_SESSION['id']);    
        }
        else
        {
            $m_like = false;
            $m_dislike = false;
        }
        
 ?>

<!doctype html>
<html class="no-js" lang="">
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
        <!-- Mon site est ici -->
        <div class="container">
            

            <section>
                <div class="row">
                    <div class="col-sm-12 col-md-7">
                        <div class="display">
                            <div class="embed-responsive embed-responsive-16by9">
                                <video class="embed-responsive-item" src="video/lien/<?php echo $infovideo['lien']; ?>" controls></video>
                            </div class="row">
                            <h3><?php echo $infovideo['titre'];?></h3>

                            <div class= "display-video-footer container">
                                <?php if(isset($_SESSION['id'])){ ?>
                                <a href="http://engineersdoporn.com/EDP/like.php?t=1&id=<?= $infovideo['id']?>" id="pouce-vert" class="btn dark-purple" <?php if($m_like){echo 'style="color:green;"';}?> onclick="thumbsUp()"><span class="fa fa-thumbs-up">J'aime</span></a>
                                <a href="http://engineersdoporn.com/EDP/like.php?t=2&id=<?= $infovideo['id']?>" id="pouce-rouge" class="btn dark-purple" <?php if($m_dislike){echo 'style="color:red;"';}?> onclick="thumbsDown()"><span class="fa fa-thumbs-down"></span></a>
                                <?php } ?>
                                <h6><?php echo $infovideo['vues']." Vues"; ?></h6>
                                
                                <div class="vote">
                                    <div class="vote-progress" style="width:<?= $ratio.'%';?>"></div>
                                </div>
                                <i class="fa fa-thumbs-up vote-total"><span id="vote-like"><?= " ".$likes ?></span></i>
                                <i class="fa fa-thumbs-down vote-total"><span id="vote-dislike"><?= " ".$dislikes ?></span></i>
                                

                                <!-- <span class="align-text-middle" id="ratio"></span> -->
                                
                            </div>
                        </div>
                        <!-- Section commentaire ici -->
                        <button class="btn" id="afficher-commentaire" onclick="showOrHideContent('commentaires')">Cacher les commentaires</button>
                        <div class="display display-comment" id="commentaires">
                            <?php 
                                if(isset($_SESSION['id']))
                                {                             
                             ?>
                                <form method="POST">
                                    <div class="form-group">
                                        <label for="comment">Commentaire:</label>
                                        <textarea class="form-control" rows="5" name="comment" placeholder="Allez-y, mettez votre commentaire ici !"></textarea>
                                        <input class="btn btn-default" type="submit" name="commentSection" >
                                    </div>
                                </form>
                                <?php 
                                    if(isset($erreurForm))
                                    {
                                    ?>
                                    <div class="alert alert-danger alert-dissmissible fade show">
                                        <button type="button" class="close" data-dismiss="alert">&times</button>
                                        <?php 
                                            echo $erreurForm;
                                         ?>
                                    </div>
                                <?php  
                                    }
                                 ?>
                            <?php 
                                }

                                else
                                {
                                 ?>
                                    <div id="display-connectetoi">
                                        <a href="connexion.php">Tu veux poster un commentaire ? Connecte toi en cliquant ici !</a>
                                    </div>
                                 <?php 
                                }
                             ?>

                             <?php 
                                while($infoCommentaire = $reqAffichageCommentaire->fetch())
                                {
                                    if($nbCommentaireMax > 0)
                                    {
                                        $reqMembre = $bdd->prepare("SELECT * FROM membre WHERE id = ?");
                                        $reqMembre->execute(array($infoCommentaire['id_membre']));
                                        $membreInfo = $reqMembre->fetch();

                                        /* code pour gérer la date*/
                                        $dateCommentaire = new DateTime($infoCommentaire['date']);
                                        $date = new DateTime();
                                        $dateDifference = $date->diff($dateCommentaire);
                                        $tempsCommentaire = time_format($dateDifference);
                                            
                             ?>
                                        <div class="media">
                                            <div class="media-left">
                                                <img  src="<?php echo "membre/avatar/".$membreInfo['avatar'] ?>" class="media-object" width=70 >
                                            </div>
                                            <div class="media-body">
                                                <div class="row">
                                                    <h6><a href="<?php echo "profil.php?id=".$membreInfo['id']; ?>"><?php echo $membreInfo['pseudo']; ?></a></h6>
                                                    <span class="display-temps"><?php if(isset($tempsCommentaire)){echo $tempsCommentaire;} ?></span>
                                                </div>
                                                <p><?php echo $infoCommentaire['commentaire'] ?></p>
                                            </div>
                                        </div>
                             <?php
                                        $nbCommentaireMax--;
                                    }
                                }
                              ?>
                        </div>
                    </div>
                    
                    <!-- TODO: SUGGESTION EN FONCTION DE LA VIDEO VISIONNÉE -->
                    
                        <div class="col-md-5 col-sm-12 display"> 
                            <div class="row">
                                <div class="col-md-6 col-sm-12 sug">
                                    <?php 
                                        $nbsuggestion = 10;
                                        $reqsuggestion = $bdd->query("SELECT * FROM videos");
                                        $reqsuggestion->execute(array());
                                        
                                        while($nbsuggestion > 0)
                                        {
                                            if($r = $reqsuggestion->fetch())
                                            {
                                                $ratio = like_dislike_ratio($r['id']);
                                                if($nbsuggestion <= 9)
                                                {
                                                ?>
                                                    </div>
                                                    <div class="col-md-6 col-sm-12 sug">
                                                <?php 
                                                }
                                                ?>
                                                <div class="embed-responsive embed-responsive-16by9">
                                                    <a href="<?php echo "display.php?id=".$r['id']; ?>"><video class="embed-responsive-item" src="video/lien/<?php echo $r['lien']; ?>" type="video/mp4"></video></a>
                                                </div>
                                                <a href="<?php echo "display.php?id=".$r['id']; ?>" data-toggle="tooltip" title="<?= $r['titre'] ?>"><?php echo $r['titre']?></a>
                                                <p><?= $r['vues']." Vues"." ".number_format($ratio)."% "?><span class="fa fa-thumbs-up" style="color:green"></span></p>
                                                <?php
                                            }
                                            $nbsuggestion--;
                                        } 
                                    ?>
                                
                            </div>
                        </div>
                    
                </div>
            </section>

            
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
    <?php 


    }

    else
        echo "erreur lors du chargement de la page";

     ?>