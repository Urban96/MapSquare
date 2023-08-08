<?php

class Layer {
    private $db;
    private $user_id;

    public function __construct($db, $user_id) {
        $this->db = $db;
        $this->user_id = $user_id;
    }

    //Get All Layers
    public function getAllLayers() {
      $records = $this->db->prepare('SELECT layer_id, name, description, feature_type, cdate FROM layer WHERE user_id = :user_id');
      $records->bindParam(':user_id', $this->user_id);
      $records->execute();
      $results = $records->fetchAll(PDO::FETCH_ASSOC);

      $response = json_encode(['status'=> "OK", 'message'=>"Data fetched.", 'data'=> $results]);
      return $response;
    }

    //View Layer
    public function viewLayer($layer_id) {
      $count_records = $this->db->prepare('SELECT COUNT(*) AS count FROM layer WHERE layer_id = :layer_id AND user_id = :user_id');
      $count_records->bindParam(':layer_id', $layer_id);
      $count_records->bindParam(':user_id', $this->user_id);
      $count_records->execute();
      $count = $count_records->fetch(PDO::FETCH_ASSOC);

      if ($count['count'] == 0) {
        $response = json_encode(['status'=> "ERROR", 'message'=>"No layer."]);
        echo $response;
        die();
      }

      $records = $this->db->prepare('SELECT name, description, feature_type, layer_id, style, cdate FROM layer WHERE layer_id = :layer_id');
      $records->bindParam(':layer_id', $layer_id);
      $records->execute();
      $results = $records->fetch(PDO::FETCH_ASSOC);

      $records1 = $this->db->prepare('SELECT COUNT(*) AS count FROM layer_feature WHERE layer_id = :layer_id');
      $records1->bindParam(':layer_id', $layer_id);
      $records1->execute();
      $count1 = $records1->fetch(PDO::FETCH_ASSOC);


      $records2 = $this->db->prepare('SELECT id, layer_id, geometry, properties FROM layer_feature WHERE layer_id = :layer_id');
      $records2->bindParam(':layer_id', $layer_id);
      $records2->execute();

      $json = array(
          'type' => 'FeatureCollection',
          'features' => array(
          )
      );

      while($row = $records2->fetch(PDO::FETCH_ASSOC))
      {
        $geom = json_decode($row['geometry']);
        $data = json_decode($row['properties']);

        $feature = array(
          'type' => 'Feature',
          'geometry' => $geom,
          'properties' => $data
        );

        array_push($json['features'],$feature);
      }

      $response = json_encode(['status'=> "OK", 'message'=>"Data fetched.", 'name'=>$results['name'], 'description'=>$results['description'], 'style'=>$results['style'], 'cdate'=>$results['cdate'], 'count'=>$count1['count'], 'type'=>$results['feature_type'], 'data'=>$json]);
      return $response;
    }

