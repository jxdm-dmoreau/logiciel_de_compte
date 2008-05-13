<?php

class graph_bar {

  

function graph_bar($file,$total,$categories) {
  $handle = fopen("$file", "w");
  fwrite($handle,"<chart>\n");
  fwrite($handle,"\t<chart_data>\n");
  fwrite($handle,"\t\t<row>\n");
  fwrite($handle,"\t\t\t<null/>\n");
  fwrite($handle,"\t\t\t<string></string>\n");
  fwrite($handle,"\t\t</row>\n");
  foreach($categories as $id => $name) {
    if(isset($total[$id]) && $total[$id]!=0) {   
      fwrite($handle,"\t\t<row>\n");   
      fwrite($handle,"\t\t\t<string>$name</string>\n");
      fwrite($handle,"\t\t\t<number>$total[$id]</number>\n");
      fwrite($handle,"\t\t</row>\n");
    }
  }
  fwrite($handle,"\t</chart_data>\n");
  fwrite($handle,"</chart>\n");
  fclose($handle);
}
  
 
  
  
  
  
  
  
}
?>
