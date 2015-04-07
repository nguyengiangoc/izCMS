<?php
    session_start();
    require('../includes/chat_core.php');
    if(isset($_POST['method']) === true && empty($_POST['method']) === false) {
        
        $chat = new Chat;
        //echo "<pre>".print_r($chat->fetch_messages())."</pre>";
        $method = trim($_POST['method']);
        $sender_id = $_SESSION['uid'];
        if ($method === 'throw') {
            $message = trim($_POST['content']);
            if(empty($message) === false) {
                $chat->throw_messages($_SESSION['sender'], $message);
            }
        }
        
    }

?>