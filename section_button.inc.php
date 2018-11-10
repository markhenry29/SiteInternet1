
<div class="btn-toolbar" role="toolbar">
    <?php 
        
        $i = 1;
        /* code pour savoir sur quel url aller quand on click sur un bouton */
        if(isset($_GET['search']) AND !empty($_GET['search']) AND isset($_GET['categorie']) AND !empty($_GET['categorie']))
        {         
            $href = $pathinfo['basename']."search=".$search."?categorie=".$categorie;
        }

        else
        {
            if(isset($_GET['categorie']) AND !empty($_GET['categorie']))
                $href = $pathinfo['basename']."?categorie=".$categorie;

            else
            {
                if(isset($_GET['search']) AND !empty($_GET['search']))
                    $href = $pathinfo['basename']."?search=".$search;

                else
                    $href = $pathinfo['basename']."?";
            }
        }   

        if(isset($_GET['search']) OR isset($_GET['categorie']))
            $href = $href."&";

        /* code pour savoir à quel page on est */
        if(!isset($_GET['page']))   
            $page = 1;

        else
            $page = intval($_GET['page']);
        
    ?>
        <ul class="pagination pagination-lg row">
            
        <?php 
            if($page > 1)
            {
            ?>
            <li><a class="btn btn-pagination" href="<?php echo $href."page=".($page-1);?>"><span class="fa fa-angle-double-left"></span><?php echo "  Précédent" ?></a></li>
            <?php
            }
         ?>
        
    <?php
        $j = $nbButtonAffichage;
        while($j > 0 && $nbButtonAffichage != 1)
        {
    ?>
        <li><a class="btn btn-pagination" href="<?php echo $href."page=".$i;?>"><?php echo $i;?></a></li>
    <?php 
            $j--;
            $i++;
        }
     ?>

     <?php 
        

        if($page < $i - 1)
        {
        ?>
            <li><a class="btn btn-pagination" href="<?php echo $href."page=".($page+1);?>"><?php echo "Suivant  " ?><span class="fa fa-angle-double-right"></span></a></li>
        <?php 
        }
     ?>
        </ul>
        
</div>