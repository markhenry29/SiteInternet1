<div class="row">
    <?php 
        /* Il faut différencier les id de vidéos, cela dépend si il a cliqué sur une catégorie ou non. */
        

        while($video = $req->fetch())
        {
            $v = (isset($categorie) AND !empty($categorie)) ? $video['id_videos'] : $video['id'];
            $ratio = like_dislike_ratio($v);
            if($nbvideos > 0)
            {
     ?>
            <div class="col-sm-12 col-12 col-md-3">
                <div class="embed-responsive embed-responsive-16by9">
                    <a href="<?php echo "display.php?id=".$v; ?>"><video class="embed-responsive-item" src="video/lien/<?php echo $video['lien'];?>" type="video/mp4"></video></a>
                </div>
                <a href="<?php echo "display.php?id=".$v; ?>" data-toggle="tooltip" title="<?= $video['titre']?>"><?php echo $video['titre']; ?></a>
                <p><?= $video['vues']." Vues"." ".number_format($ratio)."% "?><span class="fa fa-thumbs-up" style="color:green"></span></p>
            </div>
    <?php 
                $nbvideos--;
            }
            else
                break;
        }


     ?>
</div>