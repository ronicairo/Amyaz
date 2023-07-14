<!DOCTYPE html>    
<html lang="fr">

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

<body class="container1">
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

	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
		<title>Conjugueur DARIJA</title>
		<meta name="Content-Type" content="UTF-8">
		<meta name="Content-Language" content="fr">
		<link rel="stylesheet" href="bootstrap-4.6.0-dist/css/bootstrap.min.css">  
		<link rel="stylesheet" href="bootstrap-4.6.0-dist/css/bootstrap-grid.min.css"> 
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">		
		<link rel="stylesheet" href="conjugueur.css">  
	</head>
<body class="background">  
<div class="container">
<?php
if (isset($_POST['recherche'])) $recherche=strtolower($_POST['recherche']); else ($recherche='');
if (isset($_GET['cherchef'])) $cherchef=strtolower($_GET['cherchef']); else ($cherchef='');

$fichierJson = 'verbes.json';
$json = file_get_contents($fichierJson);
$jsonParse = json_decode($json,true);
$totalItems = count($jsonParse["items"]);
for ($i = 0; $i <= $totalItems - 1; $i++) {
	if ($recherche == $jsonParse["items"][$i]["verbeF"] || $cherchef == $jsonParse["items"][$i]["forme"]) {
		$reponse[$i]["verbeD"] = $jsonParse["items"][$i]["verbeD"];
		$reponse[$i]["verbeDAR"]= $jsonParse["items"][$i]["verbeDAR"];
		$reponse[$i]["verbeF"]= $jsonParse["items"][$i]["verbeF"];
		$reponse[$i]["forme"]= $jsonParse["items"][$i]["forme"];
	} 
}	
$resultats = "";
if (empty($reponse)){
	$divresult = "<div class=\"alert alert-danger\">Il n'y a pas de résultat pour votre recherche</div>";
} else {
	$divresult = "<div class=\"alert alert-dark\">Résultats de votre recherche </div>";
	$nbReponses = count($reponse);		
	foreach ($reponse as $valeurs){
		$resultats .= "<li class=\"list-group-item resultats\"><i class=\"bi bi-file-earmark-text\"></i> <a href=\"conjug.php?trans=defaut&pref&terme=".$valeurs["verbeDAR"]."\">".$valeurs["verbeF"]." - ".$valeurs["verbeD"]." - ".$valeurs["verbeDAR"]."</a></li>\n";
	}
}
?>
<!-- barre de navigation -->
	<br>
	<form class="container d-flex col-10" action="cherche.php" method="POST">
		  <input class="form-control mr-sm-1" type="search" placeholder="Chercher un verbe" aria-label="recherche" name="recherche">
		  <button class="btn btn-dark " type="submit"><svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
  <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
</svg></button>
		</form>
	<br>
	<?php echo $divresult; ?>
	<ul class="list-group list-group-flush">  
		<?php echo $resultats; ?>
	</ul>
</div>
<?php include "include/footer.html"; ?>
<!-- Optional JavaScript; choose one of the two! -->
    <script src="bootstrap-4.6.0-dist/js/jquery-3.6.0.min.js"></script>
    <script src="bootstrap-4.6.0-dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>