<?php

class compte2 {
	var $solde;
	var $solde_init;
	var $solde_p;
	var $mysql;


/* Constructor	*/
function compte2($bd) {
  $this->mysql = $bd;
	$query = "SELECT * FROM soldes";
	$result = $bd->query($query);
	$line = mysql_fetch_assoc($result);
	$this->solde = $line['solde'];
	$this->solde_init = $line['solde_init'];
	$this->solde_p = $line['solde_p'];
	print(sprintf("%.2f€",$this->solde));
}

function get_solde() {
    return sprintf("%.2f€",$this->solde);
}

function get_solde_p() {
    return sprintf("%.2f€",$this->solde_p);
}

function update_solde($valeur) {
	$this->solde += $valeur;
 }



}
?>
