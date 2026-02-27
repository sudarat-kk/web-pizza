<?php
session_start();
include("connect.php");

if (!isset($_SESSION["email"])) {
    header("Location: index.php");
    exit;
}

if (!isset($_POST["selectedItems"])) {
    header("Location: showcart.php");
    exit;
}

// ข้อมูลจากหน้า showcart.php
$totalPrice = $_POST["totalPrice"];
$userID = $_SESSION['UserID'];

// echo "UserID: " . $userID;
// exit;

// สร้าง order ใหม่
$sqlOrder = "INSERT INTO combinedorder (UserID,totalPrice,Paystatus,Address,status,OrderDate) VALUES (?,?,'ยังไม่จ่ายเงิน','','ยังไม่จัดส่ง',NOW())";
$stmtOrder = $conn->prepare($sqlOrder);
$stmtOrder->bind_param("ii", $userID, $totalPrice);
$stmtOrder->execute();
$orderID = $conn->insert_id; // รับ orderID ที่ถูกสร้างขึ้นใหม่

$pizzaIDs = $_POST['PizzaID'];
$sizeIDs = $_POST['SizeID'];
$crustIDs = $_POST['CrustID'];
$quantities = $_POST['Quantity'];
$calculatedPrices = $_POST['calculatedPrices'];

for ($i = 0; $i < count($pizzaIDs); $i++) {
    // เพิ่มรายการพิซซ่าใน orderDetails
    $sqlDetail = "INSERT INTO orderdetail (OrderID, PizzaID, SizeID, CrustID, Quantity, UnitPrice) VALUES (?, ?, ?, ?, ?, ?)";
    $stmtDetail = $conn->prepare($sqlDetail);
    $stmtDetail->bind_param("iiiiid", $orderID, $pizzaIDs[$i], $sizeIDs[$i], $crustIDs[$i], $quantities[$i], $calculatedPrices[$i]);
    $stmtDetail->execute();
}
// ลบรายการสินค้าในตระกร้า
$stmt = $conn->prepare("DELETE FROM cartdetail WHERE UserID = ?");
$stmt->bind_param("i", $userID);
if (!$stmt->execute()) {
    die("Error: " . $stmt->error);
}
$stmt->close();
// และต่อไปคือการแสดงหน้ายืนยันการสั่งซื้อ หรือ redirect ไปยังหน้าอื่น


header("Location: confirmation.php?orderID=" . $orderID);
