<?php

class Depot {
    public $depotid;
    public $number;
    public $modified;
    public $isfree;
}

class DepotWait {
    public $waitid;
    public $personid;
    public $modified;
}

class Depots {

    protected $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    private function isFree($depotid) {
        $sql = "SELECT * FROM PersonsDepots WHERE Id = :depotid AND (Stopped = '0000-00-00' OR Stopped >= CURDATE())";
        $q = $this->db->prepare($sql);
        $q->bindValue(":depotid", $depotid, PDO::PARAM_INT);
        $q->execute();
        $rows = $q->fetchAll();
        return !$rows;
    }

    private function read($row) {
        $result = new Depot();
        $result->depotid  = $row["DepotId"];
        $result->number   = $row["Number"];
        $result->modified = $row["Modified"];
        $result->isfree   = $this->isFree($row["DepotId"]);
        return $result;
    }

    public function getById($depotid) {
        $sql = "SELECT * FROM Depots WHERE DepotId = :depotid";
        $q = $this->db->prepare($sql);
        $q->bindValue(":depotid", $depotid, PDO::PARAM_INT);
        $q->execute();
        $rows = $q->fetchAll();
        return $this->read($rows[0]);
    }

    public function getAll() {
        $rows = $this->db->query("SELECT * FROM Depots")->fetchAll();

        $result = array();
        foreach($rows as $row) {
            array_push($result, $this->read($row));
        }
        return $result;
    }

    public function getFiltered($filter) {
        $depotid    = "%" . $filter["depotid"] . "%";
        $number     = "%" . $filter["number"]  ."%";
        $sql = "SELECT * FROM Depots WHERE DepotId LIKE :depotid AND Number LIKE :number";
        $q = $this->db->prepare($sql);
        $q->bindValue(":depotid",    $depotid);
        $q->bindValue(":number",     $number);
        $q->execute();
        $rows = $q->fetchAll();

        $result = array();
        foreach($rows as $row) {
            array_push($result, $this->read($row));
        }
        return $result;
    }

    public function insert($data) {
        $sql = "INSERT INTO Depots (DepotId, Number) VALUES (:depotid, :number)";
        $q = $this->db->prepare($sql);
        $q->bindValue(":depotid", $data["depotid"], PDO::PARAM_INT);
        $q->bindValue(":number",  $data["number"]);
        $q->execute();
        return $this->getById($data["depotid"]);
    }

    public function update($data) {
        $sql = "UPDATE Depots SET Number = :number WHERE DepotId = :depotid";
        $q = $this->db->prepare($sql);
        $q->bindValue(":depotid", $data["depotid"], PDO::PARAM_INT);
        $q->bindValue(":number",  $data["number"]);
        $q->execute();
    }

    public function remove($depotid) {
        $sql = "DELETE FROM Depots WHERE DepotId = :depotid";
        $q = $this->db->prepare($sql);
        $q->bindValue(":depotid", $depotid, PDO::PARAM_INT);
        $q->execute();
    }

// Depot waiting list
    private function readWait($row) {
        $result = new DepotWait();
        $result->waitid   = $row["WaitId"];
        $result->personid = $row["PersonId"];
        $result->modified = $row["Modified"];
        return $result;
    }

    private function getWaitById($waitid) {
        $sql = "SELECT * FROM PersonsDepotsWait WHERE WaitId = :waitid";
        $q = $this->db->prepare($sql);
        $q->bindValue(":waitid", $waitid, PDO::PARAM_INT);
        $q->execute();
        $row = $q->fetch();
        return $this->readWait($row);
    }

    public function getWait() {
        $rows = $this->db->query("SELECT * FROM PersonsDepotsWait")->fetchAll();

        $result = array();
        foreach($rows as $row) {
            array_push($result, $this->readWait($row));
        }
        return $result;
    }

    public function addWait($personid) {
        $sql = "INSERT INTO PersonsDepotsWait (PersonId) VALUES (:personid)";
        $q = $this->db->prepare($sql);
        $q->bindValue(":personid", $personid, PDO::PARAM_INT);
        $q->execute();
        return $this->getWaitById($this->db->lastInsertId());
    }

    public function delWait($waitid) {
        $sql = "DELETE FROM PersonsDepotsWait WHERE WaitId = :waitid";
        $q = $this->db->prepare($sql);
        $q->bindValue(":waitid", $waitid, PDO::PARAM_INT);
        $q->execute();
    }

}

?>
