<?php

class Parking {
    public $parkingid;
    public $depot;
    public $charger;
    public $modified;
}

class PPerson {
    public $personid;
    public $name;
    public $email;
}

class Parkings {

    protected $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    private function read($row) {
        $result = new Parking();
        $result->parkingid = $row["ParkingId"];
        $result->depot     = $row["Depot"] != 0 ? true : false;
        $result->charger   = $row["Charger"] != 0 ? true : false;
        $result->modified  = $row["Modified"];
        return $result;
    }

    private function readPPerson($row) {
        $result = new PPerson();
        $result->personid = $row["PersonId"];
        $result->name     = $row["Name"];
        $result->email    = $row["Email"];
        return $result;
    }

    public function getById($parkingid) {
        $sql = "SELECT * FROM Parkings WHERE ParkingId = :parkingid";
        $q = $this->db->prepare($sql);
        $q->bindValue(":parkingid", $parkingid, PDO::PARAM_INT);
        $q->execute();
        $rows = $q->fetchAll();
        return $this->read($rows[0]);
    }

    public function getAll() {
        $rows = $this->db->query("SELECT * FROM Parkings")->fetchAll();

        $result = array();
        foreach($rows as $row) {
            array_push($result, $this->read($row));
        }
        return $result;
    }

    public function getFiltered($filter) {
        $parkingid  = "%" . $filter["parkingid"] . "%";
        $depot      = $filter["depot"];
        $charger    = $filter["charger"];
        $owner      = $filter["owner"];
        $extern     = $filter["extern"];
        $tenant     = $filter["tenant"];
        $historical = $filter["historical"];
        /*
        $sql = "SELECT * FROM Parkings WHERE ParkingId LIKE :parkingid AND (:depot1 IS NULL OR Depot = :depot2) AND (:charger1 IS NULL OR Charger = :charger2)";
        */
        $sql = "SELECT * FROM Parkings WHERE ParkingId LIKE :parkingid AND ".
               "(:depot1 IS NULL OR Depot = :depot2) AND ".
               "(:charger1 IS NULL OR Charger = :charger2) AND ParkingId IN ".
               "(SELECT ParkingId FROM Parkings JOIN PersonsParkings ON (ParkingId = Id) ".
               "WHERE ((:owner = 1 AND Relation = 1) OR (:extern = 1 AND Relation = 2) OR (:tenant = 1 AND Relation = 3)) AND ".
               "(:historical = 1 OR Stopped = '0000-00-00' OR Stopped >= CURDATE()))";

        $q = $this->db->prepare($sql);
        $q->bindValue(":parkingid",  $parkingid);
        $q->bindValue(":depot1",     $depot,   PDO::PARAM_BOOL);
        $q->bindValue(":depot2",     $depot,   PDO::PARAM_BOOL); // Cannot reuse name in WHERE
        $q->bindValue(":charger1",   $charger, PDO::PARAM_BOOL);
        $q->bindValue(":charger2",   $charger, PDO::PARAM_BOOL); // Cannot reuse name in WHERE
        $q->bindValue(":owner",      $owner);
        $q->bindValue(":extern",     $extern);
        $q->bindValue(":tenant",     $tenant);
        $q->bindValue(":historical", $historical);
        $q->execute();
        $rows = $q->fetchAll();

        $result = array();
        foreach($rows as $row) {
            array_push($result, $this->read($row));
        }
        return $result;
    }

    public function insert($data) {
        $sql = "INSERT INTO Parkings (ParkingId, Depot, Charger) VALUES (:parkingid, :depot, :charger)";
        $q = $this->db->prepare($sql);
        $q->bindValue(":parkingid", $data["parkingid"], PDO::PARAM_INT);
        $q->bindValue(":depot",     $data["depot"],     PDO::PARAM_BOOL);
        $q->bindValue(":charger",   $data["charger"],   PDO::PARAM_BOOL);
        $q->execute();
        return $this->getById($data["parkingid"]);
    }

    public function update($data) {
        $sql = "UPDATE Parkings SET Depot = :depot, Charger = :charger WHERE ParkingId = :parkingid";
        $q = $this->db->prepare($sql);
        $q->bindValue(":parkingid", $data["parkingid"], PDO::PARAM_INT);
        $q->bindValue(":depot",     $data["depot"],     PDO::PARAM_BOOL);
        $q->bindValue(":charger",   $data["charger"],   PDO::PARAM_BOOL);
        $q->execute();
    }

    public function remove($parkingid) {
        $sql = "DELETE FROM Parkings WHERE ParkingId = :parkingid";
        $q = $this->db->prepare($sql);
        $q->bindValue(":parkingid", $parkingid, PDO::PARAM_INT);
        $q->execute();
    }
}

?>
