<?php

// เริ่ม session
session_start();

// เชื่อมต่อฐานข้อมูล
include("connect.php");

if (isset($_SESSION["calculatedPrice"])) {
    $price = $_SESSION["calculatedPrice"];
    // แสดงราคาในตารางของคุณ
}


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


$userID = $_GET['UserID'];
$sql = "SELECT * FROM combinedorder WHERE UserID = ? ORDER BY orderID DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userID);
$stmt->execute();
$result = $stmt->get_result();

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-image: url('https://i.pinimg.com/originals/71/4a/19/714a19d8d8ec30847d945076ca4fa3fd.png');
            padding-top: 80px;
            /* ความสูงของ navbar ปกติเป็น 56px สำหรับ navbar ปกติของ Bootstrap แต่คุณควรปรับค่านี้ให้เหมาะสมกับความสูงของ navbar ของคุณ */
        }

        .custom-table {
            font-size: 18px;
            /* ค่านี้สามารถปรับเปลี่ยนตามความต้องการ */
        }

        .navbar {
            display: flex;
            background-color: rgb(0, 91, 65);
        }
        /* เพิ่มสีพื้นหลังสำหรับแถวที่จ่ายเงินแล้ว */
    .paid {
        background-color: #007BFF; /* สีน้ำเงินหรือสีที่คุณต้องการ */
        color: white; /* สีตัวอักษรของข้อความในแถว */
    }

    /* เพิ่มสีพื้นหลังสำหรับแถวที่จัดส่งแล้ว */
    .delivered {
        background-color: #28A745; /* สีเขียวหรือสีที่คุณต้องการ */
        color: white; /* สีตัวอักษรของข้อความในแถว */
    }
    </style>
</head>

<body>
    <div class="navbar fixed-top">
        <div id="logo" style="display: flex; align-items: center;">
            <img src="https://cdn.iconscout.com/icon/free/png-256/free-care-emoji-with-pizza-2419210-2012659.png?f=webp" alt="Logo" width="50">
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
                <li><a class="dropdown-item" href="#">รายการสั่งซื้อ</a></li>
                <li><a class="dropdown-item" href="index.php">ออกจากระบบ</a></li>
            </ul>
        </div>
    </div>

    <div class="container mt-5">
    <table class="table table-bordered table-striped custom-table">
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Status</th>
                <th>PayStatus</th>
                <th>Total Price</th>
                <th>Order Date</th>
                <th>Address</th>
                <th>Phone</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()) : ?>
                <?php
                $statusClass = '';
                if ($row['Paystatus'] == 'จ่ายเงินแล้ว') {
                    $statusClass = 'paid';
                } elseif ($row['Status'] == 'จัดส่งแล้ว') {
                    $statusClass = 'delivered';
                }
                ?>
                <tr class="<?php echo $statusClass; ?>">
                    <td><?php echo $row['OrderID']; ?></td>
                    <td><?php echo $row['Status']; ?></td>
                    <td><?php echo $row['Paystatus']; ?></td>
                    <td><?php echo $row['totalPrice']; ?></td>
                    <td><?php echo $row['OrderDate']; ?></td>
                    <td>ที่อยู่: <?php echo $row['Address']; ?></td>
                    <td>เบอร์โทร: <?php echo $row['phone']; ?></td>
                    <td><a href="confirmation.php?orderID=<?php echo $row['OrderID']; ?>" class="btn btn-primary">ดูรายละเอียด</a></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>


</body>


<!-- ต้องมี JS ของ Bootstrap หากคุณใช้ Bootstrap -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
<script>
  // ค้นหาแถวที่มีสถานะ "ชำระเงินแล้ว" และเปลี่ยนสีเป็นน้ำเงิน
var paidRows = document.querySelectorAll('.paid');
paidRows.forEach(function(row) {
    row.style.backgroundColor = '#007BFF'; // สีน้ำเงินหรือสีที่คุณต้องการ
    row.style.color = 'white'; // สีตัวอักษรของข้อความในแถว
});

// ค้นหาแถวที่มีสถานะ "จัดส่งแล้ว" และเปลี่ยนสีเป็นเขียว
var deliveredRows = document.querySelectorAll('.delivered');
deliveredRows.forEach(function(row) {
    row.style.backgroundColor = '#28A745'; // สีเขียวหรือสีที่คุณต้องการ
    row.style.color = 'white'; // สีตัวอักษรของข้อความในแถว
});  
</script>


</body>

</html>