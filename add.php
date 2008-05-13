<?php

// Inclusion des librairies
require_once('./include/MySQL.php');
require_once('./include/chrono.php');
require_once('./include/Categorie.php');

session_start();


// Ajout d'une nouvelle transaction
if (isset($_POST['somme']) && $_POST['somme']!=0) {
    // MySQL
    $mysql = new MySQL('localhost','root','','compte');
    
	extract($_POST);

	// gestion de la date
	printf($MyDate);
	$date = explode('/',$MyDate);
	$jour = $date[0];
	$mois = $date[1];
	$an = $date[2];
	$date = mktime(0,0,0,$mois,$jour,$an);

	$pointage = isset($pointage)?1:0;

	// Ajout d'une nouvelle transaction
	$query = "INSERT INTO transactions
				VALUES ('','$date', '$id_cat', '$description', '$somme', '$pointage','')";
	$result = $mysql->query($query);
	

}

?>

<a href="javascript:window.opener.reload();">Retour</a>
