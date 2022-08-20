<?php

class Event {
    public $eventid;
    public $type;
    public $title;
    public $note;
    public $start;
    public $end;
    public $duration;
    public $isallday;
    public $isrecurring;
    public $rrule;
    public $modified;
}

class EventReminder {
    public $eventid;
    public $id;
    public $delta;
    public $receivers;
    public $lastsent;
    public $modified;
}

class Events {

    protected $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    private function read($row) {
        $result = new Event();
        $result->eventid     = $row["EventId"];
        $result->type        = $row["Type"];
        $result->title       = $row["Title"];
        $result->note        = $row["Note"];
        $result->start       = $row["Start"];
        $result->end         = $row["End"];
        $result->duration    = $row["Duration"];
        $result->isallday    = $row["IsAllDay"];
        $result->isrecurring = $row["IsRecurring"];
        $result->rrule       = $row["RRule"];
        $result->modified    = $row["Modified"];
        return $result;
    }

    private function readReminder($row) {
        $result = new EventReminder();
        $result->eventid   = $row["EventId"];
        $result->id        = $row["Id"];
        $result->delta     = $row["Delta"];
        $result->receivers = $row["Receivers"];
        $result->lastsent  = $row["LastSent"];
        $result->modified  = $row["Modified"];
        return $result;
    }

    private function getById($eventid) {
        $sql = "SELECT * FROM Events WHERE EventId = :eventid";
        $q = $this->db->prepare($sql);
        $q->bindValue(":eventid", $eventid, PDO::PARAM_INT);
        $q->execute();
        $row = $q->fetch();
        return $this->read($row);
    }

    private function getReminderById($id) {
        $sql = "SELECT * FROM EventReminders WHERE Id = :id";
        $q = $this->db->prepare($sql);
        $q->bindValue(":id", $id, PDO::PARAM_INT);
        $q->execute();
        $row = $q->fetch();
        return $this->readReminder($row);
    }

// EVENTS:
    public function getRange($start, $end) {
        $sql = "SELECT * FROM Events WHERE :start < End AND :end >= Start";
        $q = $this->db->prepare($sql);
        $q->bindValue(":start", $start);
        $q->bindValue(":end",   $end);
        $q->execute();
        $rows = $q->fetchAll();
        $result = [];
        foreach($rows as $row) {
            $result[] = $this->read($row);
        }
        return $result;
    }

    public function insertEvent($data) {
        //error_log("./src/events.php insertEvent: " . print_r($data, 1));
        $sql = "INSERT INTO Events (Type, Title, Note, Start, End, Duration, IsAllDay, IsRecurring, RRule) ".
               "VALUES (:type, :title, :note, :start, :end, :duration, :isallday, :isrecurring, :rrule)";
        $q = $this->db->prepare($sql);
        $q->bindValue(":type",        $data["type"],        PDO::PARAM_INT);
        $q->bindValue(":title",       $data["title"]);
        $q->bindValue(":note",        $data["note"]);
        $q->bindValue(":start",       $data["start"]);
        $q->bindValue(":end",         $data["end"]);
        $q->bindValue(":duration",    $data["duration"],    PDO::PARAM_INT);
        $q->bindValue(":isallday",    $data["isallday"],    PDO::PARAM_BOOL);
        $q->bindValue(":isrecurring", $data["isrecurring"], PDO::PARAM_BOOL);
        $q->bindValue(":rrule",       $data["rrule"]);
        $q->execute();
        return $this->getById($this->db->lastInsertId());
    }

    public function updateEvent($data) {
        //error_log("./src/events.php updateEvent: " . print_r($data, 1));
        $sql = "UPDATE Events SET Type = :type, Title = :title, Note = :note, Start = :start, End = :end, ".
               "Duration = :duration, IsAllDay = :isallday, IsRecurring = :isrecurring, RRule = :rrule ".
               "WHERE EventId = :eventid";
        $q = $this->db->prepare($sql);
        $q->bindValue(":eventid",     $data["eventid"],     PDO::PARAM_INT);
        $q->bindValue(":type",        $data["type"],        PDO::PARAM_INT);
        $q->bindValue(":title",       $data["title"]);
        $q->bindValue(":note",        $data["note"]);
        $q->bindValue(":start",       $data["start"]);
        $q->bindValue(":end",         $data["end"]);
        $q->bindValue(":duration",    $data["duration"],    PDO::PARAM_INT);
        $q->bindValue(":isallday",    $data["isallday"],    PDO::PARAM_BOOL);
        $q->bindValue(":isrecurring", $data["isrecurring"], PDO::PARAM_BOOL);
        $q->bindValue(":rrule",       $data["rrule"]);
        $q->execute();
        return $this->getById($data["eventid"]);
    }

    public function removeEvent($eventid) {
        $sql = "DELETE FROM Events WHERE EventId = :eventid";
        $q = $this->db->prepare($sql);
        $q->bindValue(":eventid", $eventid, PDO::PARAM_INT);
        $q->execute();
        return $q->rowCount();
    }
}

?>
