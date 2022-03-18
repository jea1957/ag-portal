<?php

class Person {
    public $personid;
    public $name;
    public $address;
    public $email;
    public $phone;
    public $nomails;
    public $modified;
}

class Persons {

    protected $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    private function read($row) {
        $result = new Person();
        $result->personid = $row["PersonId"];
        $result->name     = $row["Name"];
        $result->address  = $row["Address"];
        $result->email    = $row["Email"];
        $result->phone    = $row["Phone"];
        $result->nomails  = $row["NoMails"] != 0 ? true : false;
        $result->modified = $row["Modified"];
        return $result;
    }

    public function getById($personid) {
        $sql = "SELECT * FROM Persons WHERE PersonId = :personid";
        $q = $this->db->prepare($sql);
        $q->bindValue(":personid", $personid, PDO::PARAM_INT);
        $q->execute();
        $rows = $q->fetchAll();
        return $this->read($rows[0]);
    }

    public function getAll() {
        $rows = $this->db->query("SELECT * FROM Persons")->fetchAll();

        $result = array();
        foreach($rows as $row) {
            array_push($result, $this->read($row));
        }
        return $result;
    }

    public function getFiltered($filter) {
        $name       = "%" . $filter["name"] . "%";
        $address    = "%" . $filter["address"] . "%";
        $email      = "%" . $filter["email"] . "%";
        $phone      = "%" . $filter["phone"] . "%";
        $nomails    = $filter["nomails"];
        $sql = "SELECT * FROM Persons WHERE Name LIKE :name AND Address LIKE :address AND Email LIKE :email AND Phone LIKE :phone AND (:nomails1 IS NULL OR NoMails = :nomails2)";
        $q = $this->db->prepare($sql);
        $q->bindValue(":name",        $name);
        $q->bindValue(":address",     $address);
        $q->bindValue(":email",       $email);
        $q->bindValue(":phone",       $phone);
        $q->bindValue(":nomails1",    $nomails, PDO::PARAM_BOOL);
        $q->bindValue(":nomails2",    $nomails, PDO::PARAM_BOOL); // Cannot reuse name in WHERE
        $q->execute();
        $rows = $q->fetchAll();

        $result = array();
        foreach($rows as $row) {
            array_push($result, $this->read($row));
        }
        return $result;
    }

    public function insert($data) {
        $sql = "INSERT INTO Persons (Name, Address, Email, Phone, NoMails) VALUES (:name, :address, :email, :phone, :nomails)";
        $q = $this->db->prepare($sql);
        $q->bindValue(":name",    $data["name"]);
        $q->bindValue(":address", $data["address"]);
        $q->bindValue(":email",   $data["email"]);
        $q->bindValue(":phone",   $data["phone"]);
        $q->bindValue(":nomails", $data["nomails"], PDO::PARAM_BOOL);
        $q->execute();
        return $this->getById($this->db->lastInsertId());
    }

    public function update($data) {
        $sql = "UPDATE Persons SET Name = :name, Address = :address, Email = :email, Phone = :phone, Nomails = :nomails WHERE PersonId = :personid";
        $q = $this->db->prepare($sql);
        $q->bindValue(":personid", $data["personid"], PDO::PARAM_INT);
        $q->bindValue(":name",     $data["name"]);
        $q->bindValue(":address",  $data["address"]);
        $q->bindValue(":email",    $data["email"]);
        $q->bindValue(":phone",    $data["phone"]);
        $q->bindValue(":nomails",  $data["nomails"],  PDO::PARAM_BOOL);
        $q->execute();
        return $this->getById($data["personid"]);
    }

    public function remove($personid) {
        $sql = "DELETE FROM Persons WHERE PersonId = :personid";
        $q = $this->db->prepare($sql);
        $q->bindValue(":personid", $personid, PDO::PARAM_INT);
        $q->execute();
    }
}

?>