    //Add New Layer
    public function addLayer($layer_type) {
      while(true){
        $layer_id = generateRandomString();
        $sql_layer = "SELECT * FROM layer WHERE layer_id = :layer_id";
        $stmt_layer = $this->db->prepare($sql_layer);
        $stmt_layer->bindParam(':layer_id', $layer_id);
        $stmt_layer->execute();
        $row_layer = $results_layer = $stmt_layer->fetch(PDO::FETCH_ASSOC);

        if(!$row_layer) {
          break;
        }
      }

      if ($layer_type == "new") {

        $name = $_POST['name'];
        $description = $_POST['description'];
        $feature_type = $_POST['type'];

        $style = defaultStyle($feature_type);

        $sql = "INSERT INTO layer (layer_id, name, description, feature_type, style, user_id, cdate) VALUES (:layer_id, :name, :description, :feature_type, :style, :user_id, NOW())";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':layer_id', $layer_id);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':feature_type', $feature_type);
        $stmt->bindParam(':style', $style);
        $stmt->bindParam(':user_id', $this->user_id);
        if ($stmt->execute()) {
          $status = "OK";
          $message = 'Layer created';
        }
        else {
          $status = "ERROR";
          $message = 'Failed to create your layer';
        }

      } else if ($layer_type == "data") {

        $name = $_POST['name'];
        $description = $_POST['description'];
        $source = $_POST['source'];

        $records = $this->db->prepare('SELECT * FROM data WHERE data_id = :data_id');
        $records->bindParam(':data_id', $source);
        $records->execute();
        $results = $records->fetch(PDO::FETCH_ASSOC);

        $feature_type = $results['feature_type'];
        $style = defaultStyle($feature_type);

        $sql = "INSERT INTO layer (layer_id, name, description, feature_type, style, user_id, cdate) VALUES (:layer_id, :name, :description, :feature_type, :style, :user_id, NOW())";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':layer_id', $layer_id);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':feature_type', $feature_type);
        $stmt->bindParam(':style', $style);
        $stmt->bindParam(':user_id', $this->user_id);
        if ($stmt->execute()) {

          $records = $this->db->prepare('SELECT * FROM data_feature WHERE data_id = :data_id');
          $records->bindParam(':data_id', $source);
          $records->execute();
          while($row = $records->fetch(PDO::FETCH_ASSOC)) {
            $geometry = $row['geometry'];
            $properties = $row['properties'];

            $sql = "INSERT INTO layer_feature (layer_id, geometry, properties) VALUES (:layer_id, :geometry, :properties)";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':layer_id', $layer_id);
            $stmt->bindParam(':geometry', $geometry);
            $stmt->bindParam(':properties', $properties);
            $stmt->execute();
          }

          $status = "OK";
          $message = 'Layer created';
        }
        else {
          $status = "ERROR";
          $message = 'Failed to create your layer';
        }

      } else if ($layer_type == "analysis") {

        $name = $_POST['name'];
        $description = $_POST['description'];
        $source = $_POST['source'];

        $json = json_decode($source,true);

        if (isset($json['features'])) {
          $json = $json['features'];
        } else {
          $status = "ERROR";
          $message = "Invalid GeoJSON file - missing features.";
          return json_encode(['status'=> $status, 'message'=> $message]);
        }

        $i = 1;

        foreach ($json as $feature){
          if ($i == 1) {
            $feature_type = $feature['geometry']['type'];
          }

          $geom = json_encode($feature['geometry']);
          $feature['properties']['ms_fid'] = $i;
          $data = json_encode($feature['properties']);

          $sql = $this->db->prepare("INSERT INTO layer_feature (layer_id, geometry, properties) VALUES (?, ?, ?)");
          $sql->bindParam(1, $layer_id);
          $sql->bindParam(2, $geom);
          $sql->bindParam(3, $data);
          $sql->execute();

          $i++;
        }

        if ($feature_type == "LineString") {
          $feature_type = "PolyLine";
        }
        $style = defaultStyle($feature_type);

        $sql = $this->db->prepare("INSERT INTO layer (layer_id, name, description, feature_type, style, user_id, cdate) VALUES (?, ?, ?, ?, ?, ?, NOW())");
        $sql->bindParam(1, $layer_id);
        $sql->bindParam(2, $name);
        $sql->bindParam(3, $description);
        $sql->bindParam(4, $feature_type);
        $sql->bindParam(5, $style);
        $sql->bindParam(6, $this->user_id);
        $sql->execute();

        $status = "OK";
        $message = 'Layer created and exported';

      }

      $response = json_encode(['status'=> $status, 'message'=>$message]);
      return $response;
    }

    //Delete Layer
    public function deleteLayer($layer_id) {
      $records1 = $this->db->prepare('SELECT COUNT(*) AS count FROM layer WHERE layer_id = :layer_id AND user_id = :user_id');
      $records1->bindParam(':layer_id', $layer_id);
      $records1->bindParam(':user_id', $this->user_id);
      $records1->execute();
      $count = $records1->fetch(PDO::FETCH_ASSOC);

      if($count['count'] == 0) {
        echo json_encode(['status'=> "ERROR", 'message'=>"Unable to delete layer."]);
        exit;
      }

      $records3 = $this->db->prepare('SELECT COUNT(*) AS count FROM map_layers WHERE layer_id = :layer_id');
      $records3->bindParam(':layer_id', $layer_id);
      $records3->execute();
      $count3 = $records3->fetch(PDO::FETCH_ASSOC);

      if($count3['count'] > 0) {
        echo json_encode(['status'=> "ERROR", 'message'=>"Layer already used in map."]);
        exit;
      }

      $records = $this->db->prepare('DELETE FROM layer WHERE layer_id = :layer_id AND user_id = :user_id');
      $records->bindParam(':layer_id', $layer_id);
      $records->bindParam(':user_id', $this->user_id);

      if (!$records->execute()) {
        echo json_encode(['status'=> "ERROR", 'message'=>"Unable to delete layer."]);
        exit;
      }

      $records2 = $this->db->prepare('DELETE FROM layer_feature WHERE layer_id = :layer_id');
      $records2->bindParam(':layer_id', $layer_id);

      if (!$records2->execute()) {
        echo json_encode(['status'=> "ERROR", 'message'=>"Unable to delete layer."]);
        exit;
      }

      $response = json_encode(['status'=> "OK", 'message'=>"Layer deleted."]);
      return $response;
    }

    //Update Layer Information
    public function updateLayer($layer_id, $name, $description) {
      $sql = "UPDATE layer SET name=?, description=? WHERE layer_id=?";
      $stmt= $this->db->prepare($sql);

      if ($stmt->execute([$name, $description, $layer_id])) {
        $status = "OK";
        $message = 'Layer info updated.';
      }
      else {
        $status = "ERROR";
        $message = 'Oops. Failed to update layer info.';
      }

      $response = json_encode(['status'=> "OK", 'message'=>$message]);
      return $response;
    }

    //Export Layer
    public function exportLayer($layer_id) {
      $records1 = $this->db->prepare('SELECT COUNT(*) AS count FROM layer WHERE layer_id = :layer_id AND user_id = :user_id');
      $records1->bindParam(':layer_id', $layer_id);
      $records1->bindParam(':user_id', $this->user_id);
      $records1->execute();
      $count = $records1->fetch(PDO::FETCH_ASSOC);

      if($count['count'] == 0) {
        echo json_encode(['status'=> "ERROR", 'message'=>"Unable to export layer."]);
        exit;
      }

      $data_id = generateRandomString();

      $records = $this->db->prepare('SELECT * FROM layer WHERE layer_id = :layer_id');
      $records->bindParam(':layer_id', $layer_id);
      $records->execute();
      $results = $records->fetch(PDO::FETCH_ASSOC);

      $feature_type = $results['feature_type'];
      $name = $results['name'] . ".geojson";
      $data_type = "GeoJSON";

      $sql = "INSERT INTO data (data_id, name, data_type, feature_type, user_id, cdate) VALUES (:data_id, :name, :data_type, :feature_type, :user_id, NOW())";
      $stmt = $this->db->prepare($sql);
      $stmt->bindParam(':data_id', $data_id);
      $stmt->bindParam(':name', $name);
      $stmt->bindParam(':data_type', $data_type);
      $stmt->bindParam(':feature_type', $feature_type);
      $stmt->bindParam(':user_id', $this->user_id);
      if ($stmt->execute()) {

        $records = $this->db->prepare('SELECT * FROM layer_feature WHERE layer_id = :layer_id');
        $records->bindParam(':layer_id', $layer_id);
        $records->execute();
        while($row = $records->fetch(PDO::FETCH_ASSOC)) {
          $geometry = $row['geometry'];
          $properties = $row['properties'];

          $sql = "INSERT INTO data_feature (data_id, geometry, properties) VALUES (:data_id, :geometry, :properties)";
          $stmt = $this->db->prepare($sql);
          $stmt->bindParam(':data_id', $data_id);
          $stmt->bindParam(':geometry', $geometry);
          $stmt->bindParam(':properties', $properties);
          $stmt->execute();
        }

        $status = "OK";
        $message = 'Layer exported to your data';
      }
      else {
        $status = "ERROR";
        $message = 'Failed to export your layer';
      }

      $response = json_encode(['status'=> $status, 'message'=>$message]);
      return $response;
    }

    //Update Symbology
    public function updateSymbology($layer_id, $type, $style) {
      $json_style = array(
          'type' => $type,
          'style' => json_decode($style)
      );
      $json_style = json_encode($json_style);

      $sql = "UPDATE layer SET style=? WHERE layer_id=?";
      $stmt= $this->db->prepare($sql);

      if ($stmt->execute([$json_style, $layer_id])) {
        $status = "OK";
        $message = 'Symbology updated.';
      }
      else {
        $status = "ERROR";
        $message = 'Oops. Failed to update layer symbology.';
      }

      $response = json_encode(['status'=> "OK", 'message'=>$message]);
      return $response;
    }

    //Add Geometry
    public function addGeometry($layer_id, $geometry, $properties) {
      $geometry = json_decode($geometry, true);
      $type = $geometry['type'];
      if ($type == "Point") {
        $geometry['coordinates'][0] = floatval($geometry['coordinates'][0]);
        $geometry['coordinates'][1] = floatval($geometry['coordinates'][1]);
      } else if ($type == "PolyLine") {
        $length = count($geometry['coordinates']);
        for ($i = 0; $i < $length; $i++) {
          $geometry['coordinates'][$i][0] = floatval($geometry['coordinates'][$i][0]);
          $geometry['coordinates'][$i][1] = floatval($geometry['coordinates'][$i][1]);
        }
      } else if ($type == "Polygon") {
        $length = count($geometry['coordinates']);
        for ($i = 0; $i < $length; $i++) {
          $length_i = count($geometry['coordinates'][$i]);
          for ($j = 0; $j < $length_i; $j++) {
            $geometry['coordinates'][$i][$j][0] = floatval($geometry['coordinates'][$i][$j][0]);
            $geometry['coordinates'][$i][$j][1] = floatval($geometry['coordinates'][$i][$j][1]);
          }
        }
      }
      $geometry = json_encode($geometry);

      $properties = json_decode($properties, true);
      $properties['ms_fid'] = intval($properties['ms_fid']);
      $properties = json_encode($properties);

      $sql = "INSERT INTO layer_feature (layer_id, geometry, properties) VALUES (?, ?, ?)";
      $stmt= $this->db->prepare($sql);

      if ($stmt->execute([$layer_id, $geometry, $properties])) {
        $status = "OK";
        $message = 'Geometry updated.';
      }
      else {
        $status = "ERROR";
        $message = 'Oops. Failed to update layer geometry.';
      }

      $response = json_encode(['status'=> "OK", 'message'=>$message]);
      return $response;
    }

    //Update Geometry by ms_fid
    public function updateGeometry($layer_id, $ms_fid, $geometry) {
      $sql = 'UPDATE layer_feature SET geometry=? WHERE layer_id = ? AND JSON_EXTRACT(properties, "$.ms_fid") = ?';
      $stmt= $this->db->prepare($sql);

      if ($stmt->execute([$geometry, $layer_id, $ms_fid])) {
        $status = "OK";
        $message = 'Geometry updated.';
      }
      else {
        $status = "ERROR";
        $message = 'Oops. Failed to update layer geometry.';
      }

      $response = json_encode(['status'=> "OK", 'message'=>$message]);
      return $response;
    }

    //Delete Geometry by ms_fid
    public function deleteGeometry($layer_id, $ms_fid, $geometry) {
      $sql = 'DELETE FROM layer_feature WHERE layer_id = ? AND JSON_EXTRACT(properties, "$.ms_fid") = ?';
      $stmt= $this->db->prepare($sql);

      if ($stmt->execute([$layer_id, $ms_fid])) {
        $status = "OK";
        $message = 'Geometry deleted.';
      }
      else {
        $status = "ERROR";
        $message = 'Oops. Failed to delete layer geometry.';
      }

      $response = json_encode(['status'=> "OK", 'message'=>$message]);
      return $response;
    }

}

?>
