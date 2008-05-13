<?php

// Inclusion des librairies
require_once('./smarty/Smarty.class.php');
require_once('./include/MySQL.php');
require_once('./include/chrono.php');
require_once('./include/graph_line.php');
require_once('./include/graph_3D_pie.php');
require_once('./include/graph_bar.php');
require_once('./include/Categorie.php');

session_start();

if (!isset($_SESSION['id'])) {
  include('error.php');
  die();
}



// Temps pour générer la page
$chrono = new chrono();
$chrono->start();

$mysql = new MySQL('localhost','root','Kamikas1','compte'); //MySQL
$oSmarty = new Smarty(); //Smarty
$oCat = $_SESSION['cat'];



// Ajout d'une nouvelle categorie
if (isset($_POST['state']) && $_POST['state'] == 1 && isset($_POST['id_cat']) && isset($_POST['name_cat'])) {
  extract($_POST);
  $query = "INSERT INTO categories VALUES ('','$name_cat','$id_cat', '$color_cat')";
  $mysql->query($query);
  $oCat = new Categorie($mysql);
  $_SESSION['cat'] = $oCat;
  $debug = $query;
}


// Suppression d'une catégorie
if (isset($_POST['state']) && ($_POST['state'] == 3) && isset($_POST['id_cat'])) {
  extract($_POST);
  //$query = "DELETE FROM categories WHERE id='$id_cat'";
  $oCat->remove($id_cat, $mysql);
  $debug = "La catégorie $id_cat a été supprimée.";
  //$mysql->query($query);
  $oCat = new Categorie($mysql);
  $_SESSION['cat'] = $oCat;
}


// Modification d'une catégorie
if (isset($_POST['state']) && $_POST['state'] == 2) {
  extract($_POST);
  $query = "UPDATE categories SET name='$name_cat', color='$color_cat' WHERE id=$id_cat";
  $mysql->query($query);
  $oCat = new Categorie($mysql);
  $_SESSION['cat'] = $oCat;
}



$oSmarty->assign('TEST', $oCat->getTreeJs());

$oSmarty->assign('DEBUG', $debug);
$oSmarty->assign('POST', $_POST);



// stats
$chrono->stop();
$oSmarty->assign("TIME",$chrono->getTime());
$oSmarty->assign("REQUESTS",$mysql->nbRequest);


// Affichage du template après compilation
$oSmarty->debugging = false;
$oSmarty->display('admin.html');


?>
