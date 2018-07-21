<?php 
    function bdd_connexion()
    {
        try{
            $bdd = new PDO("mysql:host=engineernwbdd.mysql.db;dbname=engineernwbdd", "engineernwbdd", "DarkAssassin93");
        }
        catch(PDOException $e)
        {
            print "Erreur: ".$e->getMessage(). "<br />";
            die();
        }
        $bdd->query("SET NAMES UTF8");

        return $bdd;
    }

    function ratio($a, $b)
    {
    	if($a == 0 AND $b == 0)
    		return 100;

    	return floatval($a/($a+$b))*100;
    }

    function like_count($id)
    {
        $likes = $GLOBALS['bdd']->prepare("SELECT * FROM likes WHERE id_video = ?");
        $likes->execute(array($id));
        return $likes->rowCount();
    }

    function dislike_count($id)
    {
        $bdd = $GLOBALS['bdd'];
        $dislikes = $bdd->prepare("SELECT * FROM dislikes WHERE id_video = ?");
        $dislikes->execute(array($id));
        return $dislikes->rowCount();
    }

    function like_dislike_ratio($id)
    {
        $likes = like_count($id);
        $dislikes = dislike_count($id);

        return ratio($likes, $dislikes);
    }

    function member_vote($table, $id_video, $id_member)
    {
        $m = $GLOBALS['bdd']->prepare("SELECT * FROM ? WHERE id_membre = ? AND id_video = ?");
        $m->execute(array($table,$id_member, $id_video));
        return ($m->rowCount() == 1) ? true : false;
    }

    function time_format($time)
    {
        if($time->m > 0)
        {
            return "Il y a ".$time->format("%m"." mois");
        }
        else
        {
            if($time->d > 0)
            {
                return "Il y a ". $time->format("%d")." jours";
            }
            else
            {
                if($time->h > 0)
                    return "Il y a ". $time->format("%h")." heure(s)";

                else
                {
                    if($time->i > 0)
                        return "Il y a ". $time->format("%i")." minute(s)";

                    else
                        return "Il y a ". $time->format("%s")." secondes";
                }
            }
        }
    }
 ?>