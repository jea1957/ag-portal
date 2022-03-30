<?php

class MailCheck {
    public $checkid;
    public $mailssent;
    public $info;
    public $modified;
}

class Mail {
    public $mailid;
    public $accountid;
    public $accountname;
    public $subject;
    public $body;
    public $state;
    public $modified;
}

class MailRecipients {
    public $mailid;
    public $name;
    public $email;
}

class Mails {

    protected $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    private function readMail($row) {
        $result = new Mail();
        $result->mailid    = $row["MailId"];
        $result->accountid = $row["AccountId"];
        $result->subject   = $row["Subject"];
        $result->body      = $row["Body"];
        $result->state     = $row["State"];
        $result->modified  = $row["Modified"];
        return $result;
    }

    private function readMailRecipients($row) {
        $result = new MailRecipients();
        $result->mailid = $row["MailId"];
        $result->name   = $row["Name"];
        $result->email  = $row["Email"];
        return $result;
    }

    private function getMailById($mailid) {
        $sql = "SELECT * FROM Mails WHERE MailId = :mailid";
        $q = $this->db->prepare($sql);
        $q->bindValue(":mailid", $mailid, PDO::PARAM_INT);
        $q->execute();
        $row = $q->fetch();
        return $this->readMail($row);
    }

// DRAFTS:
    private function newDraft($accountid) {
        $sql = "INSERT INTO Mails (AccountId) VALUES (:accountid)";
        $q = $this->db->prepare($sql);
        $q->bindValue(":accountid", $accountid, PDO::PARAM_INT);
        $q->execute();
        return $this->getMailById($this->db->lastInsertId());
    }

    public function getDraft($accountid) {
        $sql = "SELECT * FROM Mails WHERE AccountId  = :accountid AND State = 1";
        $q = $this->db->prepare($sql);
        $q->bindValue(":accountid", $accountid, PDO::PARAM_INT);
        $q->execute();
        $row = $q->fetch();
        if ($row) {
            return $this->readMail($row);
        } else {
            return $this->newDraft($accountid);
        }
    }

    public function updDraft($data) {
        $sql = "UPDATE Mails SET Subject = :subject, Body = :body WHERE MailId = :mailid AND AccountId = :accountid AND State = 1";
        $q = $this->db->prepare($sql);
        $q->bindValue(":mailid",    $data["mailid"],    PDO::PARAM_INT);
        $q->bindValue(":accountid", $data["accountid"], PDO::PARAM_INT);
        $q->bindValue(":subject",   $data["subject"]);
        $q->bindValue(":body",      $data["body"]);
        $q->execute();
        return $this->getMailById($data["mailid"]);
    }

    public function prepareDraft($data) { // Change state to Sending(2) and let other do the actual send
        $sql = "UPDATE Mails SET Subject = :subject, Body = :body, State = 2 WHERE MailId = :mailid AND AccountId = :accountid AND State = 1";
        $q = $this->db->prepare($sql);
        $q->bindValue(":mailid",    $data["mailid"],    PDO::PARAM_INT);
        $q->bindValue(":accountid", $data["accountid"], PDO::PARAM_INT);
        $q->bindValue(":subject",   $data["subject"]);
        $q->bindValue(":body",      $data["body"]);
        $q->execute();
        return $this->newDraft($data["accountid"]); // Return a fresh draft
    }

// MAILS:
    public function getMails($state) {
        $sql = "SELECT * FROM Mails WHERE :state1 IS NULL or State = :state2 ORDER BY Modified DESC";
        $q = $this->db->prepare($sql);
        $q->bindValue(":state1", $state, PDO::PARAM_INT);
        $q->bindValue(":state2", $state, PDO::PARAM_INT);
        $q->execute();
        $rows = $q->fetchAll();
        $result = array();
        foreach($rows as $row) {
            // Sneak in account name
            $sql = "SELECT Name FROM Accounts WHERE AccountId = ?";
            $q = $this->db->prepare($sql);
            $q->execute([$row["AccountId"]]);
            $res = $this->readMail($row);
            $res->accountname = $q->fetch()["Name"];
            array_push($result, $res);
        }
        return $result;
    }

