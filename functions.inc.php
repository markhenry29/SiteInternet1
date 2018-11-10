<?php 
    include('inc/simple_html_dom.php');

    function bdd_connexion()
    {
        try{
            $bdd = new PDO("mysql:host=engineernwbdd.mysql.db;dbname=engineernwbdd", "engineernwbdd", "DarkAssassin93", [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
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

    function get_string_between($string, $start, $end){
        $string = ' ' . $string;
        $ini = strpos($string, $start);
        if ($ini == 0) return '';
        $ini += strlen($start);
        $len = strpos($string, $end, $ini) - $ini;
        return substr($string, $ini, $len);
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

    function after ($this, $inthat)
    {
        if (!is_bool(strpos($inthat, $this)))
        return substr($inthat, strpos($inthat,$this)+strlen($this));
    }

    function is_youtube($lien)
    {
        return strpos($lien,"https://www.youtube.com/watch") !== FALSE;
    }
    function is_pornhub($lien)
    {
        return strpos($lien,"https://fr.pornhub.com/view_video.php") !== FALSE;
    }
    

    function thumbnail_tag($lien)
    {
        $src = "video/lien/".$lien;
        $isyoutube = is_youtube($lien);
        $ispornhub = is_pornhub($lien);
        $res = '<video class="embed-responsive-item" src="'.$src.'"></video>';
        
        if($isyoutube || $ispornhub)
        {
            if($isyoutube)
            {
                $youtubeid = after('=', $lien);
                //$src = "https://i.ytimg.com/vi/".$youtubeid."/mqdefault.jpg";
                $src = "https://img.youtube.com/vi/".$youtubeid."/mqdefault.jpg";
            }
            
            if($ispornhub)
            {
                $pornhub_html = get_html_pornhub($lien);
                $i = 0;
                if(!is_bool(gettype($pornhub)))
                {
                    foreach($pornhub_html->find('meta') as $e)
                    {
                        $i++;
                        if($i == 9)
                            $thumbnail = $e->content;
                    }
                    $src = $thumbnail;
                }
            }

            $res = '<img class="embed-responsive-item" src="'.$src.'">';
        }
        

            


        return $res;
    }

    function video_tag($lien)
    {
        $youtubeid = after('=', $lien);
        if(is_youtube($lien))
        {
            return '<iframe  src="https://www.youtube.com/embed/'.$youtubeid.'" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>';
        }

        return '<video class="embed-responsive-item" src="video/lien/'.$lien.'" controls></video>';
    }

    function get_html_pornhub($lien)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $lien);
        curl_setopt($ch, CURLOPT_USERAGENT,'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.17 (KHTML, like Gecko) Chrome/24.0.1312.52 Safari/537.17');
        curl_setopt($ch, CURLOPT_AUTOREFERER, true); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_VERBOSE, 1);
        $html = str_get_html(curl_exec($ch));
        if(curl_errno($ch))
        {
            //echo 'erreur curl: '.curl_error($ch);
        }

        

        curl_close($ch);
        return $html;
    }

    /*
        @Return 1 if get_html_pornhub() worked, 0 if not 
    */
    function insert_info_bdd_pornhub($lien, $bdd)
    {
        $html = get_html_pornhub($lien);
        if(empty($html) || !isset($html))
        {
            return 0;
        }
        
        
        
        // catégories
        $cat = $html->find('iframe');
        $string = urldecode(array_pop($cat)->src).'<br>';
        $tags = get_string_between($string, '[context_tag]=', '&');
        $category = get_string_between($string, '[context_category]=', '&');
        if($tags == "" || $category == "")
        {
            return 0;
        }
        // Pour trouver le titre (16) et la thumbnail(9)
        $i = 0;
        foreach($html->find('meta') as $e)
        {
            $i++;
            if($i == 16)
                $title = $e->content;
        }
        
        echo $title;
        //les tags et category sont maintenant des arrays 
        $arr_tags = explode(',', $tags);
        $arr_category = explode(',', $category);
        // we insert the video first
        $req = $bdd->prepare("INSERT INTO videos(titre, lien, vues) VALUES(?,?,?)");   
        $req->execute(array($title, $lien, $vues));

        /* Je veux l'id de la nouvelle vidéo */
        $req = $bdd->prepare("SELECT * FROM videos ORDER BY id DESC LIMIT 1");
        $req->execute(array());
        $rowpornhub = $req->fetch();

        /* We insert the tags and the categories in the tables if it doesnt exist */
        foreach($arr_tags as $t)
        {
            
            $req = $bdd->prepare("INSERT INTO tags(tag) VALUES(?)");
            $req->execute(array($t));
            $req2 = $bdd->prepare("SELECT * FROM tags WHERE tag='".$t."'");
            $req2->execute(array());
            $tagrow = $req2->fetch();

            $req3 = $bdd->prepare("INSERT INTO videos_tags(id_video, id_tag) VALUES(?,?)");
            $req3->execute(array($rowpornhub['id'], $tagrow['id']));
        }

        foreach($arr_category as $c)
        {
            
            $req = $bdd->prepare("INSERT INTO categories(categorie) VALUES(?)");
            $req->execute(array($c));
            $req = $bdd->prepare("SELECT * FROM categories WHERE categorie='".$c."'");
            $req->execute(array());
            $catrow = $req->fetch();

            $req = $bdd->prepare("INSERT INTO videos_categories(id_videos, id_categories) VALUES(?,?)");
            $req->execute(array($rowpornhub['id'], $catrow['id']));
        }
        

        

        return 1;
    }

    // pourcentage like dislike de la vidéo
    function get_ratio_pornhub($html)
    {
        return array_pop(array_reverse($html->find('.percent')));
    }

    // Pour trouver les views
    function get_views_pornhub($html)
    {
        return array_pop($html->find('.count'));
    }
 ?>