<?php

class Map {
    private $db;
    private $user_id;

    public function __construct($db, $user_id) {
        $this->db = $db;
        $this->user_id = $user_id;
    }

    //Add Map
    public function addMap($name, $description) {
      while(true){
        $map_id = generateRandomString(8);
        $sql_map = "SELECT * FROM map WHERE map_id = :map_id";
        $stmt_map = $this->db->prepare($sql_map);
        $stmt_map->bindParam(':map_id', $map_id);
        $stmt_map->execute();
        $row_map = $results_map = $stmt_map->fetch(PDO::FETCH_ASSOC);

        if(!$row_map) {
          break;
        }
      }

      $template = "0_basic";
      $options = '{"color": "blue","minZoom": "3","maxZoom": "18","fitBounds": "1","basemaps": {"carto": "1","osm_basic": "0","osm_mapnik": "0","cycloosm": "0","esri_ortophoto": "1","none": "0"},"plugins": {"geolocate": "1","fullscreen": "1","print": "0","measurement": "0","minimap": "0","mouse_coords": "0"}}';

      $sql = "INSERT INTO map (map_id, name, description, template, options, user_id, cdate, shared) VALUES (:map_id, :name, :description, :template, :options, :user_id, NOW(), 1)";
      $stmt = $this->db->prepare($sql);
      $stmt->bindParam(':map_id', $map_id);
      $stmt->bindParam(':name', $name);
      $stmt->bindParam(':description', $description);
      $stmt->bindParam(':template', $template);
      $stmt->bindParam(':options', $options);
      $stmt->bindParam(':user_id', $this->user_id);
      if ($stmt->execute()) {
        $status = "OK";
        $message = 'Map created';
      }
      else {
        $status = "ERROR";
        $message = 'Failed to create your map';
      }

      $response = json_encode(['status'=> $status, 'message'=>$message]);
      return $response;
    }

    //Get Map to /s/map.php (Share)
    public function getMap($map_id) {
      $count_records = $this->db->prepare('SELECT COUNT(*) AS count FROM map WHERE map_id = :map_id');
      $count_records->bindParam(':map_id', $map_id);
      $count_records->execute();
      $count = $count_records->fetch(PDO::FETCH_ASSOC);

      if ($count['count'] == 0) {
        $response = json_encode(['status'=> "ERROR", 'message'=>"No map."]);
        return $response;
        die();
      }

      $records = $this->db->prepare('SELECT name, description, template, options, cdate, shared FROM map WHERE map_id = :map_id');
      $records->bindParam(':map_id', $map_id);
      $records->execute();
      $results = $records->fetch(PDO::FETCH_ASSOC);

      $records1 = $this->db->prepare('SELECT COUNT(*) AS count FROM map_layers WHERE map_id = :map_id');
      $records1->bindParam(':map_id', $map_id);
      $records1->execute();
      $count1 = $records1->fetch(PDO::FETCH_ASSOC);

      $records2 = $this->db->prepare('SELECT map_layers.layer_id, name, style, feature_type FROM map_layers LEFT JOIN layer ON map_layers.layer_id = layer.layer_id WHERE map_id = :map_id ORDER BY map_layers.id');
      $records2->bindParam(':map_id', $map_id);
      $records2->execute();

      $layers = array();

      while($row = $records2->fetch(PDO::FETCH_ASSOC))
      {
        $records3 = $this->db->prepare('SELECT id, layer_id, geometry, properties FROM layer_feature WHERE layer_id = :layer_id');
        $records3->bindParam(':layer_id', $row['layer_id']);
        $records3->execute();

        $json = array(
            'type' => 'FeatureCollection',
            'features' => array(
            )
        );

        while($row_detail = $records3->fetch(PDO::FETCH_ASSOC))
        {
          $geom = json_decode($row_detail['geometry']);
          $data = json_decode($row_detail['properties']);

          $feature = array(
            'type' => 'Feature',
            'geometry' => $geom,
            'properties' => $data
          );
          array_push($json['features'],$feature);
        }

        $layer_info = array(
          'layer_id' => $row['layer_id'],
          'name' => $row['name'],
          'feature_type' => $row['feature_type'],
          'style' => json_decode($row['style']),
          'data' => $json
        );

        array_push($layers,$layer_info);
      }

      $response = json_encode(['status'=> "OK", 'message'=>"Data fetched.", 'name'=>$results['name'], 'description'=>$results['description'], 'template'=>$results['template'], 'options'=>json_decode($results['options']), 'layers'=>$layers, 'cdate'=>$results['cdate'], 'count'=>$count1['count'], 'shared'=>$results['shared']]);
      return $response;
    }