    public function delMail($mailid) {
        $sql = "DELETE FROM Mails WHERE MailId = :mailid";
        $q = $this->db->prepare($sql);
        $q->bindValue(":mailid",    $mailid,    PDO::PARAM_INT);
        $q->execute();
    }

    public function getMailRecipients($mailid) {
        $sql = "SELECT * FROM MailRecipients WHERE MailId = :mailid";
        $q = $this->db->prepare($sql);
        $q->bindValue(":mailid", $mailid, PDO::PARAM_INT);
        $q->execute();
        $rows = $q->fetchAll();
        $result = array();
        foreach($rows as $row) {
            $res = $this->readMailRecipients($row);
            array_push($result, $res);
        }
        return $result;
    }

// MAIL CHECK:
    private function mailCheck($row) {
        $result = new MailCheck();
        $result->checkid   = $row["CheckId"];
        $result->mailssent = $row["MailsSent"];
        $result->info      = $row["Info"];
        $result->modified  = $row["Modified"];
        return $result;
    }

    public function getMailCheck() {
        $sql = "SELECT * FROM MailCheck WHERE CheckId = 1";
        $q = $this->db->prepare($sql);
        $q->execute();
        $rows = $q->fetchAll();
        return $this->mailCheck($rows[0]);
    }

// RECIPIENTS FROM TABS:
    private function addRcpFromApartments(&$rcp, $filter) {
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

        $sql = "SELECT Name, Email FROM Persons WHERE NoMails IS FALSE AND PersonId IN ".
               " (SELECT PersonId FROM PersonsApartments WHERE ".
               " ((:owner = 1 AND Relation = 1) OR (:extern = 1 AND Relation = 2) OR (:tenant = 1 AND Relation = 3)) AND ".
               " (:historical = 1 OR Stopped = '0000-00-00' OR Stopped >= CURDATE()) AND Id IN ".
               "  (SELECT ApartmentId FROM Apartments WHERE ApartmentId LIKE :apartmentid AND Number LIKE :number AND ".
                   "Floor LIKE :floor AND Side LIKE :side AND Type LIKE :type AND ".
                   "Size LIKE :size AND Reduction LIKE :reduction AND ".
                   "TapShares LIKE :tapshares AND Shafts LIKE :shafts))";

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
        while ($row = $q->fetch()) {
            $rcp[$row["Email"]] = $row["Name"];
        }
    }

    private function addRcpFromParkings(&$rcp, $filter) {
        $parkingid  = "%" . $filter["parkingid"] . "%";
        $depot      = $filter["depot"];
        $charger    = $filter["charger"];
        $owner      = $filter["owner"];
        $extern     = $filter["extern"];
        $tenant     = $filter["tenant"];
        $historical = $filter["historical"];

        $sql = "SELECT Name, Email FROM Persons WHERE NoMails IS FALSE AND PersonId IN ".
               " (SELECT PersonId FROM PersonsParkings WHERE ".
               " ((:owner = 1 AND Relation = 1) OR (:extern = 1 AND Relation = 2) OR (:tenant = 1 AND Relation = 3)) AND ".
               " (:historical = 1 OR Stopped = '0000-00-00' OR Stopped >= CURDATE()) AND Id IN ".
               "  (SELECT ParkingId FROM Parkings WHERE ParkingId LIKE :parkingid AND ".
               "   (:depot1 IS NULL or Depot = :depot2) AND ".
               "   (:charger1 IS NULL or Charger = :charger2)))";

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
        while ($row = $q->fetch()) {
            $rcp[$row["Email"]] = $row["Name"];
        }
    }

