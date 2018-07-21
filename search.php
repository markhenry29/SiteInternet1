<?php 
    session_start();
    include('inc/functions.inc.php');
    $bdd = bdd_connexion();

    include('inc/page_offset.inc.php');
    /* Si l'utilisateur effectue une recherche avec la barre */
    if(isset($_GET['search']) AND !empty($_GET['search']))
    {
        $search = htmlspecialchars($_GET['search']);
        /*$search_array = explode(" ", $search);*/
        $bool = false;
        $reqcount = $bdd->query('SELECT * FROM videos WHERE titre LIKE "%'.$search.'%"ORDER BY titre ASC ');
        $req = $bdd->query('SELECT * FROM videos WHERE titre LIKE "%'.$search.'%"ORDER BY titre ASC '." LIMIT ".$offset.",".$nbvideos);
        if($req->rowCount() == 0)
            $bool = true;
    }

    /* Si l'utilisateur a cliqué sur une catégorie */
    else if(isset($_GET['categorie']) AND !empty($_GET['categorie']))
    {
        $categorie = intval($_GET['categorie']);
        $reqcount = $bdd->query('SELECT * FROM videos v INNER JOIN videos_categories vc on v.id = vc.id_videos 
                            AND id_categories = '.$categorie);
        $req = $bdd->query('SELECT * FROM videos v INNER JOIN videos_categories vc on v.id = vc.id_videos 
                            AND id_categories = '.$categorie." LIMIT ".$offset.",".$nbvideos);

        /* ces 3 lignes servent à récupérer la catégorie dans $search */
        $reqcategorie = $bdd->query('SELECT * from categories WHERE id = '.$categorie);
        $cat = $reqcategorie->fetch();
        $search = "catégorie ".$cat['categorie'];

        if($req->rowCount() == 0)
            $bool = true;
    }

    else
        header("Location: accueil.php");
    
    $nbButtonAffichage = intval(($reqcount->rowCount() - 1) / $nbvideos) + 1;
 ?>

<!DOCTYPE html>
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
                    if($bool AND isset($search))
                    {
                        echo "Aucun résultat pour: \"".$search."\"";
                    }
                 ?>
                <?php 
                    include('inc/section_videos.inc.php');
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