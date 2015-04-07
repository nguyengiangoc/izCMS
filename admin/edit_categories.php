<?php include('../includes/header.php'); ?>
<?php include('../includes/mysqli_connect.php');?>
<?php include('../includes/function.php'); ?>
<?php include('../includes/sidebar-admin.php'); ?>
<?php
//phai them redirect to view category in 5s nua
    //xac nhan biet get ton tai va thuoc loai du lieu cho phep 
        if (isset($_GET['cid']) && filter_var($_GET['cid'], FILTER_VALIDATE_INT)) {
            $cid = $_GET['cid'];                
        } else {
            redirect_to('admin/admin.php'); //neu khong ton tai thi dung code, chuyen huong
        }
        if($_SERVER['REQUEST_METHOD'] == 'POST' ) { //gia tri ton tai, xu ly form
            $errors = array ();
            // kiem tra ten cua category
            if (empty($_POST['category'])) {
                $errors[] = 'category';
            } else {
                $cat_name = mysqli_real_escape_string($dbc, strip_tags($_POST['category'])) ; 
                //de phong truong hop nguoi dung nhap ma lenh sql hoac java sql injection
            }
            
            //kiem tra position cua category
            if (isset($_POST['position'])) {
                $position = $_POST['position'];
            } else {
                $errors[] = 'position';
            }
            
            if (empty($errors)) { 
                //neu k co loi xay ra thi chen vao csdl
            $q = "UPDATE categories SET cat_name = '{$cat_name}', position =  '$position' WHERE cat_id = {$cid} LIMIT 1";
            //LIMIT la gi, can tim hieu them
            $r = mysqli_query($dbc, $q);
            confirm_query($r, $q);
            
            if (mysqli_affected_rows($dbc) == 1) { 
                //khi dung update, insert, hoac delete thi dung affected rows, su dung dbc
                //ham dung de Get the number of affected rows by the last INSERT, UPDATE, REPLACE or DELETE query associated with
                //so dong bi anh huong chu khong phai dong dau tien!!!
                $messages = "<p class='success'>The category was edited successfully.</p>";
            } else {
                $messages = "<p class='warning'>Could not edit categories due to a system error.</p>";
            }
        } else {
            $messages = "<p class='warning'>Please fill all the required fields</p>";
        }
            
    }
    ?>
<div id="content">
    
    <?php 
        $q = "SELECT cat_name, position FROM categories WHERE cat_id = {$cid}";
        //lay cat id tren link, xem thu xem co category nao co id trung voi cat id tren link khong
        //neu co thi lay cat name, position cua category do
        $r = mysqli_query($dbc, $q);
        confirm_query ($r, $q);
        if (mysqli_num_rows($r) > 0) { 
            // neu so ket qua cua lenh $r lon hon 0
            list($cat_name, $position) = mysqli_fetch_array($r, MYSQLI_NUM);
            //list thi chi dc dung mysqli num, khong dc dung mysqli assoc
            //vi chi co 1 hang nen lam list cho nhanh, khong can array
        } else {
            $messages = "<p class = 'warning'>The category does not exist</p>";
            //neu khong thi hien ra category khong ton tai
        }
    ?>
    <h2>Edit category <?php if (isset($cat_name)) 
    //phai kiem tra ton tai hay khong vi neu o tren ton tai thi cat name moi dc defined
    //neu khong ton tai thi neu khong kiem tra isset se bi undefined
    echo $cat_name; ?> </h2>
    <?php if(!empty($messages)) echo $messages; ?>
    
    <form id="add_cat" action="" method="post">
    <fieldset>
        <legend>Add category</legend>
            <div>
                <label for="category">Category Name: <span class="required">*</span>
                <?php
                    if (isset($errors) && in_array('category', $errors)) {
                        //neu mang? error dc set va co chu~ array trong category
                        echo "<span class = 'warning'> Please provide the category name.</span>";
                    }
                ?>  
                </label>
                <input type="text" name="category" id="category" value="<?php 
                if (isset($cat_name)) echo $cat_name;
                ?>" size="20" maxlength="150" tabindex="1" />
            </div>
            <div>
                <label for="Position">Position: <span class="required">*</span></label>
                <select name="position" tabindex='2'>
                    <?php
                        $q = "SELECT count(cat_id) AS count FROM categories";
                        $r = mysqli_query($dbc, $q) or die("Query {$q} \n<br> MySQL Error: ".mysqli_error($dbc));
                        if (mysqli_num_rows($r) == 1) {
                            //doi voi ham SELECT thi phai dung mysqli_num_rows
                            //vi gia tri chi? tra? ve 1 hang`
                            //mysqli_num_rows
                            //A result set identifier returned by mysqli_query()
                            list ($num) = mysqli_fetch_array($r, MYSQLI_NUM);
                            ////lay 1 dong trong bang ket qua ra lam array, mysqli_assoc dung de goi ten thanh phan trong array
                            //bang ten so thu tu thanh phan
                            //returns a row from a recordset as an associative array and/or a numeric array.
                            //variable 1: Specifies which data pointer to use. 
                            //The data pointer is the result from the mysql_query() function
                            for($i=1; $i<=$num+1; $i++) { 
                                // Tao vong for de ra option, cong them 1 gia tri cho position
                                echo "<option value='{$i}'";
                                    if(isset($position) && $position == $i) echo "selected='selected'";
                                    //neu position cua category hien tai bang voi position cua category trong 
                                    //vong lap thi de no la selected
                                echo ">".$i."</otption>";
                            }
                        }
                    ?>
                </select>
            </div>
    </fieldset>
    <p><input type="submit" name="submit" value="Edit Category"/></p>
    </form>
    
</div> <!--end content-->

<?php include('../includes/sidebar-b.php'); ?>
<?php include('../includes/footer.php'); ?>