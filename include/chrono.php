<?php

class chrono {
  var $begin;
  var $end;
  
  

function chrono() {
  $begin = 0;
  $end = 0;
}
  
function start() {
  list($usec, $sec) = explode(" ", microtime());
  $this->begin = (float)$usec + (float)$sec;
}
  
function stop() {
  list($usec, $sec) = explode(" ", microtime());
  $this->end = (float)$usec + (float)$sec;
}

function getTime() {
  return ($this->end-$this->begin);
}  
  
  
  
  
  
  
}
?>
