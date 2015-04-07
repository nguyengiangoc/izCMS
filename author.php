<?php 
    $title = '';
    include('includes/header.php');
    include('includes/mysqli_connect.php');
    include('includes/function.php');
    include('includes/sidebar-a.php');
?>
<div id="content">
    <?php
        
        
    ?>
    <?php
        if($aid = validate_id($_GET['aid'])) {
            $display = 4;
            //display la so bai viet dc hien thi trong 1 trang
            if(isset($_GET['s']) && filter_var($_GET['s'], FILTER_VALIDATE_INT)) {
                $start = $_GET['s'];
            } else {
                $start = 0;
            }
            //start la so thu tu cua bai viet bat dau trang dang xem
            //bai viet dc danh so tu` 0
            //vi du neu dang xem trang 1 thi start la 0, dang xem trang 2 thi start la 4 (display = 4)
            if(isset($_GET['p']) && filter_var($_GET['p'], FILTER_VALIDATE_INT)) {
                $page = $_GET['p'];
            } else {
                $q = "SELECT COUNT(page_id) FROM pages";
                $r = mysqli_query($dbc, $q);
                confirm_query($r, $q);
                list($record) = mysqli_fetch_array($r, MYSQLI_NUM);
                //$record la tong so bai viet cua user nay
                if($record > $display) { //neu tong so bai viet nhieu hon so bai dc hien thi trong 1 trang
                    $page = ceil($record/$display);
                    //lay tong so bai viet cua user chia cho so bai dc hien thi trong 1 trang, lay phan nguyen + 1
                    //ket qua la tong so trang
                    //page la tong so trang, khong doi
                } else {
                    $page = 1;
                    //neu khong thi tong so trang la 1
                }
            }
            $q = "SELECT p.page_id, p.page_name, p.content, ";
            $q .= " DATE_FORMAT(p.post_on, '%b %d, %Y') AS date, ";
            $q .= " CONCAT_WS(' ', u.first_name, u.last_name) AS name, u.user_id ";
            $q .= " FROM pages AS p ";
            $q .= " INNER JOIN users AS u ";
            $q .= " WHERE u.user_id = {$aid} ";
            $q .= " ORDER BY date ASC LIMIT {$start}, {$display}";
            $r = mysqli_query($dbc, $q);
            confirm_query($r, $q);
            if (mysqli_num_rows($r) > 0) {
                while ($author = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
                echo "
                    <div class = 'post'>
                        <h2><a href = 'single.php?pid={$author['page_id']}'>{$author['page_name']}</a></h2>
                        <p>".
                        the_excerpt($author['content']).
                        "... <a href = 'single.php?pid={$author['page_id']}'>Read more</a>".
                        "</p>
                        <p class = 'meta'><strong>Posted by </strong><a href='author.php?aid={$author['user_id']}'>{$author['name']}</a><strong> on</strong> {$author['date']}</p>
                    </div>
                ";
                }
            echo "<ul class='pagination'>";
        if($page > 1) { //neu tong so trang lon hon 1 thi phai hien thi nav bar danh so cac trang
            $current_page = ($start/$display) + 1;
            //current page la so thu tu cua trang dang xem
            //vi du neu nhu dang xem trang 1, start la 0, thi 0/4 = 0, phai + 1 moi ra so thu tu cua trang dang xem
            //neu dang xem trang 2, start la 4, thi 4/4 = 1, phai +1 moi ra 2
            if($current_page != 1) {
                //neu so thu tu cua trang dang xem khong phai 1, tuc la khong phai trang dau tien thi phai 
                //hien thi nut first va nut previous
                echo "
                <li><a href = 'author.php?aid={$aid}&s=0&p={$page}'>First</a></li>
                ";
                echo "
                <li><a href = 'author.php?aid={$aid}&s=".($start - $display)."&p={$page}'>Previous</a></li>
                "; 
                //khi bam previous, phai lay so thu tu cua bai viet dau tien trong trang dang xem
                //tru di so bai dc hien thi trong mot trang
                //khi do ket qua la so thu tu cua bai viet dau tien trong trang truoc do
                //vi du trang dang xem la trang 3, so thu tu cua bai viet dau tien la 8
                //tru di display la 4 ra dc ket qua 4, la so thu tu cua bai viet dau tien trong trang 2
            }
            for ($i = 1; $i <= $page; $i++) {
                if ($i != $current_page) {
                    echo "<li><a href = 'author.php?aid={$aid}&s=".($display * ($i - 1))."&p={$page}'>$i</a></li>";
                } else {
                    echo "<li class = 'current'>{$i}</li>";
                }
            }
            if($current_page != $page) {
                echo "
                <li><a href = 'author.php?aid={$aid}&s=".($start + $display)."&p={$page}'>Next</a></li>
                "; //tuong tu nut previous, doi? dau tru thanh dau cong
                echo "
                <li><a href = 'author.php?aid={$aid}&s=".(($page - 1)*$display)."&p={$page}'>Last</a></li>
                ";
            }
        }
        echo "</ul>";    
            } else {
                echo "<p class = 'warning'>The author you're trying to visit no longer exists.</p>";
            }
        } else {
            redirect_to ();
        }
    ?>
</div>
<?php 
    include('includes/sidebar-b.php');
    include('includes/footer.php');
?>