<?php 
function callCurl(){
  $ch = curl_init('https://your-domain.info/api');
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  $contents = curl_exec($ch);
  curl_close($ch);
  return $contents;
}
 
