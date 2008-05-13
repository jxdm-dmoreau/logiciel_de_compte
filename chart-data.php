
<?php



/*
$catAllChildren = $oCat->getAllChildren($catToDisplay);
/*
$catFirstChildren = $objectCat->getFirstChildren($catToDisplay);
foreach ($catFirstChildren as $value) {
    $tabCat["$value"] = $objectCat->getAllChildren($value);
}

// initialisation du tabeau pour le total de chaque année
foreach ($catFirstChildren as $value) {
        $name = $objectCat->getName($value);
        $total["$name"] = 0; // total pour l'année
    }

// Boucle sur les mois
for($i=1;$i<=12;$i++) {

	// Initialisation
	$date_debut = mktime(0,0,0,"$i",'01','2008');
	$day = getDaysInMonth($i,2007);
	$date_fin = mktime(0,0,0,"$i","$day",'2008');
	foreach ($catFirstChildren as $value) {
        $name = $objectCat->getName($value);
        $tab["$name"][$i] = 0;
    }

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
                $name = $objectCat->getName($value);
                $tab["$name"][$i] += $valeur;
                $total["$name"] += $valeur;
            }
        }
 	}
}


/******************************************************************************/
// generate some random data:
die();

srand((double)microtime()*1000000);

$max = 50;
$data = array();
for( $i=0; $i<12; $i++ )
{
      $data[] = rand(0,$max);
}

// use the chart class to build the chart:
include_once( 'ofc-library/open-flash-chart.php' );
$g = new graph();

// Spoon sales, March 2007
$g->title( 'Chaussures de Jie '. date("Y"), '{font-size: 26px;}' );

$g->set_data( $data );
// label each point with its value
$g->set_x_labels( array('Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec' ) );

// set the Y max
$g->set_y_max( 60 );
// label every 20 (0,20,40,60)
$g->y_label_steps( 6 );

// display the data
echo $g->render();
?>
