<?php 
    include('includes/mysqli_connect.php');
    include('includes/function.php'); 
    if (isset($_GET['pid']) && filter_var($_GET['pid'], FILTER_VALIDATE_INT)) {
        $pid = $_GET['pid'];
        $q = "SELECT p.page_name, p.page_id, p.content, ";
        $q .= " DATE_FORMAT(p.post_on, '%b %d, %y') AS date, ";
        $q .= " CONCAT_WS(' ', u.first_name, u.last_name) AS name, u.user_id ";
        $q .= " FROM pages  AS p ";
        $q .= " INNER JOIN users AS u ";
        $q .= " USING (user_id) ";
        $q .= " WHERE p.page_id = {$pid}";
        $q .= " ORDER BY date ASC LIMIT 1";
        $r = mysqli_query($dbc, $q);
        confirm_query($r, $q);
        $posts = array();
        //tao mot array trong de luu gia tri vao
        if (mysqli_num_rows($r) > 0) {
            //neu co bai dang de hien thi ra
            $pages = mysqli_fetch_array($r, MYSQLI_ASSOC);
            $title = $pages['page_name'];
            $posts[] = array(
                'page_name' => $pages['page_name'], 
                'content' => $pages['content'], 
                'author' => $pages['name'], 
                'post_on' => $pages['date'],
                'aid' => $pages['user_id']
                );
        } else {
            echo "<p>There is no post in this category.</p>";
        }
        } else {
            redirect_to();
        }
    include('includes/header.php'); 
    include('includes/sidebar-a.php'); ?>

<div id="content">
    <?php
    foreach ($posts as $posts) {
        echo "
                    <div class = 'post'>
                        <h2>{$posts['page_name']}</h2>
                        <p><strong>Posted by </strong><a href='author.php?aid={$posts['aid']}'>{$posts['author']}</a> <strong>on</strong> {$posts['post_on']}</p>
                        <p>".the_content($posts['content']) ."</p>
                    </div>
                ";
    }

    ?>
    
    <?php include('includes/comment_form.php'); ?>

</div> <!--end content -->

<?php include('includes/footer.php'); ?>