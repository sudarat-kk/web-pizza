<?php

// เริ่ม session
session_start();

// เชื่อมต่อฐานข้อมูล
include("connect.php");

// ตรวจสอบว่ามีค่า OrderID และ status หรือไม่
if (isset($_POST['OrderID']) && isset($_POST['status'])) {
    $orderID = $_POST['OrderID'];
    $status = $_POST['status'];

    // ประกาศ SQL statement สำหรับอัปเดทฐานข้อมูล
    $sql = "UPDATE combinedorder SET Status = ? WHERE OrderID = ?";
    
    // ทำการ prepare statement
    $stmt = $conn->prepare($sql);

    // ผูกค่า
    $stmt->bind_param("si", $status, $orderID);

    // ทำการ execute statement
    if ($stmt->execute()) {
        // หากอัปเดทสำเร็จ ให้เปลี่ยนเส้นทางไปยัง shopwoneroage.php
        header("Location: shopowner.php");
        exit;
    } else {
        echo "เกิดข้อผิดพลาด: " . $stmt->error;
    }

    // ปิด statement
    $stmt->close();
} else {
    echo "ไม่พบค่าที่จำเป็นสำหรับการอัปเดท.";
}

?>