<div id="content-container">
    <div id="section-navigation">
        <h2>Site Navigation</h2>
        <ul class="Navi"> <!-- bat dau list category -->
        <?php
        //xac dinh xem cat id co ton tai khong de to dam link
        if (isset($_GET['cid']) && filter_var($_GET['cid'], FILTER_VALIDATE_INT)) {
            $cid = $_GET['cid'];
        } else {
            $cid = NULL; //trong truong hop nguoi dung vao trang chu, cid chua duoc set
        }
        //xac dinh xem page_id co ton tai khong de to dam link
        if (isset($_GET['pid']) && filter_var($_GET['pid'], FILTER_VALIDATE_INT)) {
            $pid = $_GET['pid'];
        } else {
            $pid = NULL;
        }
        //cau lenh truy xuat category
        $q = "SELECT cat_name, cat_id FROM categories ORDER BY position ASC";
        $r = mysqli_query($dbc, $q);
        confirm_query($r, $q);
        while ($cats = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
            //lay 1 dong trong bang ket qua ra lam array, mysqli_assoc dung de goi ten thanh phan trong array
            //bang ten cot
            echo "<li><a href='index.php?cid={$cats['cat_id']}'";
                if ($cats['cat_id'] == $cid) echo "class='selected'";
                //phai kiem tra xem biet cid co ton tai hay khong
                //get la de lay so cid tu tren link
                //neu nhu cat id cua category bang voi cid tren link thi them class = selected vao
                //file css se thay doi dinh dang cua ten category             
            echo ">".$cats['cat_name']."</a>"; //bat dau ten category
            // cau lenh truy xuat page  
            $q1 = "SELECT page_name, page_id FROM pages WHERE cat_id={$cats['cat_id']} ORDER BY position ASC";
            //chon page name page id voi dieu kien cat id cua hang nay bang voi cat id lay trong hang cua cat name
            //tuc la bang voi cat id cua cat name dang xet
            $r1 = mysqli_query($dbc, $q1);
            confirm_query($r1, $q1);
            echo "<ul class='pages'>"; //bat dau list page
            while ($pages = mysqli_fetch_array($r1, MYSQLI_ASSOC)) {
                echo "<li><a href='single.php?pid={$pages['page_id']}'";
                if ($pages['page_id'] == $pid) echo "class='selected'";
                
                echo ">".$pages['page_name']."</a></li>";//bat dau va ket thuc ten page
                }
            echo "</ul>"; // ket thuc list page
            echo "</li>"; //ket thuc ten category
        }
        ?>

        </ul> <!-- ket thuc list category -->
	
    </div><!--end section-navigation-->