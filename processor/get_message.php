<?php
    require('../includes/chat_core.php');
    if(isset($_POST['method']) === true && empty($_POST['method']) === false) {
        
        $chat = new Chat;
        //echo "<pre>".print_r($chat->fetch_messages())."</pre>";
        $method = trim($_POST['method']);
                
        if ($method === 'fetch') {
            $messages = $chat->fetch_messages();
            
            if (empty($messages) === true) {
                echo 'There are currently no messages.';
            } else {
                foreach ($messages as $message) {
                    ?>
                    <div class="message">
                        <p><a href="#"><?php echo $message['first_name']; ?></a> said<br />
                        <?php echo $message['message']; ?><br />
                        at <?php echo $message['timestamp']; ?>
                        </p>                                                
                    </div>
                    <?php
                }
            }
        }
    }
?>