<?php

class Setting {
    public $smtp_username;
    public $smtp_password;
}

class Settings {

    protected $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    private function read($row) {
        $result = new Setting();
        $result->smtp_username = $row["SMTP_Username"];
        $result->smtp_password = $row["SMTP_Password"];
        return $result;
    }

    public function get() {
        $sql = "SELECT * FROM Settings WHERE SettingsId = :settingsid";
        $q = $this->db->prepare($sql);
        $q->bindValue(":settingsid", 1, PDO::PARAM_INT);
        $q->execute();
        $rows = $q->fetchAll();
        return array($this->read($rows[0]));
    }

    public function update($data) {
        $sql = "UPDATE Settings SET SMTP_Username = :smtp_username, SMTP_Password = :smtp_password WHERE SettingsId = :settingsid";
        $q = $this->db->prepare($sql);
        $q->bindValue(":settingsid", 1, PDO::PARAM_INT);
        $q->bindValue(":smtp_username", $data["smtp_username"]);
        $q->bindValue(":smtp_password", $data["smtp_password"]);
        $q->execute();
    }

}

?>
