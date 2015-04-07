<?php
    $title = "Manage Users";
    include('../includes/header.php');
    require_once('../includes/mysqli_connect.php');
    //require once nghia la neu khong include dc thi dung` code het, khong chay nua
    require_once('../includes/function.php');
    include('../includes/sidebar-admin.php');
    // Check to see if has admin access
    admin_access();  
?>
<div id="content">
<h2>Manage Users</h2>
    <table>
<thead>
	<tr>
		<th><a href="manage_users.php?sort=fn">First Name</a></th>
		<th><a href="manage_users.php?sort=ln">Last Name</a></th>
		<th><a href="manage_users.php?sort=e">Email</a></th>
        <th><a href="manage_users.php?sort=ul">User Level</a></th>
        <th>Edit User</th>
        <th>Delete User</th>
	</tr>
</thead>
<tbody>
    <?php 
        // Kiem tra xem bien sort ton tai hay khong?
        $sort = (isset($_GET['sort'])) ? $_GET['sort'] : 'fn';

        // Sap xep thu tu cua bang bang bien
        $order_by = sort_table_users($sort);
        
        // Lay thong tin nguoi dung tu CSDL
        //$users = fetch_users("first_name");
        global $dbc;
        $q = "SELECT * FROM users";
        $r = mysqli_query($dbc, $q);
        confirm_query($r, $q);
        if (mysqli_num_rows($r) > 0) {
            // tao ra mot array de luu lai ket qua
            $users = array();
            while($results = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
                echo "
                <tr>
                    <td>".$results['first_name']."</td>
                    <td>".$results['last_name']."</td>
                    <td>".$results['email']."</td>
                    <td>".$results['user_level']."</td>
                    <td><a class='edit' href='edit_user_2.php?uid=".urlencode($results['user_id'])."'>Edit</a></td>
                    <td><a class='delete' href='delete_user.php?uid=".urlencode($results['user_id'])."'>Delete</a></td>
                <tr>";
            }
            return $users;
        } else {
            echo "There are no users to display";
        }

      
    ?>
   </tbody>
</table>
</div>
    
<?php include('../includes/footer.php'); ?>