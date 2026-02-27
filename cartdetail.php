
<?php
session_start(); // เริ่มต้น session
include("connect.php");


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_SESSION['UserID'])) {
        die("กรุณาเข้าสู่ระบบ!");
    }

    $pizzaID = $_POST["PizzaID"];
    $size = $_POST["SizeID"];
    $crust = $_POST["CrustID"];
    $quantity = $_POST["quantity"];
    $userID = $_SESSION['UserID'];

    // ตรวจสอบรายการที่มีในฐานข้อมูล
    $sql = "SELECT cartdetailID, quantity FROM cartdetail WHERE PizzaID = ? AND SizeID = ? AND CrustID = ? AND UserID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iisi", $pizzaID, $size, $crust, $userID);
    $stmt->execute();
    $result = $stmt->get_result();

    // ถ้าพบรายการ
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $newQuantity = $row["quantity"] + $quantity;
        $cartdetailID = $row["cartdetailID"];

        // อัปเดตจำนวน
        $sql_update = "UPDATE cartdetail SET quantity = ? WHERE cartdetailID = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("ii", $newQuantity, $cartdetailID);
        $stmt_update->execute();
        $stmt_update->close();
    } else {
        // ไม่พบรายการ, ให้สร้างรายการใหม่
        $sql_insert = "INSERT INTO cartdetail (PizzaID, SizeID, CrustID, quantity, UserID) VALUES (?, ?, ?, ?, ?)";
        $stmt_insert = $conn->prepare($sql_insert);
        $stmt_insert->bind_param("iisii", $pizzaID, $size, $crust, $quantity, $userID);
        $stmt_insert->execute();
        $stmt_insert->close();
    }
    $stmt->close();
    header("Location: view.php?PizzaID=" . $pizzaID);
}

