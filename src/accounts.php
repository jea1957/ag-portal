<?php

function random_password($length) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!-.[]?*()';
    //Create a blank string.
    $password = '';
    //Get the index of the last character in our $characters string.
    $characterListLength = mb_strlen($characters, '8bit') - 1;
    //Loop from 1 to the $length that was specified.
    foreach(range(1, $length) as $i){
        $password .= $characters[random_int(0, $characterListLength)];
    }
    return $password;
}

function password_ok($password) {
//    $length = mb_strlen($password, '8bit'); // Test with æøå
    $length = mb_strlen($password, '8bit');
    if ($length < 8) {
        return false;
    }
    return true;
}

class Account {
    public $accountid;
    public $name;
    public $email;
    public $password;
    public $otp;
    public $state;
    public $role;
    public $lang;
    public $activity;
}

class Accounts {

    protected $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    private function read($row) {
        $result = new Account();
        $result->accountid  = $row["AccountId"];
        $result->name       = $row["Name"];
        $result->email      = $row["Email"];
        $result->password   = $row["Password"];
        $result->otp        = $row["OTP"];
        $result->state      = $row["State"];
        $result->role       = $row["Role"];
        $result->lang       = $row["Language"];
        $result->activity   = $row["Activity"];
        return $result;
    }

    public function getByEmail($email) {
        $sql = "SELECT * FROM Accounts WHERE Email = :email";
        $q = $this->db->prepare($sql);
        $q->bindValue(":email", $email);
        $q->execute();
        $row = $q->fetch();
        return is_array($row) ? $this->read($row) : null;
    }

    public function getById($accountid) {
        $sql = "SELECT * FROM Accounts WHERE AccountId = :accountid";
        $q = $this->db->prepare($sql);
        $q->bindValue(":accountid", $accountid, PDO::PARAM_INT);
        $q->execute();
        $rows = $q->fetchAll();
        return $this->read($rows[0]);
    }

    public function getAll() {
        $sql = "SELECT * FROM Accounts";
        $q = $this->db->prepare($sql);
        $q->execute();
        $rows = $q->fetchAll();
        $result = array();
        foreach($rows as $row) {
            $res = $this->read($row);
            $res->password = ""; // Never return password
            array_push($result, $res);
        }
        return $result;
    }

    public function insert($data) {
        $sql = "INSERT INTO Accounts (Name, Email, Password, OTP, State, Role, Language) ".
               "VALUES (:name, :email, :password, :otp, :state, :role, :lang)";
        $q = $this->db->prepare($sql);
        $q->bindValue(":name",     $data["name"]);
        $q->bindValue(":email",    $data["email"]);
        $q->bindValue(":password", password_hash($data["otp"], PASSWORD_DEFAULT));
        $q->bindValue(":otp",      $data["otp"]);
        $q->bindValue(":state",    $data["state"],    PDO::PARAM_INT);
        $q->bindValue(":role",     $data["role"],     PDO::PARAM_INT);
        $q->bindValue(":lang",     $data["lang"],     PDO::PARAM_INT);
        $q->execute();
        return $this->getById($this->db->lastInsertId());
    }

    public function update($data) {
        $sql = "UPDATE Accounts SET Name = :name, State = :state, Role = :role, Language = :lang ".
               "WHERE AccountId = :accountid";
        $q = $this->db->prepare($sql);
        $q->bindValue(":accountid", $data["accountid"], PDO::PARAM_INT);
        $q->bindValue(":name",      $data["name"]);
        $q->bindValue(":state",     $data["state"],     PDO::PARAM_INT);
        $q->bindValue(":role",      $data["role"],      PDO::PARAM_INT);
        $q->bindValue(":lang",      $data["lang"],      PDO::PARAM_INT);
        $q->execute();
    }

    public function remove($accountid) {
        $sql = "DELETE FROM Accounts WHERE AccountId = :accountid";
        $q = $this->db->prepare($sql);
        $q->bindValue(":accountid", $accountid, PDO::PARAM_INT);
        $q->execute();
    }

    public function set_password($email, $password, $state) {
        $sql = "UPDATE Accounts SET Password = :password, OTP = '', State = :state WHERE Email = :email";
        $q = $this->db->prepare($sql);
        $q->bindValue(":email",    $email);
        $q->bindValue(":password", password_hash($password, PASSWORD_DEFAULT));
        $q->bindValue(":state",    $state, PDO::PARAM_INT);
        $q->execute();
    }

    public function update_activity($accountid) {
        $sql = "UPDATE Accounts SET Activity = CURRENT_TIMESTAMP WHERE AccountId = :accountid";
        $q = $this->db->prepare($sql);
        $q->bindValue(":accountid", $accountid, PDO::PARAM_INT);
        $q->execute();
    }

    public function get_activity($accountid) {
        $result = $this->getById($accountid);
        return $result->activity;
    }
}

?>
