<?php

// Inclusion des librairies
require_once('./smarty/Smarty.class.php');
require_once('./include/MySQL.php');
require_once('./include/chrono.php');
require_once('./include/Categorie.php');
session_start();

if (!isset($_SESSION['id'])) {
  include('error.php');
  die();
}



// Instanciation d'un l'objet Smarty
$oSmarty = new Smarty();

// MySQL
$mysql = new MySQL('localhost','root','Kamikas1','compte');

// Temps pour générer la page
$chrono = new chrono();
$chrono->start();

// Categories
$objectCat = $_SESSION['cat'];
$tabCat = $objectCat->getInfos();
$oSmarty->assign('CAT_NAMES', $tabCat['name']);
$oSmarty->assign('CAT_IDS', $tabCat['id']);



/*
 *****************************************************************************
 * Ajout d'une transaction
 ******************************************************************************
 */
if (isset($_POST['somme']) && $_POST['somme']!=0) {
	extract($_POST);
    $oSmarty->assign("DEBUG",$_POST);
	// gestion de la date
	$date = explode('/',$MyDate);
	$jour = $date[0];
	$mois = $date[1];
	$an = $date[2];
	$date = mktime(0,0,0,$mois,$jour,$an);

	
	if($type == 'debit') {
        $somme = $somme*(-1);
    }
	$pointage = isset($pointage)?1:0;

    if(!$modif) {
	   // Ajout d'une nouvelle transaction
	   $query = "INSERT INTO transactions
				VALUES ('','$date', '$id_cat', '$description', '$somme', '$pointage','')";
	} else {
        // modification d'une transaction existante
        $query = "UPDATE transactions
  				SET date='$date',
  				categorie='$id_cat',
  				description='$description',
  				valeur='$somme',
  				pointage='$pointage'
  				WHERE id='$modif'";
    }
	$result = $mysql->query($query);
	printf($query.'<br>');
}



/*
 *****************************************************************************
 * Mofification d'un transaction
 ******************************************************************************
 */
 
 // initialisation du formulaire
$hist['date'] = 'Aucune';
if(isset($MyDate)) {
    $hist['date'] = $MyDate;
}
$hist['cat'] = '';
$hist['description'] = '';
$hist['valeur'] = '0';
$hist['debit'] = '';
$hist['credit'] = '';
$hist['checked'] = '';
$hist['modif'] = 0;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $query = "SELECT * FROM transactions where id=$id";
    $result = $mysql->query($query);
    $line = mysql_fetch_assoc($result);
    extract($line);
    $hist['date'] = date('d/m/Y',$date);
    $hist['cat'] = $categorie;
    $hist['description'] = $description;
    if($valeur < 0) {
        $valeur *= (-1);
        $hist['debit'] = 'checked';
    } else {
        $hist['credit'] = 'checked';
    }
    $hist['valeur'] = $valeur;
    if($pointage) {
        $hist['checked'] = 'checked';
    }
    $hist['modif'] = $id;
}
$oSmarty->assign('HIST', $hist);


