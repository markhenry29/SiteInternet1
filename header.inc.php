

<div class=" container-fluid" align="center">
    <img src="img/logo/EDP.png" style=" width:110px;">
    <!-- Ici c'est la barre de recherche -->
  
        <form method="GET" action="search.php">
            <div class="input-group col-sm-12 col-12 col-md-8">
                <div class="input-group-prepend">
                    <span class="input-group-text">EDP</span>
                </div>
                <input class="form-control" type="text" name="search" placeholder="Ton film de cul préféré...">
                <div class="input-group-append">
                    <span class="input-group-text fa fa-search"></span>
                </div>
            </div>
        </form>

</div>
    <!-- Ici c'est la bar de navigation (Accueil, catégories...) -->
<div class="row">
    <nav class="navbar">
        <ul class="nav">
            <li class="nav-item">
                <a class ="nav-link" href="accueil.php">Accueil</a>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-hasexpanded="false" href="#">Catégories</a>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="<?php echo "search.php?categorie=11" ?>">Française</a>
                    <a class="dropdown-item" href="<?php echo "search.php?categorie=1" ?>">Asian</a>
                    <a class="dropdown-item" href="<?php echo "search.php?categorie=6" ?>">Black</a>
                    <a class="dropdown-item" href="<?php echo "search.php?categorie=12" ?>">Arabe</a>
                    <a class="dropdown-item" href="<?php echo "search.php?categorie=4" ?>">Latina</a>
                    <a class="dropdown-item" href="<?php echo "search.php?categorie=8" ?>">Anal</a>
                    <a class="dropdown-item" href="<?php echo "search.php?categorie=13" ?>">White</a>
                    <a class="dropdown-item" href="<?php echo "search.php?categorie=5" ?>">Redhead</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="#">HD</a>
                </div>
            </li>
            <li class="nav-item"><a class ="nav-link" href="#">Communauté</a></li>
            <li class="nav-item"><a class ="nav-link" href="inscription.php">S'inscrire </a></li>
            <li class="nav-item"><a class ="nav-link" href="<?php if(isset($_SESSION['id'])){ echo "profil.php?id=".$_SESSION['id'];} else{ echo "connexion.php";} ?>">Se connecter</a></li>
            <li class="nav-item"><a class ="nav-link" href="about.php" data-toggle="tooltip" title="Mais qui se cache derrière..." >Qui somme nous ?</a></li>
        </ul>
    </nav>
</div>
