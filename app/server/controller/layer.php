<?php

require 'class/layer_class.php';

//Database connection
$db = Database::getInstance();
$pdo = $db->getConnection();

//New Layer
$layer = new Layer($pdo, $user_id);

if (isset($_POST['layer_id'])){
    $layer_id = $_POST['layer_id'];
}

//Switch controller
switch ($action) {
  case 'layers':
    echo $layer->getAllLayers();
    break;

  case 'layerView':
    echo $layer->viewLayer($layer_id);
    break;

  case 'layerAdd':
    $layer_type = $_POST['layer_type'];
    echo $layer->addLayer($layer_type);
    break;

  case 'layerDelete':
    echo $layer->deleteLayer($layer_id);
    break;

  case 'layerUpdate':
    $name = $_POST['name'];
    $description = $_POST['description'];
    echo $layer->updateLayer($layer_id, $name, $description);
    break;

  case 'layerExport':
    echo $layer->exportLayer($layer_id);
    break;

  case 'layerSymbology':
    $type = $_POST['type'];
    $style = $_POST['style'];
    $style = json_encode($style);

    echo $layer->updateSymbology($layer_id, $type, $style);
    break;

  case 'layerGeomAdd':
    $geometry = $_POST['geometry'];
    $properties = $_POST['properties'];
    $geometry = json_encode($geometry);
    $properties = json_encode($properties);

    echo $layer->addGeometry($layer_id, $geometry, $properties);
    break;

  case 'layerGeomUpdate':
    $ms_fid = $_POST['ms_fid'];
    $geometry = $_POST['geometry'];
    $geometry = json_encode($geometry);

    echo $layer->updateGeometry($layer_id, $ms_fid, $geometry);
    break;

  case 'layerGeomDelete':
    $ms_fid = $_POST['ms_fid'];
    $geometry = $_POST['geometry'];
    $geometry = json_encode($geometry);

    echo $layer->deleteGeometry($layer_id, $ms_fid, $geometry);
    break;

  default:
    break;
}

?>
