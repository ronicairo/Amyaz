<!DOCTYPE html>    
<html lang="fr" style="width:100%; height:100%;">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="viewport" content="width=device-width">
  <meta name="keyword" content="amyaz, tarifit, rif, rifain, apprendre, tamazight, berbère, lexique, dictionnaire">
  <meta name="description" content="Apprenez le rifain avec le premier dictionnaire français-rifain en ligne. En plus d'un riche lexique en langue berbère, vous disposez de plusieurs verbes conjugués en rifain et une documentation sur la langue rifaine et berbère.">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
  <link rel="stylesheet" href="css/navbar.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
  <title>Amyaz - Apprendre le rifain</title>
  <link rel="shortcut icon" href="favicon.png" type="image/png">
  <link rel="stylesheet" href="conjugueur.css">
  <!-- Global site tag (gtag.js) - Google Analytics -->
  <script async src="https://www.googletagmanager.com/gtag/js?id=G-67RVRXNX9Q"></script>
  <script>
    window.dataLayer = window.dataLayer || [];

    function gtag() {
      dataLayer.push(arguments);
    }
    gtag('js', new Date());

    gtag('config', 'G-67RVRXNX9Q');
  </script>
</head>

<body style="flex-grow:1;">
  <section class="page">
    <!-- Barre de navigation -->

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
      <a class="navbar-brand" href="#">
        <img src="logo.jpg" width="40" height="30" class="d-inline-block align-top" alt="">
        AMYAZ
      </a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
          <li class="nav-item">
            <a class="nav-link" href="index.php">Dictionnaire</a>
          </li>
          <li class="nav-item">
            <a class="nav-link active" href="indexconjug.php">Conjugaison<span class="sr-only">(current)</span></a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="documentation.html">Documentation</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="index1.php">Mots manquants ?</a>
          </li>
          </li>
        </ul>
        <li class="form-inline my-2 my-lg-0">
          <a href="index2.php" class="btn btn-dark mr-1">English</a>
        </li>
      </div>
    </nav>

<br>
    
    <!-- Fin de la barre de navigation -->
</div>

<form class="container d-flex col-10" action="cherche.php" method="POST">
		  <input class="form-control mr-sm-1" type="search" placeholder="Chercher un verbe" aria-label="recherche" name="recherche">
		  <button class="btn btn-dark " type="submit"><svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
  <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
</svg></button>
		</form>
		
<?php
	$fichierJson = 'verbes.json';
	$json = file_get_contents($fichierJson);
	$jsonParse = json_decode($json,true);
	$totalItems = count($jsonParse["items"]);
?>

<section class="wow fadeIn animated" style="visibility: visible; animation-name: fadeIn;">
	<div class="container">
	<div class="dropdown-divider"></div>
		<div class="row compteur">
			<!-- counter -->
			<div class="col"></div>
			<div class="col bottom-margin text-center counter-section wow fadeInUp sm-margin-bottom-ten animated" data-wow-duration="300ms" style="visibility: visible; animation-duration: 300ms; animation-name: fadeInUp;">
				<i class="bi bi-journal-text medium-icon"></i>

				<span class="timer counter alt-font appear" data-to="980" data-speed="7000"><?php echo $totalItems; ?></span>
				<p class="counter-title">verbes conjugués</p>
			</div>
			<div class="col"></div>
			<!-- end counter -->
		</div>
	</div>
</section>

<?php
	echo "<div class=\"container\">";
	echo"<ul class=\"list-group list-group-flush \">";
	for ($i = 0; $i <= $totalItems - 1; $i++) {
	echo "<li class=\"list-group-item \"><i class=\"bi bi-file-earmark-text\"></i> <a href=\"conjug.php?trans=defaut&pref&terme=".$jsonParse["items"][$i]["verbeDAR"]."\">".$jsonParse["items"][$i]["verbeD"]." - ".$jsonParse["items"][$i]["verbeDAR"]." - ".$jsonParse["items"][$i]["verbeF"]."</a></li>";
	}
	echo"</ul></div>";
?>
</div>
<?php include "include/footer.html"; ?>
<!-- Optional JavaScript; choose one of the two! -->
    <script src="bootstrap-4.6.0-dist/js/jquery-3.6.0.min.js"></script>
    <script src="bootstrap-4.6.0-dist/js/bootstrap.bundle.min.js"></script>
    <script src="conjug.js"></script>
</body>  

</html>    
