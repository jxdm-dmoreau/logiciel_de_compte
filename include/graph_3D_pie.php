<?php

class graph_3D_Pie {

  

function graph_3D_Pie($file,$table) {
  $handle = fopen("$file", "w");
  fwrite($handle,"<chart>\n");
  fwrite($handle,"\t<chart_data>\n");
  fwrite($handle,"\t\t<row>\n");
  fwrite($handle,"\t\t\t<null/>\n");
  foreach($table as $key => $value ) {
    if($value != 0) {
      fwrite($handle,"\t\t\t<string>$key</string>\n");
    }
  }
  fwrite($handle,"\t\t</row>\n");
  fwrite($handle,"\t\t<row>\n");
  fwrite($handle,"\t\t\t<string></string>\n");
  foreach($table as $key => $value ) {
    if($value != 0) {
      fwrite($handle,"\t\t\t<number>$value</number>\n");
    }
  }
  fwrite($handle,"\t\t</row>\n");
  fwrite($handle,"\t</chart_data>\n");
  fwrite($handle,"\t<chart_type>3d pie</chart_type>\n");
  fwrite($handle,"\t<chart_value color='000000' alpha='65' font='arial' bold='true' size='10' position='inside' prefix='' suffix='' decimals='0' separator='' as_percentage='true' />\n");
fwrite($handle,"\t<draw>\n");
  fwrite($handle,"\t\t<text color='000000' alpha ='50' size='25' x='-50' y='0' width='500' height='50' h_align='center' v_align='middle'>$title</text>\n");
  fwrite($handle,"\t<\draw>\n");
  fwrite($handle,"\t<legend_label layout='horizontal' bullet='circle' font='arial' bold='true' size='12' color='ffffff' alpha='85' />\n");
  fwrite($handle,"\t<legend_rect x='0' y='45' width='50' height='210' margin='10' fill_color='ffffff' fill_alpha='10' line_color='000000' line_alpha='0' line_thickness='0' />\n");
  fwrite($handle,"</chart>\n");
  fclose($handle);
}
  
 
  
  
  
  
  
  
}
?>
