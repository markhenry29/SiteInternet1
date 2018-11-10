<div class="row">
    <?php 
        /* Il faut différencier les id de vidéos, cela dépend si il a cliqué sur une catégorie ou non. */
        
        
        while($video = $req->fetch())
        {
            $v = (isset($categorie) AND !empty($categorie)) ? $video['id_videos'] : $video['id'];
            $ratio = number_format(like_dislike_ratio($v))."% ";
            $lien = $video['lien'];
            $title = $video['titre'];
            $views = $video['vues'];
            // réf. vers la vidéo (renvoie vers le site original ou non ?)
            $href = "display.php?id=".$v;

            $format_thumbnail = thumbnail_tag($lien);



            if(is_pornhub($lien))
            {
                $html = get_html_pornhub($lien);
                $views = get_views_pornhub($html);
                $ratio = get_ratio_pornhub($html)." ";
                $href = $lien;
            }

            if($nbvideos > 0)
            {
     ?>
            <div class="col-sm-12 col-12 col-md-3">
                <div class="embed-responsive embed-responsive-16by9">
                    <a href="<?php echo $href ?>"><?php echo $format_thumbnail; ?></a>
                </div>
                <a href="<?php echo $href ?>" data-toggle="tooltip" title="<?= $video['titre']?>"><?php echo $title ;?></a>
                <p><?= $views." Vues"." ".$ratio?><span class="fa fa-thumbs-up" style="color:green"></span></p>
            </div>
    <?php 
                $nbvideos--;
            }
            else
                break;
        }


     ?>
</div>