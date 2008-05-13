<?php    

session_start();


// Inclusion des librairies
require_once('./smarty/Smarty.class.php');
require_once('./include/MySQL.php');
require_once('./include/chrono.php');

// temps pour générer la page
$chrono = new chrono();
$chrono->start();


$oSmarty = new Smarty();
//$mysql = new MySQL('db735.1and1.fr','dbo185303395','pUBWrNeN','db185303395');
$mysql = new MySQL('localhost','root','Kamikas1','compte');
//smarty
$error = false;
$first = true;

// deja connecté
if (isset($_SESSION['id'])) {
  $first = false;
}

// formulaire de connexion
if (isset($_POST['login'])) {
  $first = false;
	extract($_POST);
	$pass = md5($pass);
	$query = sprintf("SELECT * FROM utilisateurs WHERE login='%s' AND pass='%s'",
  	mysql_real_escape_string($login),
  	mysql_real_escape_string($pass));
  $result = $mysql->query($query);
	$mysql->close();

	if (mysql_num_rows($result)==1) {
  		$row = mysql_fetch_assoc($result);
  		$_SESSION['id'] = $row['id'];
  		$_SESSION['name'] = $login;
  		$oSmarty->assign("NAME",$_SESSION['name']);
      $oSmarty->assign("ID",$_SESSION['id']);		
	} else {
	  $error = true;
	}
}

// deconnexion
if (isset($_GET['deco'])) {
  session_destroy();
  $first = true;
}

// stats
$chrono->stop();
$oSmarty->assign("TIME",$chrono->getTime());
$oSmarty->assign("REQUESTS",$mysql->nbRequest);


$oSmarty->assign("ERROR",$error);
$oSmarty->assign("FIRST",$first);
$oSmarty->debugging = false;
$oSmarty->display('connexion.html');
