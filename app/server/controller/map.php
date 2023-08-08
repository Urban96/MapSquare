<?php

require 'class/map_class.php';

//Database connection
$db = Database::getInstance();
$pdo = $db->getConnection();

//New Map
$map = new Map($pdo, $user_id);

if (isset($_POST['id'])){
    $map_id = $_POST['id'];
}

//Switch controller
switch ($action) {
  case 'maps':
    echo $map->getAllMaps();
    break;

  case 'mapView':
    echo $map->viewMap($map_id);
    break;

  case 'mapAdd':
    $name = $_POST['name'];
    $description = $_POST['description'];
    echo $map->addMap($name, $description);
    break;

  case 'mapGet':
    echo $map->getMap($map_id);
    break;

  case 'mapLayers':
    echo $map->getMapLayers($map_id);
    break;

  case 'mapInsertLayer':
    $layer_id = $_POST['layer_id'];
    echo $map->addLayerToMap($map_id, $layer_id);
    break;

  case 'mapUpdate':
    $name = $_POST['name'];
    $template = $_POST['template'];
    $options = $_POST['options'];
    $options = json_encode($options);
    echo $map->updateMap($map_id, $name, $template, $options);
    break;

  case 'mapLayerDelete':
    $layer_id = $_POST['layer_id'];
    echo $map->removeLayerFromMap($map_id, $layer_id);
    break;

  default:
    break;
}

?>
