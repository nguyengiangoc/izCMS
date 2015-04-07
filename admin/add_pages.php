<?php include('../includes/header.php'); ?>
<?php include('../includes/mysqli_connect.php')?>
<?php include('../includes/function.php'); ?>
<?php include('../includes/sidebar-admin.php'); ?>
<?php
        admin_access();
        if($_SERVER['REQUEST_METHOD'] == 'POST' ) { //gia tri ton tai, xu ly form
            $errors = array ();
            if (empty($_POST['page_name'])) {
                $errors[] = 'page_name';
            } else {
                $page_name = mysqli_real_escape_string($dbc, strip_tags($_POST['page_name'])) ; //de phong truong hop nguoi dung nhap ma lenh sql hoac java sql injection
            }
            if (isset($_POST['category'])) {
                $cat_id = $_POST['category'];
            } else {
                $errors[] = 'category';
            }
            if (isset($_POST['position'])) {
                $position = $_POST['position'];
            } else {
                $errors[] = 'position';
            }
            
            if (empty($_POST['content'])) {
                $errors[] = 'content';
            } else {
                $content = mysqli_real_escape_string($dbc, $_POST['content']);
            }
            $post_time = date('Y-m-d H:i:s');
            if (empty($errors)) { 
                //neu k co loi xay ra thi chen vao csdl
                $q = "INSERT INTO pages (user_id, cat_id, page_name, content, position, post_on) 
                VALUES ('{$_SESSION['uid']}', '{$cat_id}', '{$page_name}', '{$content}', '{$position}', '{$post_time}')";
                // nho them dau '' truoc cac gia tri cho vao
                $r = mysqli_query($dbc, $q) ;
                confirm_query ($r, $q);
                if (mysqli_affected_rows($dbc) == 1) { 
                    //khi dung update, insert, hoac delete thi dung affected rows, su dung dbc
                    //ham dung de Get the number of affected rows by the last INSERT, UPDATE, REPLACE or DELETE query associated with
                    //so dong bi anh huong chu khong phai dong dau tien!!!
                    $messages = "<p class='success'>The page was added successfully.</p>";
                } else {
                    $messages = "<p class='warning'>Could not add to the database due to a system error.</p>";
                }
            } else {
                $messages = "<p class='warning'>Please fill all the required fields</p>";
            }
            
    } //end main IF
    ?>
<div id="content">
    <h2>Create a category</h2>
    <?php if(!empty($messages)) echo $messages; ?>
    <form id="login" action="" method="post">
    <fieldset>
    	<legend>Add a Page</legend>
            <div>
                <label for="page">Page Name: <span class="required">*</span>
                    <?php 
                        if(isset($errors) && in_array('page_name', $errors)) {
                            echo "<p class='warning'>Please fill in the page name</p>";
                        }
                    ?>
                </label>
                <input type="text" name="page_name" id="page_name" value="<?php if(isset($_POST['page_name'])) echo strip_tags($_POST['page_name']); ?>" size="20" maxlength="80" tabindex="1" />
            </div>
            
            <div>
                <label for="category">All categories: <span class="required">*</span>
                    <?php 
                        if(isset($errors) && in_array('category', $errors)) {
                            echo "<p class='warning'>Please pick a category</p>";
                        }
                    ?>
                </label>
                
                <select name="category">
                    <option>Select Category</option>
                    <?php
                        $q = "SELECT cat_id, cat_name FROM categories ORDER BY position ASC";
                        $r = mysqli_query($dbc, $q);
                        if(mysqli_num_rows($r) > 0) {
                            while($cats = mysqli_fetch_array($r, MYSQLI_NUM)) {
                            //returns a row from a recordset as an associative array and/or a numeric array.
                            //lay du lieu tu mot hang duoi dang array
                            //variable 1: Specifies which data pointer to use. 
                            //The data pointer is the result from the mysql_query() functio
                                echo "<option value='{$cats[0]}'";
                                //hien tai bien cats dang la mot array co hai thanh phan, cat_id va cat_name
                                //$cats[0] la lay cat_id                                
                                    if(isset($_POST['category']) && ($_POST['category'] == $cats[0])) echo "selected='selected'";
                                echo ">".$cats[1]."</option>";
                                //$cats[1] la lay cat_name
                            }
                        }
                    ?>
                </select>
            </div>
            <div>
                <label for="position">Position: <span class="required">*</span>
                    <?php 
                        if(isset($errors) && in_array('position', $errors)) {
                            echo "<p class='warning'>Please pick a position</p>";
                        }
                    ?>
                </label>
                <select name="position">
                    <?php
                        $q = "SELECT count(page_id) AS count FROM pages";
                        $r = mysqli_query($dbc, $q) or die("Query {$q} \n<br> MySQL Error: ".mysqli_error($dbc));
                        if (mysqli_num_rows($r) == 1) {
                            //doi voi ham SELECT thi phai dung mysqli_num_rows
                            //vi gia tri chi? tra? ve 1 hang`
                            //mysqli_num_rows
                            //A result set identifier returned by mysqli_query()
                            list ($num) = mysqli_fetch_array($r, MYSQLI_NUM);
                            //returns a row from a recordset as an associative array and/or a numeric array.
                            //variable 1: Specifies which data pointer to use. 
                            //The data pointer is the result from the mysql_query() function
                            for($i=1; $i<=$num+1; $i++) { 
                                // Tao vong for de ra option, cong them 1 gia tri cho position
                                echo "<option value='{$i}'";
                                    if(isset($_POST['position']) && $_POST['position'] == $i) echo "selected='selected'";
                                echo ">".$i."</otption>";
                            }
                        }
                    ?>
                </select>
            </div>                
            <div>
                <label for="page-content">Page Content: <span class="required">*</span>
                    <?php 
                        if(isset($errors) && in_array('content', $errors)) {
                            echo "<p class='warning'>Please fill in the content</p>";
                        }
                    ?>
                </label>
                <textarea name="content" cols="50" rows="20"><?php if(isset($_POST['content'])) echo htmlentities($_POST['content'], ENT_COMPAT, 'UTF-8'); ?></textarea>)
            </div>
    </fieldset>
    <p><input type="submit" name="submit" value="Add Page" /></p>
</form>
    
    
    
</div> <!--end content-->

<?php include('../includes/sidebar-b.php'); ?>
<?php include('../includes/footer.php'); ?>