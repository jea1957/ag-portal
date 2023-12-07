<?php

class Relation {
    public $personid;
    public $id;
    public $relation;
    public $started;
    public $stopped;
    public $modified;
}

class Relations {

    protected $db;
    protected $table;

    public function __construct(PDO $db, string $table) {
        $this->db = $db;
        $this->table = $table;
    }

    private function read($row) {
        $result = new Relation();
        $result->personid = $row["PersonId"];
        $result->id       = $row["Id"];
        $result->relation = $row["Relation"];
        $result->started  = ($row["Started"] === '0000-00-00') ? '' : $row["Started"] ;
        $result->stopped  = ($row["Stopped"] === '0000-00-00') ? '' : $row["Stopped"] ;
        $result->modified = $row["Modified"];
        return $result;
    }

    public function getById($personid, $id) {
        $sql = "SELECT * FROM $this->table WHERE PersonId = :personid AND Id = :id";
        $q = $this->db->prepare($sql);
        $q->bindValue(":personid", $personid, PDO::PARAM_INT);
        $q->bindValue(":id",       $id,       PDO::PARAM_INT);
        $q->execute();
        $rows = $q->fetchAll();
        return $this->read($rows[0]);
    }

    public function getAll($filter) {
        $personid   = $filter["personid"];
        $id         = $filter["id"];
        $historical = $filter["historical"];

        $sql = "SELECT * FROM $this->table WHERE (:personid1 = 0 OR PersonId = :personid2) ".
                                          "AND (:id1 = 0 OR Id = :id2) ".
                                          "AND (:historical = 1 OR Stopped = '0000-00-00' OR Stopped >= CURDATE())";
        $q = $this->db->prepare($sql);
        $q->bindValue(":personid1",  $personid,   PDO::PARAM_INT);
        $q->bindValue(":personid2",  $personid,   PDO::PARAM_INT); // Cannot reuse name in WHERE
        $q->bindValue(":id1"     ,   $id,         PDO::PARAM_INT);
        $q->bindValue(":id2",        $id,         PDO::PARAM_INT); // Cannot reuse name in WHERE
        $q->bindValue(":historical", $historical, PDO::PARAM_INT);
        $q->execute();
        $rows = $q->fetchAll();

        $result = array();
        foreach($rows as $row) {
            array_push($result, $this->read($row));
        }
        return $result;
    }

    public function insert($data) {
        $sql = "INSERT INTO $this->table (PersonId, Id, Relation, Started, Stopped) VALUES (:personid, :id, :relation, :started, :stopped)";
        $q = $this->db->prepare($sql);
        $q->bindValue(":personid", $data["personid"], PDO::PARAM_INT);
        $q->bindValue(":id",       $data["id"],       PDO::PARAM_INT);
        $q->bindValue(":relation", $data["relation"], PDO::PARAM_INT);
        $q->bindValue(":started",  empty($data["started"]) ? '0000-00-00' : $data["started"]);
        $q->bindValue(":stopped",  empty($data["stopped"]) ? '0000-00-00' : $data["stopped"]);
        $q->execute();
        return $this->getById($data["personid"], $data["id"]);
    }

    public function update($data) {
        $sql = "UPDATE $this->table SET Relation = :relation, Started = :started, Stopped = :stopped WHERE PersonId = :personid AND Id = :id";
        $q = $this->db->prepare($sql);
        $q->bindValue(":personid", $data["personid"], PDO::PARAM_INT);
        $q->bindValue(":id",       $data["id"],       PDO::PARAM_INT);
        $q->bindValue(":relation", $data["relation"], PDO::PARAM_INT);
        $q->bindValue(":started",  empty($data["started"]) ? '0000-00-00' : $data["started"]);
        $q->bindValue(":stopped",  empty($data["stopped"]) ? '0000-00-00' : $data["stopped"]);
        $q->execute();
        return $this->getById($data["personid"], $data["id"]);
    }

    public function remove($data) {
        $sql = "DELETE FROM $this->table WHERE PersonId = :personid AND Id = :id";
        $q = $this->db->prepare($sql);
        $q->bindValue(":personid", $data["personid"], PDO::PARAM_INT);
        $q->bindValue(":id",       $data["id"],       PDO::PARAM_INT);
        $q->execute();
    }
}

?>
