<?php

session_start();

if (isset($_SESSION['user_id'])){
    $user_id = $_SESSION['user_id'];
}

require 'assets/config.php';
require 'assets/common.php';
require 'assets/database_class.php';

if (DEBUG) {
  ini_set('display_errors', 'On');
}
else {
  ini_set('display_errors', 'Off');
}

$action = $_POST['act'];

//Switch router
switch ($action) {
  //AUTH
  case 'login':
    require 'auth/login.php';
    break;
  case 'register':
    require 'auth/register.php';
    break;
  case 'lostPassword':
    require 'auth/lostPassword.php';
    break;
  case 'recover':
    require 'auth/recover.php';
    break;

  //DATA
  case 'data':
  case 'dataView':
  case 'dataUpload':
  case 'dataDelete':
  case 'dataExport':
    require 'controller/data.php';
    break;

  // //LAYER
  case 'layers':
  case 'layerView':
  case 'layerAdd':
  case 'layerSymbology':
  case 'layerUpdate':
  case 'layerDelete':
  case 'layerExport':
  case 'layerGeomAdd':
  case 'layerGeomUpdate':
  case 'layerGeomDelete':
    require 'controller/layer.php';
    break;

  //MAPS
  case 'maps':
  case 'mapView':
  case 'mapAdd':
  case 'mapGet':
  case 'mapLayers':
  case 'mapInsertLayer':
  case 'mapUpdate':
  case 'mapLayerDelete':
    require 'controller/map.php';
    break;

  // //CONTACT
  case 'contact':
    require 'contact.php';
    break;

  //DEFAULT
  default:
    http_response_code(404);
    break;
}

?>
