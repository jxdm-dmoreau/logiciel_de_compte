<?php
class solde {
	var $solde;
	var $solde_p;


	/* Constructor	*/
	function solde() {
		$query = "SELECT * FROM soldes";
		$result = mysql_query($query);
		$line = mysql_fetch_assoc($result);
		$this->solde = $line['solde'];
		$this->solde_p = $line['solde_p'];
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
