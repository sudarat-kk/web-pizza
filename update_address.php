<?php
include("connect.php");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["OrderID"]) && isset($_POST["address"]) && isset($_POST["phone"])) {
    $orderID = $_POST["OrderID"];
    $address = "ที่อยู่: " . $_POST["address"]; // Automatically prepend "ที่อยู่: "
    $phone = "เบอร์โทร: " .$_POST["phone"];
    
    $sql = "UPDATE combinedorder SET Address = ?, Phone = ? WHERE OrderID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $address, $phone, $orderID);
    
    if ($stmt->execute()) {
        header("Location: confirmation.php?OrderID=" . $orderID);
    } else {
        echo "Error: " . $stmt->error;
    }
    
    $stmt->close();
}
?>



