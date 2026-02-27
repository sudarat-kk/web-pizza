<?php
session_start();
include("connect.php");

if (isset($_GET['CartDetailID'])) {
    $cartDetailID = $_GET['CartDetailID'];
    $sql = "UPDATE cartdetail SET Quantity = Quantity + 1 WHERE CartDetailID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $cartDetailID);
    $stmt->execute();

    header("Location: showcart.php");
} else {
    echo "ไม่พบรายการในตะกร้า";
}
?>
