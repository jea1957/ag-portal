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
    public function getParkingsPersonsFiltered($filter) {
        $parkingid  = "%" . $filter["parkingid"] . "%";
        $depot      = $filter["depot"];
        $charger    = $filter["charger"];
        $owner      = $filter["owner"];
        $extern     = $filter["extern"];
        $tenant     = $filter["tenant"];
        $historical = $filter["historical"];

        $sql = "SELECT PersonId, Name, Email FROM Persons WHERE PersonId IN ".
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
        $rows = $q->fetchAll();

        $result = array();
        foreach($rows as $row) {
            $tmp = $this->readPPerson($row);
//            array_push($result, $this->readPPerson($row));
            $result[$tmp->personid] = $tmp;
        }
        return $result;
    }


}

?>