    private function addRcpFromDepots(&$rcp, $filter) {
        $depotid    = "%" . $filter["depotid"] . "%";
        $number     = "%" . $filter["number"]  ."%";
        $historical = $filter["historical"];

        $sql = "SELECT Name, Email FROM Persons WHERE NoMails IS FALSE AND PersonId IN ".
               " (SELECT PersonId FROM PersonsDepots WHERE ".
               " (:historical = 1 OR Stopped = '0000-00-00' OR Stopped >= CURDATE()) AND Id IN ".
               "  (SELECT DepotId FROM Depots WHERE DepotId LIKE :depotid AND Number LIKE :number))";

        $q = $this->db->prepare($sql);
        $q->bindValue(":depotid",    $depotid);
        $q->bindValue(":number",     $number);
        $q->bindValue(":historical", $historical);
        $q->execute();
        while ($row = $q->fetch()) {
            $rcp[$row["Email"]] = $row["Name"];
        }
    }

    private function addRcpFromPersons(&$rcp, $filter) {
        $name       = "%" . $filter["name"] . "%";
        $address    = "%" . $filter["address"] . "%";
        $email      = "%" . $filter["email"] . "%";
        $phone      = "%" . $filter["phone"] . "%";

        $sql = "SELECT Name, Email FROM Persons WHERE Name LIKE :name AND Address LIKE :address AND ".
               "Email LIKE :email AND Phone LIKE :phone AND NoMails IS FALSE";

        $q = $this->db->prepare($sql);
        $q->bindValue(":name",        $name);
        $q->bindValue(":address",     $address);
        $q->bindValue(":email",       $email);
        $q->bindValue(":phone",       $phone);
        $q->execute();
        while ($row = $q->fetch()) {
            $rcp[$row["Email"]] = $row["Name"];
        }
    }

    private function addRcpFromAccounts(&$rcp, $role) {
        $sql = "SELECT Name, Email FROM Accounts WHERE Role = :role";
        $q = $this->db->prepare($sql);
        $q->bindValue(":role", $role, PDO::PARAM_INT);
        $q->execute();
        while ($row = $q->fetch()) {
            $rcp[$row["Email"]] = $row["Name"];
        }
    }

    private function delRcp($mailid) {
        $sql = "DELETE FROM MailRecipients WHERE MailId = :mailid";
        $q = $this->db->prepare($sql);
        $q->bindValue(":mailid", $mailid, PDO::PARAM_INT);
        $q->execute();
        return $q->rowCount();
    }

    private function addRcp($mailid, $rcp) {
        $sql = "INSERT INTO MailRecipients (MailId, Name, Email) VALUES (:mailid, :name, :email)";
        foreach($rcp as $email => $name) {
            $q = $this->db->prepare($sql);
            $q->bindValue(":mailid", $mailid, PDO::PARAM_INT);
            $q->bindValue(":name",   $name);
            $q->bindValue(":email",  $email);
            $q->execute();
        }
    }

    public function setMailRecipients($filter) {
        $rcp = []; // Start with an empty array

        // Get recipients from each group
        if (isset($filter["apartments"])) {
            $this->addRcpFromApartments($rcp, $filter["apartments"]);
        }
        if (isset($filter["parkings"])) {
            $this->addRcpFromParkings($rcp, $filter["parkings"]);
        }
        if (isset($filter["depots"])) {
            $this->addRcpFromDepots($rcp, $filter["depots"]);
        }
        if (isset($filter["persons"])) {
            $this->addRcpFromPersons($rcp, $filter["persons"]);
        }
        if (isset($filter["board"]) and $filter["board"]) {
            $this->addRcpFromAccounts($rcp, 2);
        }
        if (isset($filter["caretaker"]) and $filter["caretaker"]) {
            $this->addRcpFromAccounts($rcp, 3);
        }
        if (isset($filter["administrator"]) and $filter["administrator"]) {
            $this->addRcpFromAccounts($rcp, 4);
        }

        // Delete old recipients
        $num_deleted = $this->delRcp($filter["mailid"]);

        if (count($rcp)) {
            // Add recipients in db and return them
            $this->addRcp($filter["mailid"], $rcp);
            return $rcp;
        }
        return false;
    }

}

?>
