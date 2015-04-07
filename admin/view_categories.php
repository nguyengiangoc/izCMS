<?php include('../includes/header.php'); ?>
<?php include('../includes/mysqli_connect.php');?>
<?php include('../includes/function.php'); ?>
<?php include('../includes/sidebar-admin.php'); ?>

<div id="content">
<h2>Manage Categories</h2>
<table>
	<thead>
		<tr>
            <th>Categories<br />
            <a href="view_categories.php?sort=cat&ord=asc">ASC</a> <a href="view_categories.php?sort=cat&ord=dsc">DSC</a>
            </th>
            <th>Position<br />
            <a href="view_categories.php?sort=pos&ord=asc">ASC</a> <a href="view_categories.php?sort=pos&ord=dsc">DSC</a>
            </th>
            <th>Posted by<br />
            <a href="view_categories.php?sort=by&ord=asc">ASC</a> <a href="view_categories.php?sort=by&ord=dsc">DSC</a>
            </th>
            <th>Edit</th>
            <th>Delete</th>
		</tr>
	</thead>
	<tbody>
    <?php
        // sap xep cot theo thu tu cua table head
            if (isset($_GET['sort'])) {
                switch ($_GET['sort']) {
                    case 'cat':
                    $order_by = 'cat_name';
                    break;
                    case 'pos':
                    $order_by = 'position';
                    break;
                    case 'by':
                    $order_by = 'name';
                    break;
                    default:
                    $order_by = 'position';
                    break;
                    //de phong truong hop nguoi dung go~ linh tinh vao phan sort
                }//end switch
            } else {
                $order_by = 'position';
            }
            //chon thu tu sap xep
            if (isset($_GET['ord']) && $_GET['ord'] == 'dsc') {
                    $ord = 'DESC';
                    //switch ($_GET['ord']) {
                        //case 'asc':
                        //$ord = 'ASC';
                        //break;
                        //case 'dsc':
                        //$ord = 'DESC';
                        //default:
                        //$ord = 'ASC';
                        //break;
                        //de phong truong hop nguoi dung go~ linh tinh vao phan sort
                    //}end switch
                } else {
                    $ord = 'ASC';
                }
        
        //truy xuat csdl de hien thi categories
        $q = "SELECT c.cat_id, c.cat_name, c.position, c.user_id, CONCAT_WS(' ', first_name, last_name) AS name";
        $q .= " FROM categories AS c";
        $q .= " JOIN users AS u";
        $q .= " USING(user_id)";
        $q .= " ORDER BY {$order_by} {$ord}";
        //muon biet la da co nhung user nao post trong category nay
        //ki thuat noi bien cau lenh mysql, khi kiem tra loi co the chuyen cau lenh thanh comment
        $r = mysqli_query($dbc, $q);
        confirm_query($r, $q);
        while ($cats = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
            echo "
                <tr>
                    <td><a class = 'edit' href = '".BASE_URL."index.php?cid={$cats['cat_id']}' >{$cats['cat_name']}</a></td>
                    <td>{$cats['position']}</td>
                    <td>{$cats['name']}</td>
                    <td><a class = 'edit' href = 'edit_categories.php?cid={$cats['cat_id']}'>Edit</a></td>
                    <td><a class = 'delete' href = 'delete_categories.php?cid={$cats['cat_id']}&cat_name={$cats['cat_name']}'>Delete</a></td>
            </tr>
            ";
        }
    ?>
	</tbody>
</table>
</div><!--end content-->

<?php include('../includes/sidebar-b.php'); ?>
<?php include('../includes/footer.php'); ?>