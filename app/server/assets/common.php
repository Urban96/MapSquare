<?php

//Unzp file function
function unzip($file) {
  $zip = new ZipArchive;
  $res = $zip->open($file);
  if ($res === TRUE) {
    $zip->extractTo('tmp');
    $zip->close();
  } else {
    // ....
  }
}

//Generate random string
function generateRandomString($length = 32) {
  $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
  $charactersLength = strlen($characters);
  $randomString = '';
  for ($i = 0; $i < $length; $i++) {
    $randomString .= $characters[rand(0, $charactersLength - 1)];
  }
  return $randomString;
}

//Converts to czech format date
function czeDatum($datum) {
  if ($datum == NULL || $datum == "0000-00-00") {
    return "";
  } else {
    return date("d.m.Y", strtotime($datum));
  }
}

//Set default style of layer
function defaultStyle($type) {
  switch($type) {
    case "Point":
      return '{"type": "singleSymbol","style": {"radius": "8","fillColor": "#3374b5","color": "#ffffff","weight": "1","opacity": "1","fillOpacity": "0.8"}}';
      break;
    case "PolyLine":
      return '{"type": "singleSymbol","style": {"color": "#3374b5","weight": 2,"opacity": 1,"fillOpacity": 0.7}}';
      break;
    case "Polygon":
      return '{"type": "singleSymbol","style": {"fillColor": "#3374b5","weight": 2,"opacity": 1,"color": "white","dashArray": 3,"fillOpacity": 0.7}}';
      break;
  }
}

?>
