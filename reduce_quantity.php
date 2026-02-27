<?php
session_start();
include("connect.php");

// ตรวจสอบว่ามี parameter CartDetailID มาหรือไม่
if(isset($_GET['CartDetailID'])) {
    $cartdetailID = $_GET['CartDetailID'];

    // หา quantity ของรายการนั้น
    $sql = "SELECT Quantity FROM cartdetail WHERE CartDetailID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $cartdetailID);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $currentQuantity = $row['Quantity'];

    if($currentQuantity > 1) {
        $newQuantity = $currentQuantity - 1;
        $sql = "UPDATE cartdetail SET Quantity = ? WHERE CartDetailID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $newQuantity, $cartdetailID);
        $stmt->execute();
    } else {
        // ถ้า quantity = 1 ลบรายการนั้นออก
        $sql = "DELETE FROM cartdetail WHERE CartDetailID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $cartdetailID);
        $stmt->execute();

        echo "<script>
            var r = confirm('คุณต้องการลบรายการนี้ใช่หรือไม่?');
            if (r == true) {
                window.location.href = 'delete_cart_item.php?CartDetailID=' + $cartdetailID;
            } else {
                window.location.href = 'showcart.php';
            }
        </script>";
        exit;
    }

    header("Location: showcart.php");
    exit;
} else {
    die("Error: Invalid request.");
}
?>
