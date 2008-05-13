<?php

function getMonth($i) {

    switch ($i) {
    case 1:
        return 'Jan';
    case 2:
        return 'Fev';
    case 3:
        return 'Mar';
    case 4:
        return 'Avr';
    case 5:
        return 'Mai';
    case 6:
        return 'Juin';
    case 7:
        return 'Juil';
    case 8:
        return 'Aout';
    case 9:
        return 'Sept';
    case 10:
        return 'Oct';
    case 11:
        return 'Nov';
    case 12:
        return 'Dec';
    };
}


class graph_line {

  
/*
 * graph_line
 * tab[Alimentation][1] -> 100€
 *                  [2] ->   0€
 *                  ...
 *                  [12] -> 0€
 *
 *
 */
 


function graph_line($file,$tab) {
    $handle = fopen("./$file", "w");
    fwrite($handle,"<chart>\n");
    
    /* ecriture des options */
    fwrite($handle,file_get_contents('./xml/options.xml'));

  fwrite($handle,"\t<chart_data>\n");
  fwrite($handle,"\t\t<row>\n");
  fwrite($handle,"\t\t\t<null/>\n");
  for ($i = 1; $i <= 12; $i++) {
    $month = getMonth($i);
    fwrite($handle,"\t\t\t<string>$month</string>\n");
  }
  fwrite($handle,"\t\t</row>\n");
  /* Boucle sur chaque catégorie */
    foreach($tab as $key => $value) {
        fwrite($handle,"\t\t<row>\n");
        fwrite($handle,"\t\t\t<string>$key</string>\n");
        /* Boucle sur chaque mois */
        foreach($value as $money) {
		  fwrite($handle,"\t\t\t<number>$money</number>\n");
        }
	   fwrite($handle,"\t\t</row>\n");
    }

  fwrite($handle,"\t</chart_data>\n");
  fwrite($handle,"\t<chart_type>Line</chart_type>\n");
  fwrite($handle,"</chart>\n");
  fclose($handle);
}
  
 
  
  
  
  
  
  
}
?>
