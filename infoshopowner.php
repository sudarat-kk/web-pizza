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
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="infoshopowner.css">
    <title>infomation</title>

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
        if ($user_row["Role"] == "ShopOwner") {
        ?>
            <img src="<?php echo $user_row["url"]; ?>" alt="Profile Picture" width="60px" style="object-fit: cover;" class="rounded-circle">
            <div style="display: flex; flex-direction: column; align-items: flex-end; margin-right: 50px;">
                <h6 style="font-weight: bolder; margin: 0;"><?php echo $user_row["name"]; ?></h6>
                <h6 style="font-weight: bolder; margin: 0;">สถานะ : <?php echo $user_row["Role"]; ?></h6>
            </div>
        <?php
        }
        ?>

        <div class="cart-icon" style="position: relative; margin-right: 50px;">
            <img src="https://www.freeiconspng.com/thumbs/cart-icon/basket-cart-icon-27.png" alt="" width="50px">
            <div style="position: absolute; top: -10px; left: 35px; background-color: red; width: 20px; height: 20px; border-radius: 50%; display: flex; justify-content: center; align-items: center; color: white; font-weight: bold;">
                0 <!-- ตัวเลขในตะกร้า เผื่อไว้ใช้ใน php -->
            </div>
        </div>


        <div style="display: flex; flex-direction: column; align-items: flex-end; margin-right: 50px;">
            <div data-bs-toggle="dropdown">
                <img src="https://www.iconpacks.net/icons/2/free-settings-icon-3110-thumb.png" alt="setting" width="40px" height="40px" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            </div>
            <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="shopowner.php">หน้าหลัก</a></li>
                <li><a class="dropdown-item" href="infoshopowner.css">ข้อมูลส่วนตัว</a></li>
                <li><a class="dropdown-item" href="#">รายการสั่งซื้อ</a></li>
                <li><a class="dropdown-item" href="index.php">ออกจากระบบ</a></li>
            </ul>
        </div>
    </div>


    <div class="container">
        <div class="d-grid justify-content-center align-items-center" style="min-height: 100vh;">
            <div class="card" style="width: 600px; height: 830px;">
                <div class="card-body">
                    <div class="text-center">
                    <h3>ข้อมูลส่วนตัว</h3>
                        <?php
                        if ($user_row["Role"] == "ShopOwner") {
                        ?>
                            <img src="<?php echo $user_row["url"] ?>" class="card-img profile-image" alt="...">
                            <div class="card-body">
                                <div class="text-border">
                                    <h2 class="card-title">Name: <?php echo $user_row["name"]; ?></h2>
                                </div>
                                <br>
                                <div class="text-border" style="color: white; font-weight: bold;">
                                    <h3 class="card-text"> <?php echo $user_row["Role"]; ?></h3>
                                </div>
                                <br>

                                <div class="text-border">
                                    <p>Address</p>
                                </div>
                                <br>

                            </div>
                        <?php
                        }
                        ?>
                        <br><br>
                        <a href="shopowner.php" class="btn btn-success">Back</a>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
</body>

</html>