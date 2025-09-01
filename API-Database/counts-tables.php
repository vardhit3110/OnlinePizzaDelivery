<?php

include "_dbconnect.php";

header('Content-Type:application/json');

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    echo json_encode([
        "Statuse" => "Request Error",
        "Message" => "Please Change Request Method"
    ]);
    exit();
}
$counts = [];
$tables = ['admin', 'categories', 'contact', 'contactreply', 'delivery_boys', 'feedback', 'item', 'likes', 'orderitems', 'orders', 'reviews', 'sitedetail', 'users', 'orderitems'];
foreach ($tables as $table) {
    $sql = "SELECT * FROM $table";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_num_rows($result);
    $count[$table] = $row;
}

if ($result) {
    echo json_encode([
        "Total Message" => "Count Of Table",
        "Total Admin" => $count['admin'],
        "Total Categories" => $count['categories'],
        "Total Contact" => $count['contact'],
        "Total Contactreply" => $count['contactreply'],
        "Total Delivery_boys" => $count['delivery_boys'],
        "Total Feedback" => $count['feedback'],
        "Total Item" => $count['item'],
        "Total Likes" => $count['likes'],
        "Total Orderitems" => $count['orderitems'],
        "Total Orders" => $count['orders'],
        "Total Reviews" => $count['reviews'],
        "Total Sitedetail" => $count['sitedetail'],
        "Total Users " => $count['users'],
        "Total Viewcart" => $count['orderitems']
    ]);
    exit();
}
?>