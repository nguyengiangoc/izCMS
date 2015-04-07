<?php include('includes/header-chat.php');?>
<?php include('includes/mysqli_connect.php');?>
<?php require('includes/chat_core.php');?>
<?php include('includes/function.php');?>
<?php include('includes/sidebar-a.php'); ?>

<?php
is_logged_in();
$_SESSION['sender'] = (isset($_SESSION['uid']) === true) ? (int)$_SESSION['uid'] : 0;
//echo $_SESSION['uid'];
$chat = new Chat();

?>
<div id="content">

    <div class="chat">
        <div class="message-box"></div>
        <textarea class="entry" placeholder="Type here and hit Submit"></textarea>
        
    </div>
    
    <!--  -->
    <script src="http://code.jquery.com/jquery-1.8.2.min.js"></script>
    <script src="js/chat.js"></script>

</div>

<?php include('includes/sidebar-b.php');?>
<?php include('includes/footer.php'); ?>