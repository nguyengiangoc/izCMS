<?php include('../includes/header.php'); ?>
<?php include('../includes/mysqli_connect.php');?>
<?php include('../includes/function.php'); ?>
<?php include('../includes/sidebar-admin.php'); ?>

<div id="content">
<h2>Manage Pages</h2>
<table>
	<thead>
		<tr>
            <?php
                admin_access();            
                echo "<th><a href='view_pages.php?sort=name";
                if (isset($_GET['ord']) && $_GET['ord'] == "asc") {
                    echo "&ord=dsc'>Pages</a></th>";
                    $ord = "ASC";
                } elseif (isset($_GET['ord']) && $_GET['ord'] == "dsc") {
                    echo "&ord=asc'>Pages</a></th>";
                    $ord = "DESC";
                } else {
                    echo "&ord=asc'>Pages</a></th>";
                    $ord = "ASC";
                }
                echo "<th><a href='view_pages.php?sort=on";
                if (isset($_GET['ord']) && $_GET['ord'] == "asc") {
                    echo "&ord=dsc'>Posted on</a></th>";
                    $ord = "ASC";
                } elseif (isset($_GET['ord']) && $_GET['ord'] == "dsc") {
                    echo "&ord=asc'>Posted on</a></th>";
                    $ord = "DESC";
                } else {
                    echo "&ord=asc'>Posted on</a></th>";
                    $ord = "ASC";
                }
            
                echo "<th><a href='view_pages.php?sort=by";
                if (isset($_GET['ord']) && $_GET['ord'] == "asc") {
                    echo "&ord=dsc'>Posted by</a></th>";
                    $ord = "ASC";
                } elseif (isset($_GET['ord']) && $_GET['ord'] == "dsc") {
                    echo "&ord=asc'>Posted by</a></th>";
                    $ord = "DESC";
                } else {
                    echo "&ord=asc'>Posted by</a></th>";
                    $ord = "ASC";
                }
			?>
            
            <th>Edit</th>
            <th>Delete</th>
		</tr>
	</thead>
	<tbody>
    <?php
        // sap xep cot theo thu tu cua table head
            if (isset($_GET['sort'])) {
                switch ($_GET['sort']) {
                    case 'name':
                    $order_by = 'page_name';
                    break;
                    case 'on':
                    $order_by = 'date';
                    break;
                    case 'by':
                    $order_by = 'name';
                    break;
                    default:
                    $order_by = 'date';
                    break;
                    //de phong truong hop nguoi dung go~ linh tinh vao phan sort
                }//end switch
            } else {
                $order_by = 'date';
            }
        
        
        //truy xuat csdl de hien thi categories
        $q = "SELECT p.page_id, p.page_name, DATE_FORMAT(p.post_on, '%b %d %Y') AS date, CONCAT_WS(' ', first_name, last_name) AS name, p.content";
        $q .= " FROM pages AS p";
        $q .= " JOIN users AS u";
        $q .= " USING(user_id)";
        $q .= " ORDER BY {$order_by} {$ord}";
        //muon biet la da co nhung user nao post trong category nay
        //ki thuat noi bien cau lenh mysql, khi kiem tra loi co the chuyen cau lenh thanh comment
        $r = mysqli_query($dbc, $q);
        confirm_query($r, $q);
        if (mysqli_num_rows($r) >0 ) {
            //neu co page de hien thi, lay page de hien thi ra
            while ($pages = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
                echo "
                    <tr>
                        <td><a class = 'edit' href = '".BASE_URL."single.php?pid={$pages['page_id']}'>{$pages['page_name']}</a></td>
                        <td>{$pages['date']}</td>
                        <td>{$pages['name']}</td>
                        
                        <td><a class = 'edit' href = 'edit_pages.php?pid={$pages['page_id']}'>Edit</a></td>
                        <td><a class = 'delete' href = 'delete_pages.php?pid={$pages['page_id']}&&pn={$pages['page_name']}'>Delete</a></td>
                </tr>
                ";
            } //ket thuc while loop
        } else {
            //neu khong co page de hien thi
            $messages = "<p class = 'warning'>There is currently no page to display. Please create a page first.</p>";
        }
    ?>
	</tbody>
</table>
</div><!--end content-->

<?php include('../includes/sidebar-b.php'); ?>
<?php include('../includes/footer.php'); ?>