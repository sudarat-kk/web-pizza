<?php
include("connect.php");

$response = ['success' => false];

if (isset($_POST['orderID'])) {
    $orderID = $_POST['orderID'];

    // ตรวจสอบ Paystatus ก่อนเพื่อมั่นใจว่าไม่ได้จ่ายเงินแล้ว
    $stmt = $conn->prepare("UPDATE combinedorder SET Paystatus = 'จ่ายเงินแล้ว' WHERE OrderID = ? AND Paystatus = 'ยังไม่จ่ายเงิน'");
    $stmt->bind_param("i", $orderID);

    if ($stmt->execute() && $stmt->affected_rows > 0) {
        $response['success'] = true;
    }

    $stmt->close();
}

echo json_encode($response);

