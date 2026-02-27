<?php
// เริ่ม session
session_start();
include("connect.php");

// เช็คว่ามี session email หรือไม่
if (!isset($_SESSION["email"])) {
    echo "<script>
    alert('กรุณาเข้าสู่ระบบ!');
</script>";
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
$sql = "SELECT combinedorder.OrderID, combinedorder.UserID, combinedorder.Status, combinedorder.Address, combinedorder.phone, combinedorder.Paystatus, combinedorder.totalPrice, combinedorder.OrderDate, User.name AS UserName FROM combinedorder INNER JOIN User ON combinedorder.UserID = User.UserID ORDER BY combinedorder.OrderID DESC";
$result = $conn->query($sql);



// ดึงข้อมูลลูกค้าทุกคน
$sql_customers = "SELECT * FROM User WHERE Role = 'Customer'";
$customers = $conn->query($sql_customers);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="infoshopowner.css">
    <title>shopowner</title>

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
                        <input type="text" placeholder="ค้นไส....." class="form-control search-input" name="search" required>
                    </div>
                    <div class="col-6 d-flex justify-content-end">
                        <input type="submit" name="v_search" value="search" class="btn btn-info">
                    </div>
                </div>
            </form>
        </div>


        <?php
        if ($user_row["Role"] == "ShopOwner") {
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
                <li><a class="dropdown-item" href="shopowner.php">หน้าหลัก</a></li>
                <li><a class="dropdown-item" href="infoshopowner.php">ข้อมูลส่วนตัว</a></li>
                <li><a class="dropdown-item" href="index.php">ออกจากระบบ</a></li>
            </ul>
        </div>
    </div>
    <div class="container mt-5">

        <h3>ลูกค้าทั้งหมด:</h3>
        <select name="customer">
            <?php while ($customer = $customers->fetch_assoc()) : ?>
                <option value="<?php echo $customer['UserID']; ?>"><?php echo $customer['name']; ?></option>
            <?php endwhile; ?>
        </select>

        <h3>รายการสั่งซื้อทั้งหมด:</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>UserID</th>
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
    <tr>
        <td><?php echo $row['OrderID']; ?></td>
        <td><?php echo $row['UserName']; ?></td>
        <td>
            <form action='update_status.php' method='post'>
                <input type='hidden' name='OrderID' value='<?php echo $row['OrderID']; ?>'>
                <select name='status' onchange='confirmStatusChange(this.form);'>
                    <option value='ยังไม่จัดส่ง' <?php echo ($row['Status'] == "ยังไม่จัดส่ง" ? "selected" : ""); ?>>ยังไม่จัดส่ง</option>
                    <option value='จัดส่งแล้ว' <?php echo ($row['Status'] == "จัดส่งแล้ว" ? "selected" : ""); ?>>จัดส่งแล้ว</option>
                </select>
            </form>
        </td>
        <td><?php echo $row['Paystatus']; ?></td>
        <td><?php echo $row['totalPrice']; ?></td>
        <td><?php echo $row['OrderDate']; ?></td>
        <td><?php echo $row['Address']; ?></td>
        <td><?php echo $row['phone']; ?></td>
        <td><a href="confirmation.php?orderID=<?php echo $row['OrderID']; ?>" class="btn btn-primary">ดูรายละเอียด</a></td>
    </tr>
<?php endwhile; ?>

            </tbody>

        </table>

    </div>






    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
    <script>
function confirmStatusChange(form) {
    var selectedStatus = form.status.options[form.status.selectedIndex].value;
    
    if (selectedStatus == 'จัดส่งแล้ว') {
        var confirmation = confirm('ยืนยันการเปลี่ยนสถานะเป็น "จัดส่งแล้ว" หรือไม่?');
        if (confirmation) {
            form.submit();
        } else {
            // รีเซ็ตเลือกสถานะกลับไปยังสถานะเดิม
            form.status.value = '<?php echo $row['Status']; ?>';
        }
    } else {
        form.submit();
    }
}
</script>

</body>

</html>