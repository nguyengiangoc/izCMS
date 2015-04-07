<?php
    //require_once('recaptchalib.php');
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        $errors = array ();
        if(!empty($_POST['name'])) {
            $name = mysqli_real_escape_string($dbc, strip_tags($_POST['name'])) ;
        } else {
            $errors[] = "name";
        }
        if(isset($_POST['email']) && filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $e = mysqli_real_escape_string($dbc, strip_tags($_POST['email']));
        } else {
            $errors[] = "email";
        }
        if(!empty($_POST['comment'])) {
            $comment = mysqli_real_escape_string($dbc, strip_tags($_POST['comment'])) ;
        } else {
            $errors[] = "comment";
        }
        if(isset($_POST['captcha']) && trim($_POST['captcha']) != $_SESSION['q']['answer']) {
            $errors[] = "wrong";
        }
        if(!empty($_POST['website'])) {
            redirect_to('thankyou.html');
            exit;
        }
        if(!empty($_POST['question'])) {
            $errors[] = "delete";
        }
        //$privatekey = "6Lf-e9oSAAAAAPZy1LTJNkbW4ZutnUEmMGZWKX3i ";
        //$resp = recaptcha_check_answer ($privatekey,
                                    //$_SERVER["REMOTE_ADDR"],
                                    //$_POST["recaptcha_challenge_field"],
                                    //$_POST["recaptcha_response_field"]);
        
        //if (!$resp->is_valid) {
        // What happens when the CAPTCHA was entered incorrectly
        //$errors[] = 'sai captcha';
        //}
        $comment_time = date('Y-m-d H:i:s');
        if(empty($errors)) {
            $q = "INSERT INTO comment (page_id, author, email, comment, comment_date) VALUES ({$pid}, '{$name}', '{$e}', '{$comment}', NOW())";
            $r = mysqli_query($dbc, $q);
            confirm_query($r, $q);
            if(mysqli_affected_rows($dbc) == 1) {
                $message = "<p class = 'success'>Thank you for your comment.</p>";
            } else {
                $message = "<p class = 'warning'>Your comment couldn't be added due to a system error.</p>";
                
            }
        } else {
            $message = "<p = class= 'warning'>Please try again.</p>";
        }
    }
?>

<?php
    $q = "SELECT comment_id, author, comment, DATE_FORMAT (comment_date, '%b %d %Y') AS date FROM comment WHERE page_id = {$pid}";
    $r = mysqli_query($dbc, $q);
    confirm_query($r, $q);
    if(mysqli_num_rows($r) > 0) {
        echo "<ol id = 'discuss'>";
        while (list($cmt_id, $author, $comment, $date) = mysqli_fetch_array($r, MYSQLI_NUM)) {
            echo "<li class = 'comment-wrap'>
            <p class = 'author'>{$author}</p>
            <p class = 'comment-sec'>{$comment}</p>";
            if (is_admin()) {
                echo "<a id='{$cmt_id}' class = 'remove'>Delete</a>";
            }
            echo "
            <p class = 'date'>{$date}</p> 
            </li>        
            ";        
        }
        echo "</ol>";
    } else {
        //neu khong co comment thi se~ bao mot cau khac
        echo "<h2>Be the first to comment.</h2>";
    }
?>
<?php if(!empty($message)) echo $message; ?>
<form id="comment-form" action="" method="post">
    <fieldset>
    	<legend>Leave a comment</legend>
            <div>
            <label for="name">Name: <span class="required">*</span>
            <?php if(isset($errors) && in_array('name',$errors)) { 
                echo "<span class='warning'>Please enter your name.</span>";
                }?>
            </label>
            <input type="text" name="name" id="name" value="<?php 
                if(isset($_POST['name'])) {
                    echo htmlentities($_POST['name'], ENT_COMPAT, 'UTF-8');} 
            ?>" size="20" maxlength="80" tabindex="1" />
        </div>
        <div>
            <label for="email">Email: <span class="required">*</span>
            <?php 
                if(isset($errors) && in_array('email',$errors)) 
                echo "<span class='warning'>Please enter your email.</span>";
            ?>
            </label>
            <input type="text" name="email" id="email" value="<?php 
                if(isset($_POST['email'])) {
                    echo htmlentities($_POST['email']);
                } ?>" size="20" maxlength="80" tabindex="2" />
            </div>
        <div>
            <label for="comment">Your Comment: <span class="required">*</span>
                <?php 
                    if(isset($errors) && in_array('comment',$errors)) { 
                        echo "<span class='warning'>Please enter your message.</span>"; 
                } ?>
            </label>
            <div id="comment"><textarea name="comment" rows="10" cols="50" tabindex="3"><?php if(isset($_POST['comment'])) {echo htmlentities($_POST['comment'], ENT_COMPAT, 'UTF-8');} ?></textarea></div>
        </div>
        
        <div>
        <label for="captcha">Please provide a numeric answer to the following question: <?php echo captcha(); ?><span class="required">*</span>
            <?php if(isset($errors) && in_array('wrong',$errors)) {echo "<span class='warning'>Please give a correct answer.</span>";}?></label>
            <input type="text" name="captcha" id="captcha" value="" size="20" maxlength="5" tabindex="4" />
        </div>
        
        <div class="website">
        <label for="website">If you see this box DON'T fill anything.</label>
            <input type="text" name="url" id="url" value="" size="20" maxlength="20" />
        </div>
        
        <div>
        <label for="question">Please delete everything in the following box.
        <?php if(isset($errors) && in_array('delete',$errors)) { echo "<span class='warning'>You haven't deleted this.</span>"; } ?>
        </label>
            <input type="text" name="question" id="question" value="Delete this row" size="20" maxlength="40" />
        </div>
        
        <div>
            <label>Fill in the reCaptcha
            <?php //if(isset($errors) && in_array('sai captcha',$errors)) {echo "<span class='warning'>Please give a correct answer.</span>";}?></label>
            </label>
            <?php
                //$publickey = "6Lf-e9oSAAAAAAL43oMxzaLMOC0aTKait1v-dhAk";
                //echo recaptcha_get_html($publickey);
            ?>
        </div>
    </fieldset>
    <div><input type="submit" name="submit" value="Post Comment" /></div>
</form>