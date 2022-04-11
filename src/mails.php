<?php

class MailCheck {
    public $checkid;
    public $checked;
    public $remote;
    public $queued;
    public $lastsend;
    public $sendstatus;
}

class Mail {
    public $mailid;
    public $accountid;
    public $accountname;
    public $subject;
    public $body;
    public $state;
    public $sent;
    public $modified;
}

class MailRecipient {
    public $mailid;
    public $name;
    public $email;
}

class MailAttachmentData {
    public $mailid;
    public $id;
    public $name;
    public $type;
    public $size;
    public $modified;
}

class MailAttachmentFile {
    public $mailid;
    public $id;
    public $name;
    public $type;
    public $file;
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
        $result->sent      = $row["Sent"];
        $result->modified  = $row["Modified"];
        return $result;
    }

    private function readMailRecipient($row) {
        $result = new MailRecipient();
        $result->mailid = $row["MailId"];
        $result->name   = $row["Name"];
        $result->email  = $row["Email"];
        return $result;
    }

    private function readAttachmentData($row) {
        $result = new MailAttachmentData();
        $result->mailid = $row["MailId"];
        $result->id     = $row["Id"];
        $result->name   = $row["Name"];
        $result->type   = $row["Type"];
        $result->size   = $row["Size"];
        return $result;
    }

    private function readAttachmentFile($row) {
        $result = new MailAttachmentFile();
        $result->mailid = $row["MailId"];
        $result->id     = $row["Id"];
        $result->file   = $row["File"];
        $result->name   = $row["Name"];
        $result->type   = $row["Type"];
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

// ATTACHMENTS:
    private function getAttachmentFiles($mailid) {
        $sql = "SELECT MailId, Id, Name, Type, File FROM MailAttachments WHERE MailId = :mailid";
        $q = $this->db->prepare($sql);
        $q->bindValue(":mailid", $mailid, PDO::PARAM_INT);
        $q->execute();
        $rows = $q->fetchAll();
        $result = array();
        foreach($rows as $row) {
            array_push($result, $this->readAttachmentFile($row));
        }
        return $result;
    }

    public function getAttachments($mailid) {
        $sql = "SELECT MailId, Id, Name, Type, Size, Modified FROM MailAttachments WHERE MailId = :mailid";
        $q = $this->db->prepare($sql);
        $q->bindValue(":mailid", $mailid, PDO::PARAM_INT);
        $q->execute();
        $rows = $q->fetchAll();
        $result = array();
        foreach($rows as $row) {
            array_push($result, $this->readAttachmentData($row));
        }
        return $result;
    }

    public function addAttachment($data) {
        $sql = "INSERT INTO MailAttachments (MailId, Name, Type, Size, File) ".
               "VALUES (:mailid, :name, :type, :size, :file)";
        $q = $this->db->prepare($sql);
        $fp = fopen($data["file"], 'rb');
        if (!$fp) {
            error_log("Could not open " . $data["file"]);
            return false;
        }
        $q->bindValue(":mailid", $data["mailid"], PDO::PARAM_INT);
        $q->bindValue(":name",   $data["name"]);
        $q->bindValue(":type",   $data["type"]);
        $q->bindValue(":size",   $data["size"]);
        $q->bindParam(":file",   $fp,             PDO::PARAM_LOB);
        $q->execute();
    }

    public function getAttachmentFile($id) {
        $sql = "SELECT MailId, Id, Name, Type, File FROM MailAttachments WHERE Id = :id";
        $q = $this->db->prepare($sql);
        $q->bindValue(":id", $id, PDO::PARAM_INT);
        $q->execute();
        $row = $q->fetch();
        if ($row) {
            return $this->readAttachmentFile($row);
        } else {
            return false;
        }
    }

    public function delAttachment($mailid, $id) {
        $sql = "DELETE FROM MailAttachments WHERE MailId = :mailid AND (Id = :id OR :id2 = 0)";
        $q = $this->db->prepare($sql);
        $q->bindValue(":mailid", $mailid, PDO::PARAM_INT);
        $q->bindValue(":id",     $id,     PDO::PARAM_INT);
        $q->bindValue(":id2",    $id,     PDO::PARAM_INT);
        $q->execute();
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

    public function queueDraft($data) { // Change state to Sending(2) and let other do the actual send
        $sql = "UPDATE Mails SET Subject = :subject, Body = :body, State = 2, Sent = CURRENT_TIMESTAMP ".
               " WHERE MailId = :mailid AND AccountId = :accountid AND State = 1";
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
        $sql = "SELECT * FROM Mails WHERE :state1 IS NULL or State = :state2 ORDER BY Sent DESC";
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

    public function delMail($data) {
        if ($data["state"] == 4) { // Mail already in trash - delete it
            $sql = "DELETE FROM Mails WHERE MailId = :mailid";
        } else { // Move mail to trash
            $sql = "UPDATE Mails SET State = 4 WHERE MailId = :mailid";
        }
        $q = $this->db->prepare($sql);
        $q->bindValue(":mailid", $data["mailid"], PDO::PARAM_INT);
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
            $res = $this->readMailRecipient($row);
            array_push($result, $res);
        }
        return $result;
    }

    private function getMailFromQueue() {
        $row = $this->db->query("SELECT * FROM Mails WHERE State = 2")->fetch();
        if ($row) {
            return $this->readMail($row);
        } else {
            return false;
        }
    }

    private function markAsSent($mailid) { // Change state from Sending(2) to Sent(3)
        $sql = "UPDATE Mails SET State = 3, Sent = CURRENT_TIMESTAMP ".
               " WHERE MailId = :mailid AND State = 2";
        $q = $this->db->prepare($sql);
        $q->bindValue(":mailid", $mailid, PDO::PARAM_INT);
        $q->execute();
    }

    // Returns:
    // -3: Send error
    // -2: Too many recipients
    // -1: Too early (last mail sent less tham 5 mminutes ago)
    //  0: No mails in queue or no recipients
    //  1..n: Number of recipients
    public function sendMail() {
        global $db_contact, $db_cname;

        // Register that we have been called
        $remote = '';
        if (isset($_SERVER['REMOTE_USER'])) {
            $remote .= $_SERVER['REMOTE_USER'] . ':';
        }
        $remote .= $_SERVER['REMOTE_ADDR'] . ':' . $_SERVER['REMOTE_PORT'];
        $sql = "UPDATE MailCheck SET Checked = CURRENT_TIMESTAMP, Remote = ? WHERE CheckId = 1";
        $this->db->prepare($sql)->execute([$remote]);

        // Check if there is anything to send
        $count = $this->db->query("SELECT COUNT(*) FROM Mails WHERE State = 2")->fetchColumn();
        $sql = "UPDATE MailCheck SET Queued = ? WHERE CheckId = 1";
        $this->db->prepare($sql)->execute([$count]);
        if ($count == 0) {
            $this->db->exec("UPDATE MailCheck SET SendStatus = 0 WHERE CheckId = 1");
            return 0;
        }

        // Check if enough time has passed
        $row = $this->db->query("SELECT LastSend FROM MailCheck WHERE CheckId = 1")->fetch();
        $dtA = new DateTime($row["LastSend"]);
        $dtA->add(new DateInterval('PT5M')); // Add 5 minutes
        $dtB = new DateTime('NOW');
        if ($dtA >= $dtB) { // Don't send if lastsend is less then 5 minutes ago
            $this->db->exec("UPDATE MailCheck SET SendStatus = -1 WHERE CheckId = 1");
            return -1;
        }

        $mail = $this->getMailFromQueue();
        if (!$mail) {
            $this->db->exec("UPDATE MailCheck SET SendStatus = 0 WHERE CheckId = 1");
            return 0;
        }
        $rcp = $this->getMailRecipients($mail->mailid);
        $num_rcp = count($rcp);
        if ($num_rcp > 255) {
            $this->db->exec("UPDATE MailCheck SET SendStatus = -2 WHERE CheckId = 1");
            return -2;
        }
        if ($num_rcp == 0) {
            $rcp = null; // Send to zero bcc recipients
        }
        $body = _L('msg_header') . $mail->body . _L('msg_footer');
        $att = $this->getAttachmentFiles($mail->mailid);

        $result = send_email($db_contact, $db_cname, $db_contact, $db_cname, $mail->subject, $body, $rcp, $att);
        if (isset($result['error'])) {
            error_log("send error: " . $result["error"]);
            $this->db->exec("UPDATE MailCheck SET SendStatus = -3 WHERE CheckId = 1");
            return -3;
        }
        $this->markAsSent($mail->mailid);
        $count = $this->db->query("SELECT COUNT(*) FROM Mails WHERE State = 2")->fetchColumn();
        $sql = "UPDATE MailCheck SET LastSend = CURRENT_TIMESTAMP, Queued = ? WHERE CheckId = 1";
        $this->db->prepare($sql)->execute([$count]);

        $sql = "UPDATE MailCheck SET SendStatus = ? WHERE CheckId = 1";
        $this->db->prepare($sql)->execute([$num_rcp]);
        return $num_rcp;
    }

// MAIL CHECK:
    private function mailCheck($row) {
        $result = new MailCheck();
        $result->checkid    = $row["CheckId"];
        $result->checked    = $row["Checked"];
        $result->remote     = $row["Remote"];
        $result->queued     = $row["Queued"];
        $result->lastsend   = $row["LastSend"];
        $result->sendstatus = $row["SendStatus"];
        return $result;
    }

    public function getMailCheck() {
        $row = $this->db->query("SELECT * FROM MailCheck WHERE CheckId = 1")->fetch();
        return $this->mailCheck($row);
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
        $ccharger   = $filter["ccharger"];
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
               "   (:charger1 IS NULL or Charger = :charger2) AND ".
               "   (:ccharger1 IS NULL or CCharger = :ccharger2)))";

        $q = $this->db->prepare($sql);
        $q->bindValue(":parkingid",  $parkingid);
        $q->bindValue(":depot1",     $depot,    PDO::PARAM_BOOL);
        $q->bindValue(":depot2",     $depot,    PDO::PARAM_BOOL); // Cannot reuse name in WHERE
        $q->bindValue(":charger1",   $charger,  PDO::PARAM_BOOL);
        $q->bindValue(":charger2",   $charger,  PDO::PARAM_BOOL); // Cannot reuse name in WHERE
        $q->bindValue(":ccharger1",  $ccharger, PDO::PARAM_BOOL);
        $q->bindValue(":ccharger2",  $ccharger, PDO::PARAM_BOOL); // Cannot reuse name in WHERE
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
        $sql = "SELECT Name, Email FROM Accounts WHERE Role = :role AND (State = 1 OR State = 2)";
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
