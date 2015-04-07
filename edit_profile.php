<?php $title = 'Edit Profile'; include('includes/header.php');?>
<?php include('includes/mysqli_connect.php');?>
<?php include('includes/function.php');?>
<?php include('includes/sidebar-a.php'); 
	// Kiem tra xem nguoi dung da login chua?
	is_logged_in();
?>
<?php
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $errors = array();
    // Trim all incoming data
    $trimmed = array_map('trim', $_POST);
    //cat khoang trang o dau va o cuoi
     
    if(preg_match('/^[\w]{2,10}$/i', $trimmed['first_name'])) {
        $fn = $trimmed['first_name'];
    } else {
        $errors[] = "first_name";
    }

    if(preg_match('/^[\w]{2,10}$/i', $trimmed['last_name'])) {
        $ln = $trimmed['last_name'];
    } else {
        $errors[] = "last name";
    }
    
    if(filter_var($trimmed['email'],FILTER_VALIDATE_EMAIL)) {
        $e = $trimmed['email'];
    } else {
        $errors[] = "email";
    }
    // Check for website (not required) 
    $web = (!empty($trimmed['website'])) ? $trimmed['website'] : NULL;
    
    // Check for website (not required) 
    $bio = (!empty($trimmed['bio'])) ? $trimmed['bio'] : NULL;

    if(empty($errors)) {
        $q = "UPDATE users SET
            first_name = ?, last_name = ?, email = ?, website = ?, bio = ?
            WHERE user_id = ?
            LIMIT 1";
        $stmt = mysqli_prepare($dbc, $q);
        //prepare kiem tra xem cau lenh query co phu hop voi bang khong
        mysqli_stmt_bind_param($stmt, 'sssssi', $fn, $ln, $e, $web, $bio, $_SESSION['uid']);
        // sssssi la 5 bien string, 1 bien integer
        mysqli_stmt_execute($stmt) or die("MySQL Error: $q" . mysqli_stmt_error($stmt));

        if(mysqli_stmt_affected_rows($stmt) > 0) {
            //neu dung stmt thi phai dung ham stmt affecte rows
            // Update thanh cong
            $message = "<p class='success'>Your profile has been updated successfully.</p>";
        } else {
            // Co loi he thong xay ra
            $errrors[] = "<p class='error'>Your profile could not be updated due to a system error.</p>";
        }
    }
          
}// END $_SERVER IF

?>
<div id="content">
    <?php if(!empty($message)) echo $message; ?>
<h2>User Profile</h2>
    <?php 
        // Truy xuat csdl de hien thi thong tin nguoi dung
        $user = fetch_user($_SESSION['uid']);
    ?>

<form enctype="multipart/form-data" action="processor/avatar.php" method="post"> 
<!-- de upload duoc phai co enctype -->
    <fieldset>

        <legend>Avatar</legend>

		<div>

            <img class="avatar" src="uploads/images/<?php echo (is_null($user['avatar']) ? "no_avatar.jpg" : $user['avatar']); ?>" alt="avatar" />

            <p>Please select a JPEG or PNG image of 512Kb or smaller to use as avatar<p> 

            <input type="hidden" name="MAX_FILE_SIZE" value="524288" />
            <!-- quy dinh do nang toi da cua file -->  
        
            <input type="file" name="image" />

            <p><input class="change" type="submit" name="upload" value="Save changes" /></p>
        </div>
  </fieldset> 
</form>

<form action="" method="post">        
    <fieldset>
        <legend>User Info</legend>
        <div>
            <label for="first-name">First Name
                <?php if(isset($errors) && in_array('first_name',$errors)) echo "<p class='warning'>Please enter your first name.</p>";?>
            </label> 
            <input type="text" name="first_name" value="<?php if(isset($user['first_name'])) echo strip_tags($user['first_name']); ?>" size="20" maxlength="40" tabindex='1' />
        </div>
        
        <div>
            <label for="last-name">Last Name
                <?php if(isset($errors) && in_array('last name',$errors)) echo "<p class='warning'>Please enter your last name.</p>";?>
            </label> 
            <input type="text" name="last_name" value="<?php if(isset($user['last_name'])) echo strip_tags($user['last_name']); ?>" size="20" maxlength="40" tabindex='1' />
        </div>
  </fieldset>
  <fieldset>
        <legend>Contact Info</legend>
        <div>
            <label for="email">Email
            <?php if(isset($errors) && in_array('email',$errors)) echo "<p class='warning'>Please enter a valid email.</p>";?>
            </label> 
            <input type="text" name="email" value="<?php if(isset($user['email'])) echo $user['email']; ?>" size="20" maxlength="40" tabindex='3' />
        </div>
        
        <div>
            <label for="website">Website</label> 
            <input type="text" name="website" value="<?php echo (is_null($user['website'])) ? '' : strip_tags($user['website']); ?>" size="20" maxlength="40" tabindex='4' />
        </div>
        
  </fieldset> 
  <fieldset>
        <legend>About Yourself</legend>
        <div>
            <textarea cols="50" rows="20" name="bio"><?php echo (is_null($user['bio'])) ? '' : htmlentities($user['bio'], ENT_COMPAT, 'UTF-8'); ?></textarea>
        </div>
  </fieldset>   
 <div><input type="submit" name="submit" value="Save Changes" /></div>
</form>
</div><!--end content-->
<?php include('includes/sidebar-b.php');?>
<?php include('includes/footer.php'); ?>

