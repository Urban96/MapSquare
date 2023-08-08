<?php

// Register autoloader
require_once('assets/vendor/php-shapefile/src/Shapefile/ShapefileAutoloader.php');
Shapefile\ShapefileAutoloader::register();

// Import classes
use Shapefile\Shapefile;
use Shapefile\ShapefileException;
use Shapefile\ShapefileReader;

$target_dir = "tmp/";
$file = $_FILES['file']['name'];
$path = pathinfo($file);
$filename = $path['filename'];
$ext = $path['extension'];
$tmp_name = $_FILES['file']['tmp_name'];
$path_filename_ext = $target_dir.$filename.".".$ext;
$user_id = $_SESSION['user_id'];

move_uploaded_file($tmp_name,$path_filename_ext);

//File extension switch
switch ($ext) {
  case 'json':
  case 'geojson':
    $response = uploadGeoJSON($path_filename_ext, $target_dir, $filename);
    break;
  case 'zip':
    $response = uploadShapeFile($path_filename_ext, $target_dir, $filename);
    break;
  default:
    $response = json_encode(['status'=> "ERROR", 'message'=>"Unsupported file format."]);
    break;
}
echo $response;


function uploadGeoJSON($path_filename_ext, $target_dir, $filename) {

  global $pdo;
  global $user_id;

  $status = "ERROR";
  $message = "Couldn't upload the file.";

  $json = file_get_contents($path_filename_ext);
  $json = json_decode($json,true);

  if (isset($json['features'])) {
    $json = $json['features'];
  } else {
    $status = "ERROR";
    $message = "Invalid GeoJSON file - invalid structure near features.";
    return json_encode(['status'=> $status, 'message'=> $message]);
  }

  if (count($json) == 0) {
    $message = "Invalid GeoJSON file - missing features.";
    return json_encode(['status'=> $status, 'message'=> $message]);
  }


  $filename = $filename . ".geojson";
  $data_type = "GeoJSON";
  $feature_type = "";

  while(true){
    $data_id = generateRandomString();
    $sql_data = "SELECT * FROM data WHERE data_id = :data_id";
    $stmt_data = $pdo->prepare($sql_data);
    $stmt_data->bindParam(':data_id', $data_id);
    $stmt_data->execute();
    $row_data = $results_data = $stmt_data->fetch(PDO::FETCH_ASSOC);

    if(!$row_data) {
      break;
    }
  }

  //Check if all features are same type
  $i = 1;
  foreach ($json as $feature){
    if ($i == 1) {
      $feature_type = $feature['geometry']['type'];
      $i++;
      continue;
    }
    if (($feature_type != $feature['geometry']['type']) && ("Multi" . $feature_type != $feature['geometry']['type'])) {
      $message = "Invalid GeoJSON file - all features must be same type.";
      return json_encode(['status'=> $status, 'message'=> $message]);
    }
    $i++;
  }


  $i = 1;

  foreach ($json as $feature){
    if ($i == 1) {
      $feature_type = $feature['geometry']['type'];
    }

    $geom = json_encode($feature['geometry']);
    $feature['properties']['ms_fid'] = $i;
    $data = json_encode($feature['properties']);

    $sql = $pdo->prepare("INSERT INTO data_feature (data_id, geometry, properties) VALUES (?, ?, ?)");
    $sql->bindParam(1, $data_id);
    $sql->bindParam(2, $geom);
    $sql->bindParam(3, $data);
    $sql->execute();

    $i++;
  }

  if ($feature_type == "LineString") {
    $feature_type = "PolyLine";
  }

  $sql = $pdo->prepare("INSERT INTO data (data_id, name, data_type, feature_type, user_id, cdate) VALUES (?, ?, ?, ?, ?, NOW())");
  $sql->bindParam(1, $data_id);
  $sql->bindParam(2, $filename);
  $sql->bindParam(3, $data_type);
  $sql->bindParam(4, $feature_type);
  $sql->bindParam(5, $user_id);
  $sql->execute();

  $status = "OK";
  $message = "Succesfully uploaded.";


  $files = glob($target_dir.$filename.'*');
  foreach($files as $file){
    if(is_file($file)) {
      unlink($file);
    }
  }

  return json_encode(['status'=> $status, 'message'=> $message]);
}


function uploadShapefile($path_filename_ext, $target_dir, $filename) {

  global $pdo;
  global $user_id;

  $status = "ERROR";
  $message = "Couldn't upload the file.";

  unzip($path_filename_ext);

  try {
    $Shapefile = new ShapefileReader($target_dir.$filename.".shp");

    //Check if coords are saved in WGS84
    $projection = $Shapefile->getPRJ();
    if (!str_contains($projection, 'WGS')) {
      $message = "Coordinates must be saved in WGS 1984.";
      return json_encode(['status'=> $status, 'message'=> $message]);
      throw new Exception();
    }

    $filename = $filename . ".shp";
    $data_type = "Shapefile";

    while(true){
      $data_id = generateRandomString();
      $sql_data = "SELECT * FROM data WHERE data_id = :data_id";
      $stmt_data = $pdo->prepare($sql_data);
      $stmt_data->bindParam(':data_id', $data_id);
      $stmt_data->execute();
      $row_data = $results_data = $stmt_data->fetch(PDO::FETCH_ASSOC);

      if(!$row_data) {
        break;
      }
    }

    $i = 1;
    $feature_type = $Shapefile->getShapeType(Shapefile::FORMAT_STR);

    while ($Geometry = $Shapefile->fetchRecord()) {
      // Skip the record if marked as "deleted"
      if ($Geometry->isDeleted()) {
        continue;
      }

      $geom = $Geometry->getGeoJSON(false, false);


      $data = $Geometry->getDataArray();
      $data['ms_fid'] = $i;
      $data = json_encode($data);

      $sql = $pdo->prepare("INSERT INTO data_feature (data_id, geometry, properties) VALUES (?, ?, ?)");
      $sql->bindParam(1, $data_id);
      $sql->bindParam(2, $geom);
      $sql->bindParam(3, $data);
      $sql->execute();

      $i++;

    }

    $sql = $pdo->prepare("INSERT INTO data (data_id, name, data_type, feature_type, user_id, cdate) VALUES (?, ?, ?, ?, ?, NOW())");
    $sql->bindParam(1, $data_id);
    $sql->bindParam(2, $filename);
    $sql->bindParam(3, $data_type);
    $sql->bindParam(4, $feature_type);
    $sql->bindParam(5, $user_id);
    $sql->execute();

    $status = "OK";
    $message = "Succesfully uploaded.";
  } catch (ShapefileException $e) {
    $status = "ERROR";
    $messsage = $e->getMessage() . " - " . $e->getDetails();
  }

  $files = glob($target_dir.$filename.'*');
  foreach($files as $file){
    if(is_file($file)) {
      unlink($file);
    }
  }
  return json_encode(['status'=> $status, 'message'=> $message]);
}

?>
