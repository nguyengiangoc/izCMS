<?php include('../includes/header.php'); ?>
<?php include('../includes/mysqli_connect.php');?>
<?php include('../includes/function.php'); ?>
<?php include('../includes/sidebar-admin.php'); ?>

<div id="content">
<?php
//van de chinh: khi delete category thi nhung article nam trong category nay se di dau???
    if (isset($_GET['pid'], $_GET['pn']) && filter_var($_GET['pid'], FILTER_VALIDATE_INT)) {
        $pid = $_GET['pid'];
        $page_name = $_GET['pn'];
        //neu cid va cat name ton tai tu link lay tu view catregory
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            //xu ly form
            if (isset($_POST['delete']) && $_POST['delete'] == "yes") {
                $q = "DELETE FROM pages WHERE page_id = {$pid} LIMIT 1";
                $r = mysqli_query($dbc, $q);
                confirm_query($r, $q);
                if (mysqli_affected_rows($dbc) == 1) {
                    //xoa category thanh cnog, bao cho nguoi dung
                    $messages = "<p class = 'success'>The page was deleted successfully.</p>";
                } else {
                    $messages = "<p class = 'warning'>The page couldn't be deleted.</p>";
                    
                }
            } else {
                //neu khong phai la yes
                $messages = "<p class = 'warning'>I thought so too! It shouldn't be deleted.</p>";
            }
        } //ket thuc xu ly form
    } else {
        //neu cid khong ton tai hoac khong o dinh dang mong muon
        redirect_to('admin/view_pages.php');
    }
?>
    <h2> Delete Page: <?php if(isset($page_name)) echo htmlentities($page_name, ENT_COMPAT, 'UTF-8') 
    //vi cat name lay tu link xuong, nguoi dung co the thay doi phan tren link
    //nen htmlentities chi in phan chu~ ra, bo het phan tag
    ?></h2> 
    <?php 
        if (!empty($messages)) echo $messages;
    ?>
	   <form action="" method="post">
       <fieldset>
            <legend>Delete Page</legend>
                <label for="delete">Are you sure?</label>
                <div>
                    <input type="radio" name="delete" value="no" checked="checked" /> No
                    <input type="radio" name="delete" value="yes" /> Yes
                </div>
                <div><input type="submit" name="submit" value="Delete" onclick="return confirm('Are you sure?');" /></div>
        </fieldset>
       </form>
</div><!--end content-->

<?php include('../includes/footer.php'); ?>