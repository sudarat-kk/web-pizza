<?php
// เชื่อมต่อฐานข้อมูล
include("connect.php");

// ตรวจสอบว่ามี CartDetailID ที่ส่งมาจาก GET หรือไม่
if (isset($_GET['CartDetailID'])) {
    $cartDetailID = $_GET['CartDetailID'];

    // สร้างคำสั่ง SQL ลบรายการในตะกร้า
    $sql = "DELETE FROM cartdetail WHERE CartDetailID = ?";
    
    // เตรียมคำสั่ง SQL
    $stmt = $conn->prepare($sql);
    
    // ผูกค่าที่จะนำไปใส่ในคำสั่ง SQL
    $stmt->bind_param("i", $cartDetailID);
    
    // ทำการเรียกใช้คำสั่ง SQL
    if ($stmt->execute()) {
        header("Location: showcart.php");  // คืนกลับไปยังหน้าตะกร้า
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
} else {
    header("Location: showcart.php");
}
?>