//$tabCatIds = $objectCat->getIds();
/*
// tableau des anciennes valeurs du formulaire
$hist['date']='';
$hist['id_cat']='';
$hist['id_sous_cat']='';
$hist['type']='';
$hist['description']='';
$hist['somme']='';
$cat_selected='';
$type_selected='';
$sous_cat_noms[] =  '--Sous-Categorie--';
$sous_cat_id[] =  0;
$sous_cat_selected='';
$trans = 0;






// Ajout d'une nouvelle transaction
if (isset($_POST['somme']) && $_POST['somme']!=0) {

	extract($_POST);
	
	// gestion de la date
	$date = explode('-',$date);
	$jour = $date[0];
	$mois = $date[1];
	$an = $date[2];
	$date = mktime(0,0,0,$mois,$jour,$an);
	
	$pointage = isset($pointage)?1:0;

	// Ajout d'une nouvelle transaction
	if($trans==0) {
		$query = "INSERT INTO transactions
				VALUES ('','$date', '$id_cat', '$id_sous_cat', '$type', '$description', '$somme', '$pointage')";
		$result = $mysql->query($query);
    // Mise a jour des soldes
    if($categories["$id_cat"]['type']=='debit') {
        $somme = -$somme;
	}
    $solde += $somme;

    if($pointage) {
      $solde_p += $somme;
    }
    $query = "UPDATE soldes SET solde = $solde WHERE id = 0";
    $result = $mysql->query($query);

   // Modification d'une transaction
   } else {

		// Annulation de l'opération
        $query = "SELECT * FROM transactions WHERE id='$trans'";
        $result = $mysql->query($query);
        $line = mysql_fetch_assoc($result);

		// MAJ des soldes
		if($categories[$line['id_cat']]['type']=='debit') {
            $line['somme'] = -$line['somme'];
		}
       	$solde -=  $line['somme'];
       	if($line['pointage']) {
			$solde_p -=  $line['somme'];
		}

        // Ajout de la transaction
        $query = "UPDATE transactions
  				SET date='$date',
  				id_cat='$id_cat',
  				id_sous_cat='$id_sous_cat',
  				type='$type',
  				description='$description',
  				somme='$somme',
  				pointage='$pointage'
  				WHERE id='$trans'";
				$result = $mysql->query($query);
				
		// Mise a jour des soldes
		if($categories[$line['id_cat']]['type']=='debit') {
        	$somme = -$somme;
		}
		$solde += $somme;
		
        if($pointage)
			 $solde_p += $somme;

        $query = "UPDATE soldes SET solde = $solde, solde_p = $solde_p";
        $result = $mysql->query($query);
   }
} else if(isset($_POST['id_cat']) && $_POST['id_cat']!=0){
	// Choix de la sous-categorie
	extract($_POST);
	$cat_selected=$id_cat;
	$query = "SELECT * from sous_categories WHERE id_cat=$id_cat";
	$result = $mysql->query($query);
	while ($line = mysql_fetch_assoc($result)) {
	 	extract($line);
		$sous_cat_noms[] = $nom;
		$sous_cat_id[] = $id;
	}
	// on garde les valeurs des différents champs
	$hist = $_POST;
}





if (isset($_GET['edit'])) {
	extract($_GET);
	// Exécuter des requêtes SQL
	$query = "SELECT * FROM transactions WHERE id=$edit";

	$result = $mysql->query($query);
	$line = mysql_fetch_array($result);
	$line['date']=date('d-m-Y',$line['date']);
	$cat_selected = $line['id_cat'];
	$type_selected = $line['type'];
	$checked = $line['pointage']?'checked':'';
	$trans = $edit;
	$hist = $line;
	$id_cat = $line['id_cat'];
	// gestion des sous-catégories
	$sous_cat_selected = $line['id_sous_cat'];
	$query = "SELECT * FROM sous_categories WHERE id_cat=$id_cat";
	$result = $mysql->query($query);
	while ($line = mysql_fetch_assoc($result)) {
	 	extract($line);
		$sous_cat_noms[] = $nom;
		$sous_cat_id[] = $id;
	}
}



if(!isset($checked)) {
	$checked='';
}

$oSmarty->assign('values', $hist);
$oSmarty->assign('TRANS', $trans);
$oSmarty->assign('CHECKED', $checked);
$oSmarty->assign('cat_noms', $cat_noms);
$oSmarty->assign('cat_index', $cat_id);
$oSmarty->assign('cat_selected', $cat_selected);
$oSmarty->assign('sous_cat_noms', $sous_cat_noms);
$oSmarty->assign('sous_cat_index', $sous_cat_id);
$oSmarty->assign('sous_cat_selected', $sous_cat_selected);

// type
$query = "SELECT * FROM types";
$result = $mysql->query($query);
while ($line = mysql_fetch_assoc($result)) {
 	extract($line);
	$type_noms[] = $name;
	$type_index[] = $index;
}
$oSmarty->assign('type_noms', $type_noms);
$oSmarty->assign('type_index', $type_index);
$oSmarty->assign('type_selected', $type_selected);
*/

// stats
$chrono->stop();
$oSmarty->assign("TIME",$chrono->getTime());
$oSmarty->assign("REQUESTS",$mysql->nbRequest);

// Affichage du template après compilation
$oSmarty->debugging = false;
$oSmarty->display('edit.html');
?>
