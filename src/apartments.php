<?php

class Apartment {
    public $apartmentid;
    public $number;
    public $floor;
    public $side;
    public $type;
    public $size;
    public $reduction;
    public $tapshares;
    public $shafts;
    public $modified;
}

class Apartments {

    protected $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    private function read($row) {
        $result = new Apartment();
        $result->apartmentid = $row["ApartmentId"];
        $result->number      = $row["Number"];
        $result->floor       = $row["Floor"];
        $result->side        = $row["Side"];
        $result->type        = $row["Type"];
        $result->size        = $row["Size"];
        $result->reduction   = $row["Reduction"];
        $result->tapshares   = $row["TapShares"];
        $result->shafts      = $row["Shafts"];
        $result->modified    = $row["Modified"];
        return $result;
    }

    public function getById($apartmentid) {
        $sql = "SELECT * FROM Apartments WHERE ApartmentId = :apartmentid";
        $q = $this->db->prepare($sql);
        $q->bindValue(":apartmentid", $apartmentid, PDO::PARAM_INT);
        $q->execute();
        $rows = $q->fetchAll();
        return $this->read($rows[0]);
    }

    public function getAll() {
        $rows = $this->db->query("SELECT * FROM Apartments")->fetchAll();

        $result = array();
        foreach($rows as $row) {
            array_push($result, $this->read($row));
        }
        return $result;
    }

    public function getFiltered($filter) {
        $apartmentid = "%" . $filter["apartmentid"] . "%";
        $number      = "%" . $filter["number"]      . "%";
        $floor       = "%" . $filter["floor"]       . "%";
        $side        = "%" . $filter["side"]        . "%";
        $type        = "%" . $filter["type"]        . "%";
        $size        = "%" . $filter["size"]        . "%";
        $reduction   = "%" . $filter["reduction"]   . "%";
        $tapshares   = "%" . $filter["tapshares"]   . "%";
        $shafts      = "%" . $filter["shafts"]      . "%";
        $owner       = $filter["owner"];
        $extern      = $filter["extern"];
        $tenant      = $filter["tenant"];
        $historical  = $filter["historical"];

        $sql = "SELECT * FROM Apartments WHERE ApartmentId LIKE :apartmentid AND Number LIKE :number AND ".
                                              "Floor LIKE :floor AND Side LIKE :side AND Type LIKE :type AND ".
                                              "Size LIKE :size AND Reduction LIKE :reduction AND TapShares LIKE :tapshares AND Shafts LIKE :shafts AND ApartmentId IN ".
               "(SELECT ApartmentId FROM Apartments JOIN PersonsApartments ON (ApartmentId = Id) ".
               "WHERE ((:owner = 1 AND Relation = 1) OR (:extern = 1 AND Relation = 2) OR (:tenant = 1 AND Relation = 3)) AND ".
               "(:historical = 1 OR Stopped = '0000-00-00' OR Stopped >= CURDATE()))";
                                              
        $q = $this->db->prepare($sql);
        $q->bindValue(":apartmentid", $apartmentid);
        $q->bindValue(":number",      $number);
        $q->bindValue(":floor",       $floor);
        $q->bindValue(":side",        $side);
        $q->bindValue(":type",        $type);
        $q->bindValue(":size",        $size);
        $q->bindValue(":reduction",   $reduction);
        $q->bindValue(":tapshares",   $tapshares);
        $q->bindValue(":shafts",      $shafts);
        $q->bindValue(":owner",       $owner);
        $q->bindValue(":extern",      $extern);
        $q->bindValue(":tenant",      $tenant);
        $q->bindValue(":historical",  $historical);
        $q->execute();
        $rows = $q->fetchAll();
        $result = array();
        foreach($rows as $row) {
            array_push($result, $this->read($row));
        }
        return $result;
    }

    public function insert($data) {
        $sql = "INSERT INTO Apartments (ApartmentId, Number, Floor, Side, Type, Size, Reduction, TapShares, Shafts) ".
               "VALUES (:apartmentid, :number, :floor, :side, :type, :size, :reduction, :tapshares, :shafts)";
        $q = $this->db->prepare($sql);
        $q->bindValue(":apartmentid", $data["apartmentid"], PDO::PARAM_INT);
        $q->bindValue(":number",      $data["number"]);
        $q->bindValue(":floor",       $data["floor"]);
        $q->bindValue(":side",        $data["side"]);
        $q->bindValue(":type",        $data["type"]);
        $q->bindValue(":size",        $data["size"]);
        $q->bindValue(":reduction",   $data["reduction"]);
        $q->bindValue(":tapshares",   $data["tapshares"]);
        $q->bindValue(":shafts",      $data["shafts"]);
        $q->execute();
        return $this->getById($data["apartmentid"]);
    }

    public function update($data) {
        $sql = "UPDATE Apartments SET Number = :number, Floor = :floor, Side = :side, Type = :type, Size = :size, Reduction = :reduction, TapShares = :tapshares, Shafts = :shafts ".
               "WHERE ApartmentId = :apartmentid";
        $q = $this->db->prepare($sql);
        $q->bindValue(":apartmentid", $data["apartmentid"], PDO::PARAM_INT);
        $q->bindValue(":number",      $data["number"]);
        $q->bindValue(":floor",       $data["floor"]);
        $q->bindValue(":side",        $data["side"]);
        $q->bindValue(":type",        $data["type"]);
        $q->bindValue(":size",        $data["size"]);
        $q->bindValue(":reduction",   $data["reduction"]);
        $q->bindValue(":tapshares",   $data["tapshares"]);
        $q->bindValue(":shafts",      $data["shafts"]);
        $q->execute();
    }

    public function remove($apartmentid) {
        $sql = "DELETE FROM Apartments WHERE ApartmentId = :apartmentid";
        $q = $this->db->prepare($sql);
        $q->bindValue(":apartmentid", $apartmentid, PDO::PARAM_INT);
        $q->execute();
    }
}

?>
