<?php
    include('inc/functions.inc.php');
    $bdd = bdd_connexion();
    /* on affectue une requete sur la totalité des videos et on affiche "$nbvideos" videos. (currently 12) */
    /* ce code initialise le nombre de page à afficher et le nombre de vidéos. */

    $page = 0;
    if(isset($_GET['page']) AND $_GET['page'] > 0)
    {
        $page = intval($_GET['page']);
        $page--;
    }

    $nbvideos = 12;
    $offset = $nbvideos*$page;


    $all = $bdd->query("SELECT * FROM videos WHERE id > 32");
    $req = $bdd->prepare("SELECT * FROM videos WHERE id > 32 LIMIT ".$offset.",".$nbvideos);
    $req->execute(array());
    $nbButtonAffichage = intval(($all->rowCount() - 1) / $nbvideos) + 1;

 ?>

<!doctype html>
<html class="no-js" lang="">
    <head>
        <?php 
            include('inc/head.inc.php');
         ?>

        
    </head>
    <body>
        <!-- Mon site est ici -->
            <header>  
                <?php 

                    include('inc/header.inc.php');
                 ?>
            </header>
            <div class="container">
                

                <section>
                    <?php 
                        include("inc/section_videos.inc.php");
                     ?>

                    <?php 
                        $pathinfo = pathinfo(__FILE__);
                        include('inc/section_button.inc.php');
                        
                     ?>
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
