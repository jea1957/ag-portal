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
        $sql = "SELECT * FROM Events";
        $q = $this->db->prepare($sql);
        $q->execute();
        $rows = $q->fetchAll();
        foreach($rows as $row) {
            $result[] = $this->read($row);
        }
        return $result;

}

}

?>
