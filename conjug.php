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

<body>
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

<?php
header("Expires: Tue, 01 Jan 2000 00:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
if (isset($_GET['trans'])) setcookie('trans', $_GET['trans'], time()+3600*24, '/', '', true, true);
if(isset($_COOKIE['trans'])) $trans = $_COOKIE['trans'];
$pref='';
if (isset($_GET['pref'])) setcookie('pref', $_GET['pref'], time()+3600*24, '/', '', true, true);
if(isset($_COOKIE['pref'])) $pref = $_COOKIE['pref'];
echo $pref."<br />";
?>
<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
		<title>Conjugueur DARIJA</title>
		<meta name="Content-Type" content="UTF-8">
		<meta name="Content-Language" content="fr">
		<meta http-equiv="Cache-Control" content="no-cache"/>
		<meta http-equiv="Cache-Control" content="max-age=120"/>
		<link rel="stylesheet" href="bootstrap-4.6.0-dist/css/bootstrap.min.css">  
		<link rel="stylesheet" href="bootstrap-4.6.0-dist/css/bootstrap-grid.min.css"> 
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
		<link rel="stylesheet" href="conjugueur.css">  
	</head>
<?php
$je_inacc = ''; $je_inacc = ''; $tum_inacc = ''; $tuf_inacc = ''; $il_inacc = ''; $elle_inacc = ''; $nous_inacc = ''; $vousm_inacc = ''; $vousf_inacc = ''; $ils_inacc = ''; $elles_inacc = '';
$je_acc = ''; $je_acc = ''; $tum_acc = ''; $tuf_acc = ''; $il_acc = ''; $elle_acc = ''; $nous_acc = ''; $vousm_acc = ''; $vousf_acc = ''; $ils_acc = ''; $elles_acc = '';
$je_aor = ''; $je_aor = ''; $tum_aor = ''; $tuf_aor = ''; $il_aor = ''; $elle_aor = ''; $nous_aor = ''; $vousm_aor = ''; $vousf_aor = ''; $ils_aor = ''; $elles_aor = '';
$R1=''; $R2=''; $R3=''; $R4=''; $f=''; $t=''; 

/* init des paramètres passés en get */
//$terme = ''; $pref = ''; $sep = ''; 
$tiret = ''; 

if (isset($_GET['terme'])) $terme=$_GET['terme'];
//echo $terme;
$fichierJson = 'verbes.json';
$json = file_get_contents($fichierJson);
$jsonParse = json_decode($json,true);
$totalItems = count($jsonParse["items"]);
for ($i = 0; $i <= $totalItems - 1; $i++) {
	if ($terme == $jsonParse["items"][$i]["verbeDAR"]) {
		$t = $jsonParse["items"][$i]["verbeD"];
		$tf = $jsonParse["items"][$i]["verbeF"];
		$R1 = $jsonParse["items"][$i]["R1"];
		$R2 = $jsonParse["items"][$i]["R2"];
		$R3 = $jsonParse["items"][$i]["R3"];
		$R4 = $jsonParse["items"][$i]["R4"];
		$R5 = $jsonParse["items"][$i]["R5"];
		$R6 = $jsonParse["items"][$i]["R6"];
		$f = $jsonParse["items"][$i]["forme"];
	}
}	
//if (isset($_GET['pref'])) $pref=$_GET['pref'];
//if (isset($_GET['sep'])) $sep=$_GET['sep'];

if ($f == "C1") $forme = "include/C1.html"; if ($f == "C2") $forme = "include/C2.html"; if ($f == "C3") $forme = "include/C3.html"; elseif ($f == "C-v") $forme = "include/C-v.html"; elseif ($f == "v-C") $forme = "include/v-C.html"; elseif ($f == "v-c") $forme = "include/v-c.html"; elseif ($f == "v-c-v") $forme = "include/v-c-v.html"; elseif ($f == "C-c") $forme = "include/C-c.html"; elseif ($f == "c-c") $forme = "include/c-c.html"; elseif ($f == "C-v-c") $forme = "include/C-v-c.html"; elseif ($f == "c-v-C") $forme = "include/c-v-C.html"; elseif ($f == "c-c-v") $forme = "include/c-c-v.html"; elseif ($f == "c-v-c") $forme = "include/c-v-c.html"; 

$prefixe = "<span class=\"prefixe\">".$pref."</span>";
$R1_html_bold = "<span class=\"font-weight-bold\">".$R1."</span>";
$R2_html_bold = "<span class=\"font-weight-bold\">".$R2."</span>";
$R3_html_bold = "<span class=\"font-weight-bold\">".$R3."</span>";
$R4_html_bold = "<span class=\"font-weight-bold\">".$R4."</span>";
$R5_html_bold = "<span class=\"font-weight-bold\">".$R5."</span>";
$R6_html_bold = "<span class=\"font-weight-bold\">".$R6."</span>";


/* Les formes C */
/* Accompli = Passé*/
if ($f == "C1" || $f=="C2" || $f=="C3" || $f=="C4") {
$je_acc = $prefixe.$tiret."<span class=\"pref\"></span>".$tiret.$R1_html_bold.$R2_html_bold.$R3_html_bold."<span class=\"suffixe\">iɣ</span><br />";
$tum_acc = $prefixe.$tiret."<span class=\"pref\">t</span>".$tiret.$R1_html_bold.$R2_html_bold.$R3_html_bold."<span class=\"suffixe\">id</span><br />";
$tuf_acc = $prefixe.$tiret."<span class=\"pref\">t</span>".$tiret.$R1_html_bold.$R2_html_bold.$R3_html_bold."<span class=\"suffixe\">id</span><br />";
$il_acc =  $prefixe.$tiret."<span class=\"pref\">y</span>".$tiret.$R1_html_bold.$R2_html_bold.$R3_html_bold."<span class=\"suffixe\">a</span><br />";
$elle_acc = $prefixe.$tiret."<span class=\"pref\">t</span>".$tiret.$R1_html_bold.$R2_html_bold.$R3_html_bold."<span class=\"suffixe\">a</span><br />";
$nous_acc =  $prefixe.$tiret."<span class=\"pref\">n</span>".$tiret.$R1_html_bold.$R2_html_bold.$R3_html_bold."<span class=\"suffixe\">a</span><br />";
$vousm_acc =  $prefixe.$tiret."<span class=\"pref\">t</span>".$tiret.$R1_html_bold.$R2_html_bold.$R3_html_bold."<span class=\"suffixe\">im</span><br />";
$vousf_acc =  $prefixe.$tiret."<span class=\"pref\">t</span>".$tiret.$R1_html_bold.$R2_html_bold.$R3_html_bold."<span class=\"suffixe\">imt</span><br />";
$ils_acc = $prefixe.$tiret."<span class=\"pref\"></span>".$tiret.$R1_html_bold.$R2_html_bold.$R3_html_bold."<span class=\"suffixe\">in</span><br />";
$elles_acc = $prefixe.$tiret."<span class=\"pref\"></span>".$tiret.$R1_html_bold.$R2_html_bold.$R3_html_bold."<span class=\"suffixe\">int</span><br />";
}
/* Inaccompli = Présent*/
if ($f == "C1") {
$je_inacc = $prefixe.$tiret."<span class=\"pref\">tt</span>".$tiret.$R1_html_bold.$R2_html_bold.$R3_html_bold."<span class=\"suffixe\">eɣ</span><br />";
$tum_inacc = $prefixe.$tiret."<span class=\"pref\">tt</span>".$tiret.$R1_html_bold.$R2_html_bold.$R3_html_bold."<span class=\"suffixe\">ed</span><br />";
$tuf_inacc = $prefixe.$tiret."<span class=\"pref\">tt</span>".$tiret.$R1_html_bold.$R2_html_bold.$R3_html_bold."<span class=\"suffixe\">ed</span><br />";
$il_inacc = $prefixe.$tiret."<span class=\"pref\">it</span>".$tiret.$R1_html_bold.$R2_html_bold.$R3_html_bold."<br />";
$elle_inacc = $prefixe.$tiret."<span class=\"pref\">tt</span>".$tiret.$R1_html_bold.$R2_html_bold.$R3_html_bold."<br />";
$nous_inacc = $prefixe.$tiret."<span class=\"pref\">nt</span>".$tiret.$R1_html_bold.$R2_html_bold.$R3_html_bold."<br />";
$vousm_inacc = $prefixe.$tiret."<span class=\"pref\">tt</span>".$tiret.$R1_html_bold.$R2_html_bold.$R3_html_bold."<span class=\"suffixe\">em</span><br />";
$vousf_inacc = $prefixe.$tiret."<span class=\"pref\">tt</span>".$tiret.$R1_html_bold.$R2_html_bold.$R3_html_bold."<span class=\"suffixe\">emt</span><br />";
$ils_inacc = $prefixe.$tiret."<span class=\"pref\">tt</span>".$tiret.$R1_html_bold.$R2_html_bold.$R3_html_bold."<span class=\"suffixe\">en</span><br />";
$elles_inacc = $prefixe.$tiret."<span class=\"pref\">tt</span>".$tiret.$R1_html_bold.$R2_html_bold.$R3_html_bold."<span class=\"suffixe\">ent</span><br />";
}
if ($f == "C2") {
$je_inacc = $prefixe.$tiret."<span class=\"pref\">tt</span>".$tiret.$R4_html_bold.$R5_html_bold.$R6_html_bold."<span class=\"suffixe\">eɣ</span><br />";
$tum_inacc = $prefixe.$tiret."<span class=\"pref\">tt</span>".$tiret.$R4_html_bold.$R5_html_bold.$R6_html_bold."<span class=\"suffixe\">ed</span><br />";
$tuf_inacc = $prefixe.$tiret."<span class=\"pref\">tt</span>".$tiret.$R4_html_bold.$R5_html_bold.$R6_html_bold."<span class=\"suffixe\">ed</span><br />";
$il_inacc =  $prefixe.$tiret."<span class=\"pref\">it</span>".$tiret.$R4_html_bold.$R5_html_bold.$R6_html_bold."<br />";
$elle_inacc = $prefixe.$tiret."<span class=\"pref\">tt</span>".$tiret.$R4_html_bold.$R5_html_bold.$R6_html_bold."<br />";
$nous_inacc =  $prefixe.$tiret."<span class=\"pref\">nt</span>".$tiret.$R4_html_bold.$R5_html_bold.$R6_html_bold."<br />";
$vousm_inacc =  $prefixe.$tiret."<span class=\"pref\">tt</span>".$tiret.$R4_html_bold.$R5_html_bold.$R6_html_bold."<span class=\"suffixe\">em</span><br />";
$vousf_inacc =  $prefixe.$tiret."<span class=\"pref\">tt</span>".$tiret.$R4_html_bold.$R5_html_bold.$R6_html_bold."<span class=\"suffixe\">emt</span><br />";
$ils_inacc = $prefixe.$tiret."<span class=\"pref\">tt</span>".$tiret.$R4_html_bold.$R5_html_bold.$R6_html_bold."<span class=\"suffixe\">en</span><br />";
$elles_inacc = $prefixe.$tiret."<span class=\"pref\">tt</span>".$tiret.$R4_html_bold.$R5_html_bold.$R6_html_bold."<span class=\"suffixe\">ent</span><br />";
}
if ($f == "C3") {
$je_inacc = $prefixe.$tiret."<span class=\"pref\">tt</span>".$tiret.$R1_html_bold.$R2_html_bold.$R3_html_bold."<span class=\"suffixe\">iɣ</span><br />";
$tum_inacc = $prefixe.$tiret."<span class=\"pref\">tt</span>".$tiret.$R1_html_bold.$R2_html_bold.$R3_html_bold."<span class=\"suffixe\">id</span><br />";
$tuf_inacc = $prefixe.$tiret."<span class=\"pref\">tt</span>".$tiret.$R1_html_bold.$R2_html_bold.$R3_html_bold."<span class=\"suffixe\">id</span><br />";
$il_inacc = $prefixe.$tiret."<span class=\"pref\">it</span>".$tiret.$R1_html_bold.$R2_html_bold.$R3_html_bold."<span class=\"suffixe\">a</span><br />";
$elle_inacc = $prefixe.$tiret."<span class=\"pref\">tt</span>".$tiret.$R1_html_bold.$R2_html_bold.$R3_html_bold."<span class=\"suffixe\">a</span><br />";
$nous_inacc = $prefixe.$tiret."<span class=\"pref\">nt</span>".$tiret.$R1_html_bold.$R2_html_bold.$R3_html_bold."<span class=\"suffixe\">a</span><br />";
$vousm_inacc = $prefixe.$tiret."<span class=\"pref\">tt</span>".$tiret.$R1_html_bold.$R2_html_bold.$R3_html_bold."<span class=\"suffixe\">am</span><br />";
$vousf_inacc = $prefixe.$tiret."<span class=\"pref\">tt</span>".$tiret.$R1_html_bold.$R2_html_bold.$R3_html_bold."<span class=\"suffixe\">am</span><br />";
$ils_inacc = $prefixe.$tiret."<span class=\"pref\">tt</span>".$tiret.$R1_html_bold.$R2_html_bold.$R3_html_bold."<span class=\"suffixe\">an</span><br />";
$elles_inacc = $prefixe.$tiret."<span class=\"pref\">tt</span>".$tiret.$R1_html_bold.$R2_html_bold.$R3_html_bold."<span class=\"suffixe\">ant</span><br />";
}
if ($f == "C4") {
$je_inacc = $prefixe.$tiret."<span class=\"pref\">tt</span>".$tiret.$R1_html_bold.$R2_html_bold.$R3_html_bold."<span class=\"suffixe\">aɣ</span><br />";
$tum_inacc = $prefixe.$tiret."<span class=\"pref\">tt</span>".$tiret.$R1_html_bold.$R2_html_bold.$R3_html_bold."<span class=\"suffixe\">ad</span><br />";
$tuf_inacc = $prefixe.$tiret."<span class=\"pref\">tt</span>".$tiret.$R1_html_bold.$R2_html_bold.$R3_html_bold."<span class=\"suffixe\">ad</span><br />";
$il_inacc = $prefixe.$tiret."<span class=\"pref\">it</span>".$tiret.$R1_html_bold.$R2_html_bold.$R3_html_bold."<span class=\"suffixe\">a</span><br />";
$elle_inacc = $prefixe.$tiret."<span class=\"pref\">tt</span>".$tiret.$R1_html_bold.$R2_html_bold.$R3_html_bold."<span class=\"suffixe\">a</span><br />";
$nous_inacc = $prefixe.$tiret."<span class=\"pref\">nt</span>".$tiret.$R1_html_bold.$R2_html_bold.$R3_html_bold."<span class=\"suffixe\">a</span><br />";
$vousm_inacc = $prefixe.$tiret."<span class=\"pref\">tt</span>".$tiret.$R1_html_bold.$R2_html_bold.$R3_html_bold."<span class=\"suffixe\">am</span><br />";
$vousf_inacc = $prefixe.$tiret."<span class=\"pref\">tt</span>".$tiret.$R1_html_bold.$R2_html_bold.$R3_html_bold."<span class=\"suffixe\">amt</span><br />";
$ils_inacc = $prefixe.$tiret."<span class=\"pref\">tt</span>".$tiret.$R1_html_bold.$R2_html_bold.$R3_html_bold."<span class=\"suffixe\">an</span><br />";
$elles_inacc = $prefixe.$tiret."<span class=\"pref\">tt</span>".$tiret.$R1_html_bold.$R2_html_bold.$R3_html_bold."<span class=\"suffixe\">ant</span><br />";
}
/*Aoriste = Futur*/
if ($f == "C1" || $f=="C2" || $f=="C3" || $f=="C4") {
$je_aor = $prefixe.$tiret."<span class=\"pref\">ad </span>".$tiret.$R1_html_bold.$R2_html_bold.$R3_html_bold."<span class=\"suffixe\">eɣ</span><br />";
$tum_aor = $prefixe.$tiret."<span class=\"pref\">ad t</span>".$tiret.$R1_html_bold.$R2_html_bold.$R3_html_bold."<span class=\"suffixe\">ed</span><br />";
$tuf_aor = $prefixe.$tiret."<span class=\"pref\">ad t</span>".$tiret.$R1_html_bold.$R2_html_bold.$R3_html_bold."<span class=\"suffixe\">ed</span><br />";
$il_aor =  $prefixe.$tiret."<span class=\"pref\">ad y</span>".$tiret.$R1_html_bold.$R2_html_bold.$R3_html_bold."<br />";
$elle_aor = $prefixe.$tiret."<span class=\"pref\">ad t</span>".$tiret.$R1_html_bold.$R2_html_bold.$R3_html_bold."<br />";
$nous_aor =  $prefixe.$tiret."<span class=\"pref\">ad n</span>".$tiret.$R1_html_bold.$R2_html_bold.$R3_html_bold."<br />";
$vousm_aor =  $prefixe.$tiret."<span class=\"pref\">ad t</span>".$tiret.$R1_html_bold.$R2_html_bold.$R3_html_bold."<span class=\"suffixe\">em</span><br />";
$vousf_aor =  $prefixe.$tiret."<span class=\"pref\">ad t</span>".$tiret.$R1_html_bold.$R2_html_bold.$R3_html_bold."<span class=\"suffixe\">emt</span><br />";
$ils_aor = $prefixe.$tiret."<span class=\"pref\">ad </span>".$tiret.$R1_html_bold.$R2_html_bold.$R3_html_bold."<span class=\"suffixe\">en</span><br />";
$elles_aor = $prefixe.$tiret."<span class=\"pref\">ad </span>".$tiret.$R1_html_bold.$R2_html_bold.$R3_html_bold."<span class=\"suffixe\">ent</span><br />";
}

/* Les formes C-v */

/* Accompli = Passé*/
if ($f == "C-v" || $f=="C-v1") {
$je_acc = $prefixe.$tiret."<span class=\"pref\">e</span>".$tiret.$R1_html_bold.$R2_html_bold.$R3_html_bold."<span class=\"suffixe\">ɣ</span><br />";
$tum_acc = $prefixe.$tiret."<span class=\"pref\">te</span>".$tiret.$R1_html_bold.$R2_html_bold.$R3_html_bold."<span class=\"suffixe\">d</span><br />";
$tuf_acc = $prefixe.$tiret."<span class=\"pref\">te</span>".$tiret.$R1_html_bold.$R2_html_bold.$R3_html_bold."<span class=\"suffixe\">d</span><br />";
$il_acc =  $prefixe.$tiret."<span class=\"pref\">ye</span>".$tiret.$R1_html_bold.$R2_html_bold.$R3_html_bold."<span class=\"suffixe\"></span><br />";
$elle_acc = $prefixe.$tiret."<span class=\"pref\">te</span>".$tiret.$R1_html_bold.$R2_html_bold.$R3_html_bold."<span class=\"suffixe\"></span><br />";
$nous_acc =  $prefixe.$tiret."<span class=\"pref\">ne</span>".$tiret.$R1_html_bold.$R2_html_bold.$R3_html_bold."<span class=\"suffixe\"></span><br />";
$vousm_acc =  $prefixe.$tiret."<span class=\"pref\">te</span>".$tiret.$R1_html_bold.$R2_html_bold.$R3_html_bold."<span class=\"suffixe\">m</span><br />";
$vousf_acc =  $prefixe.$tiret."<span class=\"pref\">te</span>".$tiret.$R1_html_bold.$R2_html_bold.$R3_html_bold."<span class=\"suffixe\">mt</span><br />";
$ils_acc = $prefixe.$tiret."<span class=\"pref\">e</span>".$tiret.$R1_html_bold.$R2_html_bold.$R3_html_bold."<span class=\"suffixe\">n</span><br />";
$elles_acc = $prefixe.$tiret."<span class=\"pref\">e</span>".$tiret.$R1_html_bold.$R2_html_bold.$R3_html_bold."<span class=\"suffixe\">nt</span><br />";
}
/* Inaccompli = Présent*/
if ($f == "C-v") {
$je_inacc = $prefixe.$tiret."<span class=\"pref\">tte</span>".$tiret.$R1_html_bold.$R2_html_bold.$R3_html_bold."<span class=\"suffixe\">ɣ</span><br />";
$tum_inacc = $prefixe.$tiret."<span class=\"pref\">tte</span>".$tiret.$R1_html_bold.$R2_html_bold.$R3_html_bold."<span class=\"suffixe\">d</span><br />";
$tuf_inacc = $prefixe.$tiret."<span class=\"pref\">tte</span>".$tiret.$R1_html_bold.$R2_html_bold.$R3_html_bold."<span class=\"suffixe\">d</span><br />";
$il_inacc = $prefixe.$tiret."<span class=\"pref\">ite</span>".$tiret.$R1_html_bold.$R2_html_bold.$R3_html_bold."<span class=\"suffixe\"></span><br />";
$elle_inacc = $prefixe.$tiret."<span class=\"pref\">tte</span>".$tiret.$R1_html_bold.$R2_html_bold.$R3_html_bold."<span class=\"suffixe\"></span><br />";
$nous_inacc = $prefixe.$tiret."<span class=\"pref\">nte</span>".$tiret.$R1_html_bold.$R2_html_bold.$R3_html_bold."<span class=\"suffixe\"></span><br />";
$vousm_inacc = $prefixe.$tiret."<span class=\"pref\">tte</span>".$tiret.$R1_html_bold.$R2_html_bold.$R3_html_bold."<span class=\"suffixe\">m</span><br />";
$vousf_inacc = $prefixe.$tiret."<span class=\"pref\">tte</span>".$tiret.$R1_html_bold.$R2_html_bold.$R3_html_bold."<span class=\"suffixe\">mt</span><br />";
$ils_inacc = $prefixe.$tiret."<span class=\"pref\">tte</span>".$tiret.$R1_html_bold.$R2_html_bold.$R3_html_bold."<span class=\"suffixe\">n</span><br />";
$elles_inacc = $prefixe.$tiret."<span class=\"pref\">tte</span>".$tiret.$R1_html_bold.$R2_html_bold.$R3_html_bold."<span class=\"suffixe\">nt</span><br />";
}
if ($f == "C-v1") {
$je_inacc = $prefixe.$tiret."<span class=\"pref\"></span>".$tiret.$R1_html_bold.$R4_html_bold.$R3_html_bold."<span class=\"suffixe\">ɣ</span><br />";
$tum_inacc = $prefixe.$tiret."<span class=\"pref\">tte</span>".$tiret.$R1_html_bold.$R4_html_bold.$R3_html_bold."<span class=\"suffixe\">d</span><br />";
$tuf_inacc = $prefixe.$tiret."<span class=\"pref\">tte</span>".$tiret.$R1_html_bold.$R4_html_bold.$R3_html_bold."<span class=\"suffixe\">d</span><br />";
$il_inacc = $prefixe.$tiret."<span class=\"pref\">ite</span>".$tiret.$R1_html_bold.$R4_html_bold.$R3_html_bold."<span class=\"suffixe\"></span><br />";
$elle_inacc = $prefixe.$tiret."<span class=\"pref\">tte</span>".$tiret.$R1_html_bold.$R4_html_bold.$R3_html_bold."<span class=\"suffixe\"></span><br />";
$nous_inacc = $prefixe.$tiret."<span class=\"pref\">nte</span>".$tiret.$R1_html_bold.$R4_html_bold.$R3_html_bold."<span class=\"suffixe\"></span><br />";
$vousm_inacc = $prefixe.$tiret."<span class=\"pref\">tte</span>".$tiret.$R1_html_bold.$R4_html_bold.$R3_html_bold."<span class=\"suffixe\">m</span><br />";
$vousf_inacc = $prefixe.$tiret."<span class=\"pref\">tte</span>".$tiret.$R1_html_bold.$R4_html_bold.$R3_html_bold."<span class=\"suffixe\">mt</span><br />";
$ils_inacc = $prefixe.$tiret."<span class=\"pref\">tte</span>".$tiret.$R1_html_bold.$R4_html_bold.$R3_html_bold."<span class=\"suffixe\">n</span><br />";
$elles_inacc = $prefixe.$tiret."<span class=\"pref\">tte</span>".$tiret.$R1_html_bold.$R4_html_bold.$R3_html_bold."<span class=\"suffixe\">nt</span><br />";
}
/*Aoriste = Futur*/
if ($f == "C-v" || $f=="C-v1") {
$je_aor = $prefixe.$tiret."<span class=\"pref\">ad e</span>".$tiret.$R1_html_bold.$R2_html_bold.$R3_html_bold."<span class=\"suffixe\">ɣ</span><br />";
$tum_aor = $prefixe.$tiret."<span class=\"pref\">ad te</span>".$tiret.$R1_html_bold.$R2_html_bold.$R3_html_bold."<span class=\"suffixe\">d</span><br />";
$tuf_aor = $prefixe.$tiret."<span class=\"pref\">ad te</span>".$tiret.$R1_html_bold.$R2_html_bold.$R3_html_bold."<span class=\"suffixe\">d</span><br />";
$il_aor =  $prefixe.$tiret."<span class=\"pref\">ad ye</span>".$tiret.$R1_html_bold.$R2_html_bold.$R3_html_bold."<span class=\"suffixe\"></span><br />";
$elle_aor = $prefixe.$tiret."<span class=\"pref\">ad te</span>".$tiret.$R1_html_bold.$R2_html_bold.$R3_html_bold."<span class=\"suffixe\"></span><br />";
$nous_aor =  $prefixe.$tiret."<span class=\"pref\">ad ne</span>".$tiret.$R1_html_bold.$R2_html_bold.$R3_html_bold."<span class=\"suffixe\"></span><br />";
$vousm_aor =  $prefixe.$tiret."<span class=\"pref\">ad te</span>".$tiret.$R1_html_bold.$R2_html_bold.$R3_html_bold."<span class=\"suffixe\">m</span><br />";
$vousf_aor =  $prefixe.$tiret."<span class=\"pref\">ad te</span>".$tiret.$R1_html_bold.$R2_html_bold.$R3_html_bold."<span class=\"suffixe\">mt</span><br />";
$ils_aor = $prefixe.$tiret."<span class=\"pref\">ad e</span>".$tiret.$R1_html_bold.$R2_html_bold.$R3_html_bold."<span class=\"suffixe\">n</span><br />";
$elles_aor = $prefixe.$tiret."<span class=\"pref\">ad e</span>".$tiret.$R1_html_bold.$R2_html_bold.$R3_html_bold."<span class=\"suffixe\">nt</span><br />";
}

/* Les formes v-C */

/* Accompli = Passé*/
if ($f == "v-C1" || $f=="v-C3") {
$je_acc = $prefixe.$tiret."<span class=\"pref\"></span>".$tiret.$R1_html_bold.$R2_html_bold.$R3_html_bold."<span class=\"suffixe\">iɣ</span><br />";
$tum_acc = $prefixe.$tiret."<span class=\"pref\">t</span>".$tiret.$R1_html_bold.$R2_html_bold.$R3_html_bold."<span class=\"suffixe\">id</span><br />";
$tuf_acc = $prefixe.$tiret."<span class=\"pref\">t</span>".$tiret.$R1_html_bold.$R2_html_bold.$R3_html_bold."<span class=\"suffixe\">id</span><br />";
$il_acc =  $prefixe.$tiret."<span class=\"pref\">y</span>".$tiret.$R1_html_bold.$R2_html_bold.$R3_html_bold."<span class=\"suffixe\">a</span><br />";
$elle_acc = $prefixe.$tiret."<span class=\"pref\">t</span>".$tiret.$R1_html_bold.$R2_html_bold.$R3_html_bold."<span class=\"suffixe\">a</span><br />";
$nous_acc =  $prefixe.$tiret."<span class=\"pref\">n</span>".$tiret.$R1_html_bold.$R2_html_bold.$R3_html_bold."<span class=\"suffixe\">a</span><br />";
$vousm_acc =  $prefixe.$tiret."<span class=\"pref\">t</span>".$tiret.$R1_html_bold.$R2_html_bold.$R3_html_bold."<span class=\"suffixe\">im</span><br />";
$vousf_acc =  $prefixe.$tiret."<span class=\"pref\">t</span>".$tiret.$R1_html_bold.$R2_html_bold.$R3_html_bold."<span class=\"suffixe\">imt</span><br />";
$ils_acc = $prefixe.$tiret."<span class=\"pref\"></span>".$tiret.$R1_html_bold.$R2_html_bold.$R3_html_bold."<span class=\"suffixe\">in</span><br />";
$elles_acc = $prefixe.$tiret."<span class=\"pref\"></span>".$tiret.$R1_html_bold.$R2_html_bold.$R3_html_bold."<span class=\"suffixe\">int</span><br />";
}
if ($f == "v-C2") {
$je_acc = $prefixe.$tiret."<span class=\"pref\"></span>".$tiret.$R1_html_bold.$R2_html_bold.$R3_html_bold."<span class=\"suffixe\">eɣ</span><br />";
$tum_acc = $prefixe.$tiret."<span class=\"pref\">t</span>".$tiret.$R1_html_bold.$R2_html_bold.$R3_html_bold."<span class=\"suffixe\">ed</span><br />";
$tuf_acc = $prefixe.$tiret."<span class=\"pref\">t</span>".$tiret.$R1_html_bold.$R2_html_bold.$R3_html_bold."<span class=\"suffixe\">ed</span><br />";
$il_acc =  $prefixe.$tiret."<span class=\"pref\">y</span>".$tiret.$R1_html_bold.$R2_html_bold.$R3_html_bold."<br />";
$elle_acc = $prefixe.$tiret."<span class=\"pref\">t</span>".$tiret.$R1_html_bold.$R2_html_bold.$R3_html_bold."<br />";
$nous_acc =  $prefixe.$tiret."<span class=\"pref\">n</span>".$tiret.$R1_html_bold.$R2_html_bold.$R3_html_bold."<br />";
$vousm_acc =  $prefixe.$tiret."<span class=\"pref\">t</span>".$tiret.$R1_html_bold.$R2_html_bold.$R3_html_bold."<span class=\"suffixe\">em</span><br />";
$vousf_acc =  $prefixe.$tiret."<span class=\"pref\">t</span>".$tiret.$R1_html_bold.$R2_html_bold.$R3_html_bold."<span class=\"suffixe\">emt</span><br />";
$ils_acc = $prefixe.$tiret."<span class=\"pref\"></span>".$tiret.$R1_html_bold.$R2_html_bold.$R3_html_bold."<span class=\"suffixe\">en</span><br />";
$elles_acc = $prefixe.$tiret."<span class=\"pref\"></span>".$tiret.$R1_html_bold.$R2_html_bold.$R3_html_bold."<span class=\"suffixe\">ent</span><br />";
}
/* Inaccompli = Présent*/
if ($f == "v-C1") {
$je_inacc = $prefixe.$tiret."<span class=\"pref\">tt</span>".$tiret.$R1_html_bold.$R2_html_bold.$R3_html_bold."<span class=\"suffixe\">iɣ</span><br />";
$tum_inacc = $prefixe.$tiret."<span class=\"pref\">tt</span>".$tiret.$R1_html_bold.$R2_html_bold.$R3_html_bold."<span class=\"suffixe\">id</span><br />";
$tuf_inacc = $prefixe.$tiret."<span class=\"pref\">tt</span>".$tiret.$R1_html_bold.$R2_html_bold.$R3_html_bold."<span class=\"suffixe\">id</span><br />";
$il_inacc = $prefixe.$tiret."<span class=\"pref\">it</span>".$tiret.$R1_html_bold.$R2_html_bold.$R3_html_bold."<span class=\"suffixe\">a</span><br />";
$elle_inacc = $prefixe.$tiret."<span class=\"pref\">tt</span>".$tiret.$R1_html_bold.$R2_html_bold.$R3_html_bold."<span class=\"suffixe\">a</span><br />";
$nous_inacc = $prefixe.$tiret."<span class=\"pref\">nt</span>".$tiret.$R1_html_bold.$R2_html_bold.$R3_html_bold."<span class=\"suffixe\">a</span><br />";
$vousm_inacc = $prefixe.$tiret."<span class=\"pref\">tt</span>".$tiret.$R1_html_bold.$R2_html_bold.$R3_html_bold."<span class=\"suffixe\">am</span><br />";
$vousf_inacc = $prefixe.$tiret."<span class=\"pref\">tt</span>".$tiret.$R1_html_bold.$R2_html_bold.$R3_html_bold."<span class=\"suffixe\">amt</span><br />";
$ils_inacc = $prefixe.$tiret."<span class=\"pref\">tt</span>".$tiret.$R1_html_bold.$R2_html_bold.$R3_html_bold."<span class=\"suffixe\">an</span><br />";
$elles_inacc = $prefixe.$tiret."<span class=\"pref\">tt</span>".$tiret.$R1_html_bold.$R2_html_bold.$R3_html_bold."<span class=\"suffixe\">ant</span><br />";
}
if ($f == "v-C2") {
$je_inacc = $prefixe.$tiret."<span class=\"pref\">tt</span>".$tiret.$R1_html_bold.$R2_html_bold.$R3_html_bold."<span class=\"suffixe\">eɣ</span><br />";
$tum_inacc = $prefixe.$tiret."<span class=\"pref\">tt</span>".$tiret.$R1_html_bold.$R2_html_bold.$R3_html_bold."<span class=\"suffixe\">ed</span><br />";
$tuf_inacc = $prefixe.$tiret."<span class=\"pref\">tt</span>".$tiret.$R1_html_bold.$R2_html_bold.$R3_html_bold."<span class=\"suffixe\">ed</span><br />";
$il_inacc = $prefixe.$tiret."<span class=\"pref\">it</span>".$tiret.$R1_html_bold.$R2_html_bold.$R3_html_bold."<br />";
$elle_inacc = $prefixe.$tiret."<span class=\"pref\">tt</span>".$tiret.$R1_html_bold.$R2_html_bold.$R3_html_bold."<br />";
$nous_inacc = $prefixe.$tiret."<span class=\"pref\">nt</span>".$tiret.$R1_html_bold.$R2_html_bold.$R3_html_bold."<br />";
$vousm_inacc = $prefixe.$tiret."<span class=\"pref\">tt</span>".$tiret.$R1_html_bold.$R2_html_bold.$R3_html_bold."<span class=\"suffixe\">um</span><br />";
$vousf_inacc = $prefixe.$tiret."<span class=\"pref\">tt</span>".$tiret.$R1_html_bold.$R2_html_bold.$R3_html_bold."<span class=\"suffixe\">umt</span><br />";
$ils_inacc = $prefixe.$tiret."<span class=\"pref\">tt</span>".$tiret.$R1_html_bold.$R2_html_bold.$R3_html_bold."<span class=\"suffixe\">un</span><br />";
$elles_inacc = $prefixe.$tiret."<span class=\"pref\">tt</span>".$tiret.$R1_html_bold.$R2_html_bold.$R3_html_bold."<span class=\"suffixe\">unt</span><br />";
}
/*Aoriste = Futur*/
if ($f == "v-C1" || $f=="v-C2") {
$je_aor = $prefixe.$tiret."<span class=\"pref\">ad </span>".$tiret.$R1_html_bold.$R2_html_bold.$R3_html_bold."<span class=\"suffixe\">eɣ</span><br />";
$tum_aor = $prefixe.$tiret."<span class=\"pref\">ad t</span>".$tiret.$R1_html_bold.$R2_html_bold.$R3_html_bold."<span class=\"suffixe\">ed</span><br />";
$tuf_aor = $prefixe.$tiret."<span class=\"pref\">ad t</span>".$tiret.$R1_html_bold.$R2_html_bold.$R3_html_bold."<span class=\"suffixe\">ed</span><br />";
$il_aor =  $prefixe.$tiret."<span class=\"pref\">ad y</span>".$tiret.$R1_html_bold.$R2_html_bold.$R3_html_bold."<br />";
$elle_aor = $prefixe.$tiret."<span class=\"pref\">ad t</span>".$tiret.$R1_html_bold.$R2_html_bold.$R3_html_bold."<br />";
$nous_aor =  $prefixe.$tiret."<span class=\"pref\">ad n</span>".$tiret.$R1_html_bold.$R2_html_bold.$R3_html_bold."<br />";
$vousm_aor =  $prefixe.$tiret."<span class=\"pref\">ad t</span>".$tiret.$R1_html_bold.$R2_html_bold.$R3_html_bold."<span class=\"suffixe\">em</span><br />";
$vousf_aor =  $prefixe.$tiret."<span class=\"pref\">ad t</span>".$tiret.$R1_html_bold.$R2_html_bold.$R3_html_bold."<span class=\"suffixe\">emt</span><br />";
$ils_aor = $prefixe.$tiret."<span class=\"pref\">ad </span>".$tiret.$R1_html_bold.$R2_html_bold.$R3_html_bold."<span class=\"suffixe\">en</span><br />";
$elles_aor = $prefixe.$tiret."<span class=\"pref\">ad </span>".$tiret.$R1_html_bold.$R2_html_bold.$R3_html_bold."<span class=\"suffixe\">ent</span><br />";
}
	
	
?>


<div class="container">
	<!-- barre de navigation -->
	<br>
	
	<form class="container d-flex col-10" action="cherche.php" method="POST">
		  <input class="form-control mr-sm-1" type="search" placeholder="Chercher un verbe" aria-label="recherche" name="recherche">
		  <button class="btn btn-dark " type="submit"><svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
  <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
</svg></button>
		</form>
	<br>
	<?php
	if (empty($reponse)){
		$divresult = "";
	} else {
		$divresult = "<div class=\"alert alert-dark\">Résultats de votre recherche </div>";
		$nbReponses = count($reponse);		
		foreach ($reponse as $valeurs){
			$resultats .= "<li class=\"list-group-item resultats\"><i class=\"bi bi-file-earmark-text\"></i> <a href=\"conjug.php?trans=defaut&pref&terme=".$valeurs["verbeDAR"]."\">".$valeurs["verbeF"]." - ".$valeurs["verbeD"]." - ".$valeurs["verbeDAR"]."</a></li>\n";
		}
	}
	
	echo $divresult; ?>
	<ul class="list-group list-group-flush">  
</div>

<div class="container">
	<div class="alert alert-info">Conjugaison du verbe <span class="font-weight-bold"><?php echo $terme." - ".$t." - ".$tf. "</span>";?></span></div>
	<div class="collapse" id="explicationsForme">
		<div class="card card-body">
		<?php include $forme; ?>
		</div>
	</div>
	<div class="alert alert-warning"><span class="font-weight-bold">Inaccompli (présent)</span></div>
	<table class="table table-striped table-responsive-md">
		<tbody>
		<tr>
			<td class="col-2">nec</td>
			<td class="col-1"><?php echo $je_inacc ?></td>
		</tr>		
		<tr>
			<td class="col-2">cek</td>
			<td class="col-1"><?php echo $tum_inacc ?></td>
		</tr>
		<tr>
			<td class="col-2">cem</td>
			<td class="col-1"><?php echo $tuf_inacc ?></td>
		</tr>
		<tr>
			<td class="col-2">netta</td>
			<td class="col-1"><?php echo $il_inacc ?></td>
		</tr>
		<tr>
			<td class="col-2">nettaṭ</td>
			<td class="col-1"><?php echo $elle_inacc ?></td>
		</tr>
		<tr>
			<td class="col-2">neccin</td>
			<td class="col-1"><?php echo $nous_inacc ?></td>
		</tr>
		<tr>
			<td class="col-2">kenniw</td>
			<td class="col-1"><?php echo $vousm_inacc ?></td>
		</tr>
		<tr>
			<td class="col-2">kennint</td>
			<td class="col-1"><?php echo $vousf_inacc ?></td>
		</tr>
		<tr>
			<td class="col-2">niṭni</td>
			<td class="col-1"><?php echo $ils_inacc ?></td>
		</tr>
		<tr>
			<td class="col-2">niṭnint</td>
			<td class="col-1"><?php echo $elles_inacc ?></td>
		</tr>
		</tbody>
	</table>
</div>
<div class="container">
	<div class="alert alert-warning"><span class="font-weight-bold">Accompli (passé)</span></div>
	<table class="table table-striped table-responsive-md">
		<tbody>
		<tr>
			<td class="col-2">nec</td>
			<td class="col-1"><?php echo $je_acc ?></td>
		</tr>		
		<tr>
			<td class="col-2">cek</td>
			<td class="col-1"><?php echo $tum_acc ?></td>
		</tr>
		<tr>
			<td class="col-2">cem</td>
			<td class="col-1"><?php echo $tuf_acc ?></td>
		</tr>
		<tr>
			<td class="col-2">netta</td>
			<td class="col-1"><?php echo $il_acc ?></td>
		</tr>
		<tr>
			<td class="col-2">nettaṭ</td>
			<td class="col-1"><?php echo $elle_acc ?></td>
		</tr>
		<tr>
			<td class="col-2">neccin</td>
			<td class="col-1"><?php echo $nous_acc ?></td>
		</tr>
		<tr>
			<td class="col-2">kenniw</td>
			<td class="col-1"><?php echo $vousm_acc ?></td>
		</tr>
		<tr>
			<td class="col-2">kennint</td>
			<td class="col-1"><?php echo $vousf_acc ?></td>
		</tr>
		<tr>
			<td class="col-2">niṭni</td>
			<td class="col-1"><?php echo $ils_acc ?></td>
		</tr>
		<tr>
			<td class="col-2">niṭnint</td>
			<td class="col-1"><?php echo $elles_acc ?></td>
		</tr>
		</tbody>
	</table>	
</div>
<div class="container">
	<div class="alert alert-warning"><span class="font-weight-bold">Aoriste (futur)</span></div>
	<table class="table table-striped table-responsive-md">
		<tbody>
		<tr>
			<td class="col-2">nec</td>
			<td class="col-1"><?php echo $je_aor ?></td>
		</tr>		
		<tr>
			<td class="col-2">cek</td>
			<td class="col-1"><?php echo $tum_aor ?></td>
		</tr>
		<tr>
			<td class="col-2">cem</td>
			<td class="col-1"><?php echo $tuf_aor ?></td>
		</tr>
		<tr>
			<td class="col-2">netta</td>
			<td class="col-1"><?php echo $il_aor ?></td>
		</tr>
		<tr>
			<td class="col-2">nettaṭ</td>
			<td class="col-1"><?php echo $elle_aor ?></td>
		</tr>
		<tr>
			<td class="col-2">neccin</td>
			<td class="col-1"><?php echo $nous_aor ?></td>
		</tr>
		<tr>
			<td class="col-2">kenniw</td>
			<td class="col-1"><?php echo $vousm_aor ?></td>
		</tr>
		<tr>
			<td class="col-2">kennint</td>
			<td class="col-1"><?php echo $vousf_aor ?></td>
		</tr>
		<tr>
			<td class="col-2">niṭni</td>
			<td class="col-1"><?php echo $ils_aor ?></td>
		</tr>
		<tr>
			<td class="col-2">niṭnint</td>
			<td class="col-1"><?php echo $elles_aor ?></td>
		</tr>
		</tbody>
	</table>	
</div>
<?php include "include/footer.html"; ?>
<!-- Optional JavaScript; choose one of the two! -->
    <script src="bootstrap-4.6.0-dist/js/jquery-3.6.0.min.js"></script>
    <script src="bootstrap-4.6.0-dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>