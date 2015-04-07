<?php include('includes/header.php'); ?>
<?php include('includes/mysqli_connect.php');?>
<?php include('includes/function.php'); ?>
<?php include('includes/sidebar-a.php'); ?>

<div id="content">
    <?php
        if (isset($_GET['cid']) && filter_var($_GET['cid'], FILTER_VALIDATE_INT)) {
        $cid = $_GET['cid'];
        $q = "SELECT p.page_name, p.page_id, p.content, ";
        $q .= " DATE_FORMAT(p.post_on, '%b %d, %y') AS date, ";
        $q .= " CONCAT_WS(' ', u.first_name, u.last_name) AS name,  ";
        $q .= " u.user_id, COUNT(c.comment_id) AS count ";
        $q .= " FROM users AS u  ";
        $q .= " INNER JOIN pages AS p ";
        $q .= " USING (user_id) ";
        $q .= " LEFT JOIN comment AS c ";
        $q .= " ON p.page_id = c.page_id ";
        $q .= " WHERE p.cat_id={$cid} ";
        $q .= " GROUP BY p.page_name ";
        $q .= " ORDER BY date ASC LIMIT 0, 10";
        $r = mysqli_query($dbc, $q);
        $pages = mysqli_fetch_array($r, MYSQLI_ASSOC);
        echo print_r($pages);
        /*confirm_query($r, $q);
        if (mysqli_num_rows($r) > 0) {
            //neu co bai dang de hien thi ra
            while ($pages = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
                echo "
                    <div class = 'post'>
                        <h2><a href = 'single.php?pid={$pages['page_id']}'>{$pages['page_name']}</a></h2>
                        <p class = 'comments'><a href = 'single.php?pid={$pages['page_id']}#discuss'>{$pages['count']}</a></p>
                        <p>".
                        the_excerpt($pages['content']).
                        "... <a href = 'single.php?pid={$pages['page_id']}'>Read more</a>".
                        "</p>
                        <p class = 'meta'><strong>Posted by </strong><a href='author.php?aid={$pages['user_id']}'>{$pages['name']}</a><strong> on</strong> {$pages['date']}</p>
                    </div>
                ";
            }
        } else {
            echo "<p>There is no post in this category.</p>";
        }
        } else {
            echo "
            <h2>Welcome To izCMS</h2>
        <div>
            <p>
                Dummy text
            </p>
            
            <p>
                Dummy text
            </p>
            
            <p>
                Dummy text
            </p>
            </div>
            ";*/
        }
    ?>
    
        

</div> <!--end content -->

<?php include('includes/footer.php'); ?>