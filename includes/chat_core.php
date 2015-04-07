<?php
class Core {
    protected $db, $result;
    private $rows;
    
    public function __construct() {
        $this->db = new mysqli('localhost', 'root', '', 'izcms');
    }
    
    public function query($sql) {
        $this->result = $this->db->query($sql);
    }
    
    public function rows() {
        for ($x = 1; $x <= $this->db->affected_rows; $x++) {
            $this->rows[] = $this->result->fetch_assoc();
        }
        return $this->rows;
    }
    
}

class Chat extends Core {
    public function fetch_messages() {
        $this->query("
            SELECT chat.message, chat.timestamp, users.first_name, users.user_id
            FROM chat INNER JOIN users
            ON chat.sender_id = users.user_id
            ORDER BY chat.timestamp DESC
        ");
        return $this->rows();
    }
    
    public function throw_messages($sender_id, $message) {
        $this->query("
            INSERT INTO chat (sender_id, message, timestamp)
            VALUES (" . $sender_id . ", '" . $this->db->real_escape_string(htmlentities($message)) . "', CURRENT_TIMESTAMP())
        ");
        
    }
}
    
    
?>