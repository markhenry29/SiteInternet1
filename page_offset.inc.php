<?php     
    $page = 0;
    if(isset($_GET['page']) AND $_GET['page'] > 0)
    {
        $page = intval($_GET['page']);
        $page--;
    }

    $nbvideos = 12;
    $offset = $nbvideos*$page;
?>