    //View Map Info (Edit)
    public function viewMap($map_id) {
      $count_records = $this->db->prepare('SELECT COUNT(*) AS count FROM map WHERE map_id = :map_id AND user_id = :user_id');
      $count_records->bindParam(':map_id', $map_id);
      $count_records->bindParam(':user_id', $this->user_id);
      $count_records->execute();
      $count = $count_records->fetch(PDO::FETCH_ASSOC);

      if ($count['count'] == 0) {
        $response = json_encode(['status'=> "ERROR", 'message'=>"No map."]);
        return $response;
        die();
      }

      $records = $this->db->prepare('SELECT name, description, template, options, cdate, shared FROM map WHERE map_id = :map_id');
      $records->bindParam(':map_id', $map_id);
      $records->execute();
      $results = $records->fetch(PDO::FETCH_ASSOC);

      $records1 = $this->db->prepare('SELECT COUNT(*) AS count FROM map_layers WHERE map_id = :map_id');
      $records1->bindParam(':map_id', $map_id);
      $records1->execute();
      $count1 = $records1->fetch(PDO::FETCH_ASSOC);

      $response = json_encode(['status'=> "OK", 'message'=>"Data fetched.", 'name'=>$results['name'], 'description'=>$results['description'], 'template'=>$results['template'], 'options'=>json_decode($results['options']), 'cdate'=>$results['cdate'], 'count'=>$count1['count'], 'shared'=>$results['shared']]);
      return $response;
    }

    //Add Layer to Map
    public function addLayerToMap($map_id, $layer_id) {
      $count_records = $this->db->prepare('SELECT COUNT(*) AS count FROM map_layers WHERE map_id = :map_id AND layer_id = :layer_id');
      $count_records->bindParam(':map_id', $map_id);
      $count_records->bindParam(':layer_id', $layer_id);
      $count_records->execute();
      $count = $count_records->fetch(PDO::FETCH_ASSOC);

      if ($count['count'] >  0) {
        $response = json_encode(['status'=> "ERROR", 'message'=>"Layer already added to map."]);
        return $response;
        die();
      }

      $sql = "INSERT INTO map_layers (map_id, layer_id) VALUES (:map_id, :layer_id)";
      $stmt = $this->db->prepare($sql);
      $stmt->bindParam(':map_id', $map_id);
      $stmt->bindParam(':layer_id', $layer_id);
      if ($stmt->execute()) {
        $status = "OK";
        $message = 'Layer added to map';
      }
      else {
        $status = "ERROR";
        $message = 'Failed to add your layer to map';
      }

      $response = json_encode(['status'=> $status, 'message'=>$message]);
      return $response;
    }

    //Remove Layer from Map
    public function removeLayerFromMap($map_id, $layer_id) {
      $sql = 'DELETE FROM map_layers WHERE map_id = ? AND layer_id = ?';
      $stmt= $this->db->prepare($sql);

      if ($stmt->execute([$map_id, $layer_id])) {
        $status = "OK";
        $message = 'Layer deleted.';
      }
      else {
        $status = "ERROR";
        $message = 'Oops. Failed to delete layer.';
      }

      $response = json_encode(['status'=> "OK", 'message'=>$message]);
      return $response;

    }

    //Get Map Layers
    public function getMapLayers($map_id) {
      $records = $this->db->prepare('SELECT map_layers.map_id, map_layers.layer_id, layer.name FROM map_layers LEFT JOIN layer ON map_layers.layer_id = layer.layer_id WHERE map_id = :map_id ORDER BY map_layers.id');
      $records->bindParam(':map_id', $map_id);
      $records->execute();
      $results = $records->fetchAll(PDO::FETCH_ASSOC);

      $response = json_encode(['status'=> "OK", 'message'=>"Data fetched.", 'data'=> $results]);
      return $response;
    }

    //Get All Maps
    public function getAllMaps() {
      $records = $this->db->prepare('SELECT map_id, name, description, cdate FROM map WHERE user_id = :user_id');
      $records->bindParam(':user_id', $this->user_id);
      $records->execute();
      $results = $records->fetchAll(PDO::FETCH_ASSOC);

      $response = json_encode(['status'=> "OK", 'message'=>"Data fetched.", 'data'=> $results]);
      return $response;
    }

    //Update Map Info
    public function updateMap($map_id, $name, $template, $options) {
      $sql = "UPDATE map SET name=?, template=?, options=? WHERE map_id=?";
      $stmt= $this->db->prepare($sql);

      if ($stmt->execute([$name, $template, $options, $map_id])) {
        $status = "OK";
        $message = 'Map info updated.';
      }
      else {
        $status = "ERROR";
        $message = 'Oops. Failed to update map info.';
      }

      $response = json_encode(['status'=> "OK", 'message'=>$message]);
      return $response;
    }

}

?>
