<?php

// เริ่ม session
session_start();

// เชื่อมต่อฐานข้อมูล
include("connect.php");

// เช็คว่ามี session email หรือไม่
if (!isset($_SESSION["email"])) {
    echo "<script>alert('กรุณาเข้าสู่ระบบ!');</script>";
    header("Location: index.php");
    exit;
}

// เช็คว่ามี session UserID หรือไม่
if (!isset($_SESSION['UserID'])) {
    die("กรุณาเข้าสู่ระบบ!");
}

$userid = $_SESSION['UserID'];


$stmt = $conn->prepare("SELECT Username,name, Role, url FROM User WHERE UserID = ?");
$stmt->bind_param("i", $userid);

$stmt->execute();

$result = $stmt->get_result();
if ($result->num_rows > 0) {
    $user_row = $result->fetch_assoc();
} else {
    die("ไม่พบข้อมูลผู้ใช้!");
}

$stmt->close();

?>

<?php
include("connect.php");

// ดึง OrderID จาก session
// รับ OrderID จาก URL แทนที่จะรับจาก $_SESSION
if (!isset($_GET['orderID'])) {
    die("Order ID not found.");
}
$orderID = $_GET['orderID'];

$stmt = $conn->prepare("
    SELECT 
        orderdetail.PizzaID, 
        orderdetail.SizeID, 
        orderdetail.CrustID, 
        pizza.Name AS PizzaName,
        pizza.ImageURL,  
        size.SizeName,
        crust.CrustType, 
        orderdetail.Quantity,
        orderdetail.UnitPrice
    FROM 
        orderdetail
    INNER JOIN 
        pizza ON pizza.PizzaID = orderdetail.PizzaID
    INNER JOIN
        size ON size.SizeID = orderdetail.SizeID
    INNER JOIN
        crust ON crust.CrustID = orderdetail.CrustID
    WHERE 
        orderdetail.OrderID = ?
");
$stmt->bind_param('i', $orderID);
$stmt->execute();
$result = $stmt->get_result();

$addressStmt = $conn->prepare("SELECT Address FROM combinedorder WHERE OrderID = ?");
$addressStmt->bind_param('i', $orderID);
$addressStmt->execute();
$addressResult = $addressStmt->get_result();
if ($addressRow = $addressResult->fetch_assoc()) {
    $address = $addressRow['Address'];
} else {
    $address = null;
}
$addressStmt->close();


$phoneStmt = $conn->prepare("SELECT Phone FROM combinedorder WHERE OrderID = ?");
$phoneStmt->bind_param('i', $orderID);
$phoneStmt->execute();
$phoneResult = $phoneStmt->get_result();
if ($phoneRow = $phoneResult->fetch_assoc()) {
    $phone = $phoneRow['Phone'];
} else {
    $phone = null; // ถ้าไม่พบข้อมูลเบอร์โทรศัพท์
}
$phoneStmt->close();


//ดึงราคามาแสดง
$totalPriceStmt = $conn->prepare("SELECT totalPrice FROM combinedorder WHERE OrderID = ?");
$totalPriceStmt->bind_param('i', $orderID);
$totalPriceStmt->execute();
$totalPriceResult = $totalPriceStmt->get_result();
if ($totalPriceRow = $totalPriceResult->fetch_assoc()) {
    $totalPrice = $totalPriceRow['totalPrice'];
} else {
    die("ไม่พบข้อมูลราคาทั้งหมด!");
}
$totalPriceStmt->close();


$paymentStatusStmt = $conn->prepare("SELECT Paystatus FROM combinedorder WHERE OrderID = ?");
$paymentStatusStmt->bind_param('i', $orderID);
$paymentStatusStmt->execute();
$paymentStatusResult = $paymentStatusStmt->get_result();
if ($paymentStatusRow = $paymentStatusResult->fetch_assoc()) {
    $isPaid = $paymentStatusRow['Paystatus'] == 'จ่ายเงินแล้ว';
} else {
    die("ไม่พบข้อมูลสถานะการชำระเงิน!");
}
$paymentStatusStmt->close();


?>


<!DOCTYPE html>
<html lang="en">

<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <title>Confirmation</title>
    <style>
        .search-input {
            width: 600px;
            /* เปลี่ยนค่าตามที่คุณต้องการ */
        }

        body {
            padding-top: 80px;
            /* ความสูงของ navbar + สัก 20px เพื่อการเว้นวรรค */
            background-image: url("https://wallpaperaccess.com/full/8711154.jpg");
            font-family: 'Poppins', sans-serif;
            background-size: cover;
            /* ให้ภาพเต็มเฟรม */
            background-position: center;
            /* ให้ภาพอยู่กึ่งกลาง */
            background-attachment: fixed;
            /* ภาพพื้นหลังคงที่ */
        }

        .navbar {
            display: flex;
            /* justify-content: center; */
            background-color: rgb(24, 111, 101);
            ;
        }

        .responsive-card {
            width: auto;
            /* ความกว้างเท่ากับรูปภาพ */
            display: inline-block;
            /* ให้การ์ดจัดเรียงต่อกันแบบไม่ขึ้นบรรทัดใหม่ */
        }


        .custom-image-size {
            width: 250px;
            height: 250px;
            object-fit: cover;
            /* ให้รูปภาพปรับขนาดเต็มพื้นที่และครอบตัดตามพื้นที่ที่กำหนด */
        }

        .total-price-section {
            position: fixed;
            top: 100px;
            /* ความสูงของ navbar + สัก 20px เพื่อการเว้นวรรค */
            right: 20px;
            background-color: white;
            padding: 10px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
        }
    </style>
</head>

<body>
    <div class="navbar fixed-top">
        <div id="logo" style="display: flex; align-items: center;">
            <a href="customerpage.php"><img src="https://cdn.iconscout.com/icon/free/png-256/free-care-emoji-with-pizza-2419210-2012659.png?f=webp" alt="Logo" width="50"></a>
            <h2 style="color: white; margin-left: 10px;">Pizza Makima</h2>
        </div>

        <div style="margin-left: 150px; margin-right: auto;">
            <form class="form-inline my-2 my-lg-0">
                <div class="row">
                    <div class="col-6">
                        <input type="text" placeholder="ค้นหาพิซซ่า....." class="form-control search-input" name="search" required>
                    </div>
                    <div class="col-6 d-flex justify-content-end">
                        <input type="submit" name="v_search" value="search" class="btn btn-info">
                    </div>
                </div>
            </form>
        </div>


        <?php
        if ($user_row["Role"] == "Customer") {
        ?>
            <img src="<?php echo $user_row["url"]; ?>" alt="Profile Picture" width="60px" style="object-fit: cover;" class="rounded-circle">
            <div style="display: flex; flex-direction: column; align-items: flex-end; margin-right: 50px;">
                <h6 style="font-weight: bolder; margin: 0;"><?php echo $user_row["name"]; ?></h6>
                <h6 style="font-weight: bolder; margin: 0;">สถานะ : <?php echo $user_row["Role"]; ?></h6>
                <h6 style="font-weight: bolder; margin: 0;"> <?php echo $user_row["Username"]; ?></h6>
            </div>
        <?php
        }
        ?>






        <div style="display: flex; flex-direction: column; align-items: flex-end; margin-right: 50px;">
            <div data-bs-toggle="dropdown">
                <img src="https://www.iconpacks.net/icons/2/free-settings-icon-3110-thumb.png" alt="setting" width="40px" height="40px" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            </div>
            <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="customerpage.php">หน้าหลัก</a></li>
                <li><a class="dropdown-item" href="infocustomer.php">ข้อมูลส่วนตัว</a></li>
                <li><a class="dropdown-item" href="Order.php?UserID=<?php echo $userID; ?>">ออร์เดอร์ของคุณ</a></li>
                <li><a class="dropdown-item" href="index.php">ออกจากระบบ</a></li>
            </ul>
        </div>
    </div>


    <div class="total-price-section d-flex justify-content-end align-items-center flex-column mb-5">
    <?php if ($address) : ?>
        <p class="mb-2">ที่อยู่สำหรับจัดส่ง:</p>
        <p><?php echo $address; ?></p>
        <p class="mb-2"> <?php echo $phone; ?></p>
    <?php else : ?>
        <p class="mb-2 text-danger">เพิ่มที่อยู่ของคุณ</p>
        <a id='addAddressButton' class="btn btn-warning mb-2">เพิ่มข้อมูลที่อยู่</a>
    <?php endif; ?>
    <h3 class="mr-3">ราคาทั้งหมด: <?php echo $totalPrice; ?> บาท</h3>
    <?php if (!$isPaid) : ?>
        <a class="btn btn-primary mt-3" id="paymentBtn">ชำระเงิน</a>
    <?php endif; ?>
</div>




    <div class="container mt-5">
        <?php
        while ($item = $result->fetch_assoc()) {
        ?>
            <div class="card mb-3 responsive-card">
                <div class="card-header">
                    Pizza Name: <?php echo $item['PizzaName']; ?>
                </div>
                <div class="card-body">
                    <img src="<?php echo $item["ImageURL"]; ?>" class="card-img-top custom-image-size" alt="Pizza Image">
                    <br>
                    <strong>Quantity:</strong> <?php echo $item['Quantity']; ?><br>
                    <strong>Size:</strong> <?php echo $item['SizeName']; ?><br>
                    <strong>Crust:</strong> <?php echo $item['CrustType']; ?><br>
                    <strong>Price:</strong> <?php echo $item['UnitPrice']; ?> บาท
                </div>
            </div>
        <?php
        }
        ?>
    </div>
    <!-- Payment Confirmation Modal -->
    <div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="paymentModalLabel">ยืนยันการชำระเงิน</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <img src="https://cdn.discordapp.com/attachments/992063935471693876/1168315580051378227/IMG_3878.png?ex=655151ac&is=653edcac&hm=af93e432230ab4cbe34eb7e41e5d06c7218a4fbdc0b3831946309dc40125831c&" alt="Payment Image" class="img-fluid">
                    <p>กรุณายืนยันการชำระเงินของคุณ</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                    <button type="button" class="btn btn-primary" id="confirmPayment">ยืนยัน</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
<div class="modal fade" id="yourModalId" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addressModalLabel">เพิ่มที่อยู่จัดส่ง</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="update_address.php" method="post">
    <div class="modal-body">
        <input type="hidden" name="OrderID" value="<?php echo $orderID; ?>">
        <!-- เพิ่มคำว่า "ที่อยู่: " ข้างหน้า textarea -->
        <label for="address">ที่อยู่:</label>
        <textarea name="address" rows="4" class="form-control" placeholder="กรุณากรอกที่อยู่"></textarea>
        <br>
        <label for="phone">เบอร์โทรศัพท์:</label>
        <input type="text" name="phone" class="form-control" placeholder="กรุณากรอกเบอร์โทรศัพท์">
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
        <button type="submit" class="btn btn-primary">ยืนยัน</button>
    </div>
</form>

        </div>
    </div>
</div>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        document.getElementById('paymentBtn').addEventListener('click', function(event) {
            event.preventDefault(); // ป้องกันการทำงานตามปกติของ <a> tag
            const paymentModal = new bootstrap.Modal(document.getElementById('paymentModal'));
            paymentModal.show();
        });

        const orderID = <?php echo json_encode($orderID); ?>;
        document.getElementById('confirmPayment').addEventListener('click', function() {
            fetch('update_payment_status.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: `orderID=${orderID}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('การชำระเงินสำเร็จ!');
                        location.reload(); // โหลดหน้าใหม่หลังจากอัพเดต
                    } else {
                        alert('เกิดข้อผิดพลาดในการชำระเงิน!');
                    }
                })
                .catch(error => {
                    console.error("Error:", error);
                    alert('เกิดข้อผิดพลาดในการสื่อสารกับเซิร์ฟเวอร์!');
                });
        });

        $(document).ready(function() {
            $("#addAddressButton").click(function() {
                $("#yourModalId").modal('show'); // แทน "yourModalId" ด้วย id ของ modal ที่คุณต้องการแสดง
            });
        });
    </script>
</body>

</html>