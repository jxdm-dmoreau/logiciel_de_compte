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



// Liste de toutes les catégories existantes
$query = "SELECT * FROM categories";
$result = $mysql->query($query);
while ($line = mysql_fetch_assoc($result)) {
	extract($line);
	$categories["$id"] = $name;
}
$oSmarty->assign('CATEGORIES', $categories);



/* Courbes */
if(isset($_POST['courbe_annee'])) {
  foreach($categories as $id => $name) {
    if(isset($_POST["$id"])){
      $cat_select["$id"]= $name;
    }
  }
} else {
   $cat_select = $categories;
}

for($i=1;$i<=12;$i++) {
  $date_debut = mktime(0,0,0,"$i",'01','2007'); // Aout 2007
  $day = getDaysInMonth($i,2007);
  $date_fin = mktime(0,0,0,"$i","$day",'2007');

  foreach($cat_select as $id => $name) {
  	$query = "SELECT debit FROM transactions WHERE date >= $date_debut AND date <= $date_fin AND id_cat = $id";
	    $result = $mysql->query($query);
      while ($line = mysql_fetch_assoc($result)) {
        if(!isset($total[$id][$i])) {
	    $total[$id][$i] = 0;
  	  }
 	   	  extract($line);
 	      $total[$id][$i] += $debit;
	}
  	if(!isset($total[$id][$i])) {
            $total[$id][$i] = 0;
  	}
  }
}

// Genertion du graphe
new graph_line('./courbes.xml',$total,$cat_select);






if(!isset($_POST['month'])) {
  $date = date("d-m-Y");
  $date = explode('-',$date);
	$mois = $date[1];
	$annee = $date[2];
} else {
  extract($_POST);
}

$oSmarty->assign('DATE'," $mois - $annee");

  $date_debut = mktime(0,0,0,$mois,01,$annee);
  $mois_suivant = $mois + 1;
  $annee_suivante = $annee;
  if($mois_suivant == 13) {
    $mois_suivant = 1;
    $annee_suivante++;
  }
  $date_fin = mktime(0,0,0,$mois_suivant,01,$annee_suivante);


  // Liste des catégories
  $query = "SELECT * FROM categories";
  $result = $mysql->query($query);
  while ($line = mysql_fetch_assoc($result)) {
  	extract($line);
  	$categories["$id"] = $name;
  }


  $query = "SELECT debit,id_cat FROM transactions WHERE date >= $date_debut AND date <= $date_fin";
  $result = $mysql->query($query);
  while ($line = mysql_fetch_assoc($result)) {
   	extract($line);
   	if(!isset($somme["$id_cat"])) {
        $somme["$id_cat"] = 0;
	}
   	$somme["$id_cat"] += $debit;
  }

if(!isset($somme)) {
  $oSmarty->assign('GRAPH',false);
} else {
  $oSmarty->assign('GRAPH',true);
  new graph_bar('./barre.xml',$somme,$categories);
  new graph_3D_pie('./pie.xml',$somme,$categories);
}





// Affichage du fichier XML
/*
$oSmarty->assign('VOIR_XML', isset($_POST['voir_xml']));
if(isset($_POST['voir_xml'])) {
  $contents = highlight_file("./pie.xml",true);
  $oSmarty->assign('CONTENTS', $contents);
}
*/

//$oSmarty->assign('cat_index', $cat_index);
//$oSmarty->assign('cat_selected', $cat_selected);





// stats
$chrono->stop();
$oSmarty->assign("TIME",$chrono->getTime());
$oSmarty->assign("REQUESTS",$mysql->nbRequest);


// Affichage du template après compilation
$oSmarty->debugging = false;
$oSmarty->display('stats_mois.html');


?>
