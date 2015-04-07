<?php

    define ('BASE_URL', 'http://localhost/izcms/');
    //xac dinh hang so cho dia chi tuyet doi
    
    
    
    //kiem tra xem ket qua tra ve tu sql co dung hay khong
    function confirm_query($result, $query) {
        global $dbc;
        //trong ham nay chua khai bao bien $dbc, ma lay o file mysqli_connect, phai bao global moi define
        if (!$result) {
            //neu result khong ton tai thi bao loi~
            die("Query ($query) \n\n <br /> MySQl Error: ".mysqli_error($dbc));
        }
    }

    //kiem tra xem nguoi dung co dang dang nhap khong
    function is_logged_in () {
        if(!isset($_SESSION['uid'])) {
            redirect_to('login.php');
        }
    }
    
    function report_error($msg) {
        if(!empty($msg)) {
            foreach ($msg as $m) {
                echo $m;
            }
        }
    }
    
    function redirect_to($page = 'index.php') {
        $url = BASE_URL.$page;
        header("Location: $url");
        exit();
    }

    function the_excerpt($text) {
        $sanitized = htmlentities($text, ENT_COMPAT, 'UTF-8');
        if (strlen($sanitized) > 400) {
            $cutstring = substr($sanitized,0,400);
            $words = substr($sanitized,0, strrpos($cutstring, ' '));
            return $words;
        } else {
            return $sanitized;
        }
    }
    
    function validate_id ($id) {
        if (isset($id) && filter_var($id, FILTER_VALIDATE_INT)) {
        $val_id = $id;
        return $val_id;
        } else {
            return NULL;
        }
    }
    
    function get_page_by_id($id) {
        global $dbc;
        $q = "SELECT p.page_name, p.page_id, p.content, ";
        $q .= " DATE_FORMAT(p.post_on, '%b %d, %y') AS date, ";
        $q .= " CONCAT_WS(' ', u.first_name, u.last_name) AS name, u.user_id ";
        $q .= " FROM pages  AS p ";
        $q .= " INNER JOIN users AS u ";
        $q .= " USING (user_id) ";
        $q .= " WHERE p.page_id = {$pid}";
        $q .= " ORDER BY date ASC LIMIT 1";
        $result = mysqli_query($dbc, $q);
        confirm_query($result, $q);
        return $result;
    }
    
    //ham tao paragraph tu csdl
    function the_content($text) {
        $sanitized = htmlentities($text, ENT_COMPAT, 'UTF-8');    
        return str_replace(array("\r\n", "\n"), array("<p>","</p>"), $sanitized);
    
    }
   
    function captcha() {
        $qna = array(
                1 => array('question' => 'Mot cong mot', 'answer' => 2),
                2 => array('question' => 'ba tru hai', 'answer' => 1),
                3 => array('question' => 'ba nhan nam', 'answer' => 15),
                4 => array('question' => 'sau chia hai', 'answer' => 3),
                5 => array('question' => 'nang bach tuyet va .... chu lun', 'answer' => 7),
                6 => array('question' => 'Alibaba va ... ten cuop', 'answer' => 40),
                7 => array('question' => 'an mot qua khe, tra .... cuc vang', 'answer' => 1),
                8 => array('question' => 'may tui .... gang, mang di ma dung', 'answer' => 3)
                );
        $rand_key = array_rand($qna); // Lay ngau nhien mot trong cac array 1, 2, 4
        $_SESSION['q'] = $qna[$rand_key]; //luu question va answer vao session
        return $question = $qna[$rand_key]['question'];
    } // END function captcha
    
    function clean_email ($value) {
        $suspect = array('to:', 'bcc:','cc:','content-type:','mime-version:', 'multipart-mixed:','content-transfer-encoding:');
        foreach ($suspect as $s) {
            if(strpos($value, $s) !== FALSE) {
                return '';
            }
            $value = str_replace(array('\n','\r','%0a','%0d'), '', $value);
            return trim($value); 
        }
    }
    
    function is_admin() {
        return isset($_SESSION['user_level']) && ($_SESSION['user_level'] == 2) ;
    }
    
    function admin_access() {
        if (!is_admin()) {
            redirect_to();
        }
    }
    
    function view_counter($pg_id) {
        $ip = $_SERVER['REMOTE_ADDR'];
        global $dbc;

        // Truy van CSDL de xem page view
        $q = "SELECT num_views, user_ip FROM page_views WHERE page_id = {$pg_id}";
        $r = mysqli_query($dbc, $q); confirm_query($r, $q);

        if(mysqli_num_rows($r) > 0) {
            
            // Neu ket qua tra ve, co nghia la da ton tai trong table, Update page view
            list($num_views, $db_ip) = mysqli_fetch_array($r, MYSQLI_NUM);
            
            // So sanh IP trong CSDL va IP cua nguoi dung, neu khac nhau thi se update CSDL
            if($db_ip !== $ip) {
                $q = "UPDATE page_views SET num_views = (num_views + 1) WHERE page_id = {$pg_id} LIMIT 1";
                $r = mysqli_query($dbc, $q); confirm_query($r, $q);
            }
            // neu ip bang thi khong lam gi het

        } else {
            // Neu ko co ket qua tra ve, thi se insert vao table.
            $q = "INSERT INTO page_views (page_id, num_views, user_ip) VALUES ({$pg_id}, 1, '{$ip}')";
            $r = mysqli_query($dbc, $q); confirm_query($r, $q);
            $num_views = 1;
        }
        return $num_views;
    }// ENd view_counter
    
    function fetch_user($user_id) {
        $q = "SELECT * FROM users WHERE user_id = {$user_id}";
        global $dbc;
        $r = mysqli_query($dbc, $q);
        confirm_query($r, $q);
        if (mysqli_num_rows($r) > 0) {
            return $result_set = mysqli_fetch_array($r, MYSQLI_ASSOC);
        } else {
            return FALSE;
        }
    }
    
    function fetch_users($order) {
        global $dbc;
        $q = "SELECT * FROM users ORDER BY {$order} ASC";
        $r = mysqli_query($dbc, $q);
        confirm_query($r, $q);
        if (mysqli_num_rows($r) > 1) {
            // tao ra mot array de luu lai ket qua
            $users = array();
            while($results = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
                $users[] = $results; 
            }
            return $users;
        } else {
            return FALSE;
        }
    }
    
    
    // Ham de sap xep thu tu cua bang USERS
    function sort_table_users($order) {
        switch ($order) {
            case 'fn':
            $order_by = "first_name";
            break;
            
            case 'ln':
            $order_by = "last_name";
            break;
            
            case 'e':
            $order_by = "email";
            break;
            
            case 'ul':
            $order_by = "user_level";
            break;
            
            default:
            $order_by = "first_name";
            break;
        }
        return $order_by;
    } // END sort_table_users
?>