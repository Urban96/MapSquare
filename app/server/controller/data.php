<?php

require 'class/data_class.php';

//Database connection
$db = Database::getInstance();
$pdo = $db->getConnection();

//New Data
$data = new Data($pdo, $user_id);

if (isset($_POST['data_id'])){
    $data_id = $_POST['data_id'];
}

//Switch controller
switch ($action) {
  case 'data':
    echo $data->getAllData();
    break;

  case 'dataView':
    echo $data->viewData($data_id);
    break;

  case 'dataUpload':
    require 'dataUpload.php';
    break;

  case 'dataDelete':
    echo $data->deleteData($data_id);
    break;

  case 'dataExport':
    echo $data->exportData($data_id);
    break;

  default:
    break;
}

?>
