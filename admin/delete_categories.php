<?php include('../includes/header.php'); ?>
<?php include('../includes/mysqli_connect.php');?>
<?php include('../includes/function.php'); ?>
<?php include('../includes/sidebar-admin.php'); ?>

<div id="content">
<?php
//van de chinh: khi delete category thi nhung article nam trong category nay se di dau???
    if (isset($_GET['cid'], $_GET['cat_name']) && filter_var($_GET['cid'], FILTER_VALIDATE_INT)) {
        $cid = $_GET['cid'];
        $cat_name = $_GET['cat_name'];
        //neu cid va cat name ton tai tu link lay tu view catregory
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            //xu ly form
            if (isset($_POST['delete']) && $_POST['delete'] == "yes") {
                $q = "DELETE FROM categories WHERE cat_id = {$cid} LIMIT 1";
                $r = mysqli_query($dbc, $q);
                confirm_query($r, $q);
                if (mysqli_affected_rows($dbc) == 1) {
                    //xoa category thanh cnog, bao cho nguoi dung
                    $messages = "<p class = 'success'>The category was deleted successfully.</p>";
                } else {
                    $messages = "<p class = 'warning'>The category couldn't be deleted.</p>";
                    
                }
            } else {
                //neu khong phai la yes
                $messages = "<p class = 'warning'>I thought so too! It shouldn't be deleted.</p>";
            }
        } //ket thuc xu ly form
    } else {
        //neu cid khong ton tai hoac khong o dinh dang mong muon
        redirect_to('admin/view_categories.php');
    }
?>
    <h2> Delete Category: <?php if(isset($cat_name)) echo htmlentities($cat_name, ENT_COMPAT, 'UTF-8') 
    //vi cat name lay tu link xuong, nguoi dung co the thay doi phan tren link
    //nen htmlentities chi in phan chu~ ra, bo het phan tag
    ?></h2> 
    <?php 
        if (!empty($messages)) echo $messages;
    ?>
	   <form action="" method="post">
       <fieldset>
            <legend>Delete Category</legend>
                <label for="delete">Are you sure?</label>
                <div>
                    <input type="radio" name="delete" value="no" checked="checked" /> No
                    <input type="radio" name="delete" value="yes" /> Yes
                </div>
                <div><input type="submit" name="submit" value="Delete" onclick="return confirm('Are you sure?');" /></div>
        </fieldset>
       </form>
</div><!--end content-->

<?php include('../includes/sidebar-b.php'); ?>
<?php include('../includes/footer.php'); ?>