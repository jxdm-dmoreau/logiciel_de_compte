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


Function remove_accents($string) {  
    return strtr($string,  "ÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöøÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ", "aaaaaaaaaaaaooooooooooooeeeeeeeecciiiiiiiiuuuuuuuuynn");  
} 


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

function max_tab($tab) {
    $max = -99999;
    foreach($tab as $value)
	$max = max($max, $value);
    return $max;    
}

// Temps pour générer la page
$chrono = new chrono();
$chrono->start();

// MySQL
$mysql = new MySQL('localhost','root','Kamikas1','compte');

// Smarty
$oSmarty = new Smarty();

// Variables de session
$oCat = $_SESSION['cat'];

/*********** Récupération des informations **********************/
if (isset($_GET['cat_id'])) {
    $cat_id = $_GET['cat_id'];
} else {
    $cat_id = 1;
}
$cat_name = $oCat->getName($cat_id);
if($cat_name == -1)
    die("Mauvaise catégorie $cat_id\n");

$cat_name = remove_accents($cat_name);
$catAllChildren = $oCat->getAllChildren($cat_id);

$catFirstChildren = $oCat->getFirstChildren($cat_id);
foreach ($catFirstChildren as $value) {
    $tabCat["$value"] = $oCat->getAllChildren($value);
}

// initialisation du tabeau pour le total de chaque année
foreach ($catFirstChildren as $value) {
        $name = $oCat->getName($value);
        $cat_fils_annee["$name"] = 0; // total pour l'année
    }

// Boucle sur les mois
$cat_fils_mois_max = 0;
for($i=1;$i<=12;$i++) {

    // Initialisation
    $date_debut = mktime(0,0,0,"$i",'01','2008');
    $day = getDaysInMonth($i,2008);
    $date_fin = mktime(0,0,0,"$i","$day",'2008');
    foreach ($catFirstChildren as $value) {
	$name = $oCat->getName($value);
	$cat_fils_mois["$name"][$i] = 0;
    }
    $cat_annee[$i] = 0;

    $query = "SELECT * FROM transactions WHERE
            date >= $date_debut AND
            date <= $date_fin";
    $result = $mysql->query($query);
    while ($line = mysql_fetch_assoc($result)) {
        extract($line);
        if($valeur > 0) {
            continue;
        }
        $valeur *= (-1);
        foreach ($catFirstChildren as $value) {
            if($categorie == $value || ($tabCat["$value"] != null && in_array($categorie,$tabCat["$value"]))) {
                $name = $oCat->getName($value);
                $cat_fils_mois["$name"][$i] += $valeur;
                $cat_fils_annee["$name"] += $valeur;
		$cat_annee[$i] += $valeur;
		$cat_fils_mois_max = max($cat_fils_mois_max, $cat_fils_mois["$name"][$i]);
            }
        }
    }
}

// on enlève les valeurs nulles
foreach($cat_fils_annee as $key => $value) {
    if ($value != 0)
	$tmp["$key"] = $value;
}
$cat_fils_annee = $tmp;
$debug = $cat_fils_annee;

include_once( 'ofc-library/open-flash-chart.php' );

/************************* Graph sur mois de chaque sous-catégorie *************/

$g = new graph();

// Spoon sales, March 2007
$g->title( $cat_name, '{font-size: 26px;}' );

foreach($cat_fils_mois as $key => $value) {
    $g->set_data( $value );
    $id = $oCat->getId($key);
    $color = $oCat->getColor($id);
    $g->line( 2, "$color", $key, 10 );

}
//$g->set_data( $cat_fils_mois['Alimentation'] );
//$g->set_data( $cat_fils_mois['Logement'] );

//$g->line( 2, '0x9933CC', 'Alimentation', 10 );
//$g->line( 2, '0x990000', 'Logement', 10 );
// label each point with its value
$g->set_x_labels( array('Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec' ) );

// set the Y max
$g->set_y_max( $cat_fils_mois_max );
// label every 20 (0,20,40,60)
#$g->y_label_steps( 10 );


$g->set_width( 650 );
$g->set_height( 400 );

$g->set_output_type('js');


$ofc=$g->render();

/****************** bar de la catégorie sur l'année */
$g = new graph();
$g->title( "$cat_name", '{font-size:20px; color: #bcd6ff; margin:10px;}' );
$color = $oCat->getColor($cat_id);
$bar = new bar_glass( 55, "$color", "$color" );
$bar->data = $cat_annee;   
$g->data_sets[] = $bar;

// label the X axis (10 labels for 10 bars):
$g->set_x_labels( array( 'January','February','March','April','May','June','July','August','September','October' ) );

// colour the chart to make it pretty:
$g->x_axis_colour( '#909090', '#D2D2FB' );
$g->y_axis_colour( '#909090', '#D2D2FB' );

$g->set_y_min( 0 );
$g->set_y_max( max_tab($cat_annee) );
$g->y_label_steps( 6 );
$g->set_y_legend( 'Open Flash Chart', 12, '#736AFF' );
$g->set_width( 650 );
$g->set_height( 400 );

$g->set_output_type('js');
$ofc3 = $g->render();
/***********************************/


/************* Camembert sur l'année de toutes les catégories ***************************/
$pie = new graph();

//
// PIE chart, 60% alpha
//
$pie->pie(60,'#505050','{font-size: 12px; color: #404040;');
//
// pass in two arrays, one of data, the other data labels
//
$label = array();
$data = array();
$color = array();
foreach ($cat_fils_annee as $key => $value) {
    $label[] = $key;
    $data[] = $value;
    $id = $oCat->getId($key);
    $color[] = $oCat->getColor($id);
    $val = $oCat->getId($key);
    if(sizeof($oCat->getAllChildren($val)) == 0) {
	$links[] = "javascript:alert('Cette categorie n a pas de sous-categorie')";
    } else {
	$links[] = "./statsCompte.php?cat_id=$val";
    }


}
$pie->pie_slice_colours( $color );
$oSmarty->assign("DEBUG",$debug);
$pie->pie_values( $data, $label, $links );
//
// Colours for each slice, in this case some of the colours
// will be re-used (3 colurs for 5 slices means the last two
// slices will have colours colour[0] and colour[1]):
//

$pie->set_tool_tip( '#val#%' );
$pie->set_width( 650 );
$pie->set_height( 400 );

$pie->title( 'Résumé sur l\'année', '{font-size:18px; color: #d01f3c}' );
$pie->set_output_type('js');
$ofc2 = $pie->render();


/*************************************/
$oSmarty->assign("OFC",$ofc);
$oSmarty->assign("OFC2",$ofc2);
$oSmarty->assign("OFC3",$ofc3);


// stats
$chrono->stop();
$oSmarty->assign("TIME",$chrono->getTime());
$oSmarty->assign("REQUESTS",$mysql->nbRequest);


// Affichage du template après compilation
$oSmarty->debugging = false;
$oSmarty->display('statsCompte.html');


?>
