<?php 
    session_start();
    include('inc/functions.inc.php');
    $bdd = bdd_connexion();

    $page = 0;
    if(isset($_GET['page']) AND $_GET['page'] > 0)
    {
        $page = intval($_GET['page']);
        $page--;
    }

    $nbvideos = 12;
    $offset = $nbvideos*$page;
    /* Si l'utilisateur effectue une recherche avec la barre */
    if(isset($_GET['search']) AND !empty($_GET['search']))
    {
        $full_search_request = "";
        $search = htmlspecialchars($_GET['search']);
        $search_array = explode(" ", $search);
        $debut = true;
        

        foreach($search_array as $word)
        {   
            $part_search_request = "";
            $is_tag = false;
            $is_categorie = false;
            $is_title = false;
            if(!$debut)
                $full_search_request = $full_search_request." UNION ";
            $debut = false;

            $reqtag = $bdd->query("SELECT * FROM tags WHERE tag = '".$word."'");
            $reqcat = $bdd->query("SELECT * FROM categories WHERE categorie = '".$word."'");

            if($reqtag->rowCount() == 1)
                $is_tag = true;

            else
            {
                if($reqcat->rowCount() == 1)
                    $is_cat = true;

                else
                    $is_title = true;
            }
                

            if($is_tag)
                $part_search_request = " JOIN videos_tags ON videos_tags.id_video = videos.id 
                                         JOIN tags ON tags.id = videos_tags.id_tag 
                                         WHERE tag = '".$word."' OR ";
            if($is_cat)
                $part_search_request = " JOIN videos_categories ON videos.id = videos_categories.id_video
                                         JOIN categories ON categories.id = videos_categories.id_categorie
                                         WHERE categorie = '".$word."' OR ";

            if($is_title)
                $part_search_request = "WHERE";


            $full_search_request = $full_search_request."SELECT titre,lien,vues FROM  videos ".$part_search_request." titre LIKE '%".$word."%'";
        }   
        $bool = false;
        //$reqcount = $bdd->query('SELECT * FROM videos WHERE id > 32 AND titre LIKE "%'.$search.'%"ORDER BY titre ASC ');
        //$req = $bdd->query('SELECT * FROM videos WHERE id > 32 AND titre LIKE "%'.$search.'%"ORDER BY titre ASC '." LIMIT ".$offset.",".$nbvideos);
        $reqcount = $bdd->query($full_search_request);
        $req = $bdd->query($full_search_request." LIMIT ".$offset.",".$nbvideos);
        if($req->rowCount() == 0)
            $bool = true;
    }

    /* Si l'utilisateur a cliqué sur une catégorie */
    else if(isset($_GET['categorie']) AND !empty($_GET['categorie']))
    {
        $categorie = intval($_GET['categorie']);
        $reqcount = $bdd->query('SELECT * FROM videos v JOIN videos_categories vc on v.id = vc.id_videos 
                            AND id_categories = '.$categorie);
        $req = $bdd->query('SELECT * FROM videos v JOIN videos_categories vc on v.id = vc.id_videos 
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
                ?>
                        <p><?= "Aucun résultat pour: \"".$search."\"";?></p>
                <?php
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