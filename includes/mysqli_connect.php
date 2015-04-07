<?php
// Ket noi co so du lieu
$dbc = mysqli_connect('localhost','root','','izcms');
//Thong bao ket noi khong thanh cong
if(!$dbc) {
    trigger_error("Khong the ket noi den co so du lieu: ".mysqli_connect_error());
} else {
    //dat phuong thuc ket noi la utf-8
    mysqli_set_charset($dbc,'utf-8');
}
?>