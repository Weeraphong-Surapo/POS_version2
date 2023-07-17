<?php 
include('../../config/connect.php');
$data = array();
$query = $conn->query("SELECT * FROM tb_customer");
foreach($query as $row){
    $group = $conn->query("SELECT * FROM tb_group_customer WHERE group_id = '$row[group_id]'");
    $row_group = $group->fetch_array();
    array_push($data,array(
        'fullname'=> $row['customer_fname'].' '.$row['customer_lname'],
        'address'=>$row['customer_address'],
        'group'=> $row_group['group_name']
    ));
}
echo json_encode($data);
?>