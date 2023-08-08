<?php

class Data {
    private $db;
    private $user_id;

    public function __construct($db, $user_id) {
        $this->db = $db;
        $this->user_id = $user_id;
    }

    //Get All Data
    public function getAllData() {
      $records = $this->db->prepare('SELECT name, data_type, feature_type, data_id, cdate FROM data WHERE user_id = :user_id');
      $records->bindParam(':user_id', $this->user_id);
      $records->execute();
      $results = $records->fetchAll(PDO::FETCH_ASSOC);

      $response = json_encode(['status'=> "OK", 'message'=>"Data fetched.", 'data'=> $results]);
      return $response;
    }

    //View Data
    public function viewData($data_id) {
      $count_records = $this->db->prepare('SELECT COUNT(*) AS count FROM data WHERE data_id = :data_id AND user_id = :user_id');
      $count_records->bindParam(':data_id', $data_id);
      $count_records->bindParam(':user_id', $this->user_id);
      $count_records->execute();
      $count = $count_records->fetch(PDO::FETCH_ASSOC);

      if ($count['count'] == 0) {
        $response = json_encode(['status'=> "ERROR", 'message'=>"No layer."]);
        echo $response;
        die();
      }

      $records = $this->db->prepare('SELECT name, data_type, feature_type, data_id, cdate FROM data WHERE data_id = :data_id');
      $records->bindParam(':data_id', $data_id);
      $records->execute();
      $results = $records->fetch(PDO::FETCH_ASSOC);

      $records1 = $this->db->prepare('SELECT COUNT(*) AS count FROM data_feature WHERE data_id = :data_id');
      $records1->bindParam(':data_id', $data_id);
      $records1->execute();
      $count1 = $records1->fetch(PDO::FETCH_ASSOC);

      $records2 = $this->db->prepare('SELECT id, data_id, geometry, properties FROM data_feature WHERE data_id = :data_id');
      $records2->bindParam(':data_id', $data_id);
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

      $response = json_encode(['status'=> "OK", 'message'=>"Data fetched.", 'name'=>$results['name'], 'cdate'=>$results['cdate'], 'count'=>$count1['count'], 'type'=>$results['feature_type'], 'data'=>$json]);
      return $response;
    }

    //Delete Data
    public function deleteData($data_id) {
      $records1 = $this->db->prepare('SELECT COUNT(*) AS count FROM data WHERE data_id = :data_id AND user_id = :user_id');
      $records1->bindParam(':data_id', $data_id);
      $records1->bindParam(':user_id', $this->user_id);
      $records1->execute();
      $count = $records1->fetch(PDO::FETCH_ASSOC);

      if($count['count'] == 0) {
        echo json_encode(['status'=> "ERROR", 'message'=>"Unable to delete data."]);
        exit;
      }

      $records = $this->db->prepare('DELETE FROM data WHERE data_id = :data_id AND user_id = :user_id');
      $records->bindParam(':data_id', $data_id);
      $records->bindParam(':user_id', $this->user_id);

      if (!$records->execute()) {
        echo json_encode(['status'=> "ERROR", 'message'=>"Unable to delete data."]);
        exit;
      }

      $records2 = $this->db->prepare('DELETE FROM data_feature WHERE data_id = :data_id');
      $records2->bindParam(':data_id', $data_id);

      if (!$records2->execute()) {
        echo json_encode(['status'=> "ERROR", 'message'=>"Unable to delete data."]);
        exit;
      }

      $response = json_encode(['status'=> "OK", 'message'=>"Data deleted."]);
      return $response;
    }

    //Export Data
    public function exportData($data_id) {
      $data_id = $_POST['data_id'];
      $format = $_POST['format'];

      $records = $this->db->prepare('SELECT name, data_type, feature_type, data_id, cdate FROM data WHERE data_id = :data_id');
      $records->bindParam(':data_id', $data_id);
      $records->execute();
      $results = $records->fetch(PDO::FETCH_ASSOC);

      $records1 = $this->db->prepare('SELECT COUNT(*) AS count FROM data_feature WHERE data_id = :data_id');
      $records1->bindParam(':data_id', $data_id);
      $records1->execute();
      $count = $records1->fetch(PDO::FETCH_ASSOC);

      $records2 = $this->db->prepare('SELECT id, data_id, geometry, properties FROM data_feature WHERE data_id = :data_id');
      $records2->bindParam(':data_id', $data_id);
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

      $response = json_encode(['status'=> "OK", 'message'=>"Data exported.", 'name'=>$results['name'], 'data'=>$json]);
      return $response;
    }

}

?>
