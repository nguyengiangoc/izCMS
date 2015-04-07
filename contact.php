<?php 
    $title = 'Contact Us';
    include('includes/header.php');
    include('includes/mysqli_connect.php');    
    include('includes/function.php');
    include('includes/sidebar-a.php');
?>
<div id="content">
    <?php
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $errors = array();
            
            $clean = array_map('clean_email', $_POST);
            //The array_map() function sends each value of an array to a user-made function, 
            //and returns an array with new values, given by the user-made function.
            //cho tat ca cac gia tri trong array post chay qua ham clean email
            
            if(empty($clean['name'])) {
                $errors[] = 'name';
            }
            
            if(!preg_match('/^[a-zA-Z0-9_.-]+@[\w.-]+\.+[a-zA_Z]{2,6}$/', $clean['email'])) {
                //co cach viet khac cho a-zA-Z0-9_ la \w
                $errors[] = 'email';
            }
            if(empty($clean['comment'])) {
                $errors[] = 'comment';
            }
            if(isset($_POST['captcha']) && trim($_POST['captcha']) != $_SESSION['q']['answer']) {
            $errors[] = "wrong";
            }
            if(!empty($_POST['website'])) {
            redirect_to('thankyou.html');
            exit;
            }
            if(empty($errors)) {
                $body_old = "Name: {$clean['name']} \n\n Content: \n".strip_tags($clean['comment']);
                $body_new = wordwrap($body_old, 70);
                if (mail('nguyen.gia.ngoc.2710@gmail.com','Contact form submission', $body_new, 'FROM localhost')) {
                    echo "<p class = 'success'>Thank you for contacting.</p>";
                    $_POST = array();   
                } else {
                    echo "<p class = 'warning'>The email can not be sent.</p>";
                }
            } else {
                echo "<p class = 'warning'>Please fill in all required fields.</p>";
            }
        }
    ?>
    <form id="contact" action="" method="post">
    <fieldset>
    	<legend>Contact</legend>
            <div>
                <label for="Name">Your Name: <span class="required">*</span>
                    <?php if(isset($errors) && in_array('name',$errors)) { echo "<span class='warning'>Please enter your name.</span>";}?>
                </label>
                <input type="text" name="name" id="name" value="<?php if(isset($_POST['name'])) {echo htmlentities($_POST['name'], ENT_COMPAT, 'UTF-8');} ?>" size="20" maxlength="80" tabindex="1" />
            </div>
        	<div>
                <label for="email">Email: <span class="required">*</span>
                <?php if(isset($errors) && in_array('email',$errors)) {echo "<span class='warning'>Please enter your email.</span>";} ?>
                </label>
                <input type="text" name="email" id="email" value="<?php if(isset($_POST['email'])) {echo htmlentities($_POST['email'], ENT_COMPAT, 'UTF-8');} ?>" size="20" maxlength="80" tabindex="2" />
            </div>
            <div>
                <label for="comment">Your Message: <span class="required">*</span>
                    <?php if(isset($errors) && in_array('comment',$errors)) {echo "<span class='warning'>Please enter your message.</span>";} ?>
                </label>
                <div id="comment"><textarea name="comment" rows="10" cols="45" tabindex="3"><?php if(isset($_POST['comment'])) {echo htmlentities($_POST['comment'], ENT_COMPAT, 'UTF-8');} ?></textarea></div>
            </div>
            
            <div>
            <label for="captcha">Please provide a numeric answer for this question: <?php echo captcha(); ?><span class="required">*</span>
                <?php if(isset($errors) && in_array('wrong',$errors)) {echo "<span class='warning'>Please give a correct answer.</span>";}?></label>
                <input type="text" name="captcha" id="captcha" value="" size="20" maxlength="5" tabindex="4" />
            </div>
            
            <div class="website">
            <label for="website">If you see this box DON'T fill anything.</label>
            <input type="text" name="url" id="url" value="" size="20" maxlength="20" />
            </div>
            

    </fieldset>
    <div><input type="submit" name="submit" value="Send Email" tabindex="3" /></div>
    </form>
</div>
<?php
    include('includes/sidebar-b.php');
    include('includes/footer.php');
?>