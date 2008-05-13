<?php

session_start();

if (!isset($_SESSION['id'])) {
  include('error.php');
  die();
}


// Inclusion des librairies
require_once('./smarty/Smarty.class.php');
require_once('./include/MySQL.php');
require_once('./include/chrono.php');
require_once('./include/graph_line.php');
require_once('./include/graph_3D_pie.php');
require_once('./include/graph_bar.php');

function getDaysInMonth($month=null,$year=null) {
  if ($month==null) {
       $month = date("n",time());
  }
   if ($year=null) {
       $year = date("Y",time());
   }
   $dim = date( "j", mktime(0, 0, 0, $month + 1, 1, $year) - 1 );
  return $dim;
}


// Temps pour générer la page
$chrono = new chrono();
$chrono->start();

// MySQL
$mysql = new MySQL('db735.1and1.fr','dbo185303395','pUBWrNeN','db185303395');

// Smarty
$oSmarty = new Smarty();



// Liste des catégories
$query = "SELECT * FROM categories";
$result = $mysql->query($query);
while ($line = mysql_fetch_assoc($result)) {
	extract($line);
	$categories["$id"]['name'] = $name;
	$categories["$id"]['type'] = $type;
}

// Liste des sous-catégories
$query = "SELECT * FROM sous_categories";
$result = $mysql->query($query);
while ($line = mysql_fetch_assoc($result)) {
	extract($line);
	$sous_cat["$id"]['name'] = $nom;
	$sous_cat["$id"]['id_cat'] = $id_cat;
}

/* Boucle sur les mois */
for($i=1;$i<=12;$i++) {

	// Initialisation
	foreach($categories as $id => $value) {
	    if($value['type']=='debit') {
			$debit_mois[$id][$i] = 0;
			if (!isset($debit_an[$id])) $debit_an[$id]  = 0;
		} else {
            $credit_mois[$id][$i] = 0;
            if (!isset($credit_an[$id])) $credit_an[$id]  = 0;
		}
 	}

	$date_debut = mktime(0,0,0,"$i",'01','2007');
	$day = getDaysInMonth($i,2007);
	$date_fin = mktime(0,0,0,"$i","$day",'2007');
	
	$query = "SELECT * FROM transactions WHERE date >= $date_debut AND date <= $date_fin";
	$result = $mysql->query($query);
    while ($line = mysql_fetch_assoc($result)) {
        extract($line);
        if($categories[$id_cat]['type']=='debit') {
			$debit_mois[$id_cat][$i] += $somme;
			$debit_an[$id_cat] += $somme;
		} else {
            $credit_mois[$id_cat][$i] += $somme;
            $credit_an[$id_cat] += $somme;
		}
 	}
}


$oSmarty->assign("DEBIT",$debit_mois);
$oSmarty->assign("DEBIT_AN",$debit_an);

foreach($debit_an as $id => $value) {
	if($value!=0) {
		$cat_debit[$id]=$categories[$id]['name'];
	}
}
foreach($credit_an as $id => $value) {
	if($value!=0) {
		$cat_credit[$id]=$categories[$id]['name'];
	}
}

new graph_line('./courbes_an_debit.xml',$debit_mois,$cat_debit);
new graph_line('./courbes_an_credit.xml',$credit_mois,$cat_credit);





// Liste des catégories a afficher
//$oSmarty->assign('CATEGORIES', $cat_form);

// Generation du graphe
//new graph_line('./courbes.xml',$debit_mois);












// stats
$chrono->stop();
$oSmarty->assign("TIME",$chrono->getTime());
$oSmarty->assign("REQUESTS",$mysql->nbRequest);


// Affichage du template après compilation
$oSmarty->debugging = false;
$oSmarty->display('stats_an.html');


?>
