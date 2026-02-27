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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <link rel="stylesheet" href="customerpage.css">
    <title>Comtomer Page</title>
    <style>
        body {
            padding-top: 80px;
            /* ความสูงของ navbar + สัก 20px เพื่อการเว้นวรรค */
            background-image: url("https://images.hdqwalls.com/download/anime-city-girl-4k-xl-2560x1080.jpg");
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
            background-color: rgb(183, 96, 215);
        }

        .card {
            background-image: url("https://cdn.create.vista.com/api/media/small/461183668/stock-photo-abstract-colorful-polygonal-background");
            background-size: cover;
            /* แสดงภาพพื้นหลังให้เต็มการ์ด */
            background-position: center;
            /* ตั้งค่าตำแหน่งของภาพพื้นหลังให้อยู่ตรงกลางการ์ด */
            transition: transform 0.3s;
        }


        .card:hover {
            transform: translateY(-10px);
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
                <li><a class="dropdown-item" href="customerpage.php">หน้าหลัก</a></li>
                <li><a class="dropdown-item" href="infocustomer.php">ข้อมูลส่วนตัว</a></li>
                <li><a class="dropdown-item" href="#">รายการสั่งซื้อ</a></li>
                <li><a class="dropdown-item" href="index.php">ออกจากระบบ</a></li>
            </ul>
        </div>
    </div>


    <div class="container mt-5">
        <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2" aria-label="Slide 3"></button>
                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="3" aria-label="Slide 4"></button>
            </div>

            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="https://i.etsystatic.com/32434499/r/il/76ac45/3860443665/il_fullxfull.3860443665_gtbm.jpg" class="d-block w-100" alt="...">
                </div>
                <!-- เพิ่มรูปภาพที่คุณต้องการที่นี่ -->
                <div class="carousel-item">
                    <img src="https://d1csarkz8obe9u.cloudfront.net/posterpreviews/pizza-delivery-take-away-header-banner-pizza-design-template-5d022200977cea21b021452175f60b1a_screen.jpg?ts=1604786816" class="d-block w-100" alt="...">
                </div>
                <div class="carousel-item">
                    <img src="https://d1csarkz8obe9u.cloudfront.net/posterpreviews/pizza-banner-design-template-5623a47ee70d2ca4f3a4eca9c19a8039_screen.jpg?ts=1572691129" class="d-block w-100" alt="...">
                </div>
                <div class="carousel-item">
                    <img src="https://oldcoinprice.com/wp-content/uploads/2020/08/maxresdefault-259.jpg" class="d-block w-100" alt="...">
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </div>
    ิ<br>
    <div class="container-fluid">
        <?php
        include("connect.php");
        $sql = "SELECT PizzaID,Name,Description,ImageURL,Price FROM pizza";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $counter = 0;
            while ($row = $result->fetch_assoc()) {
                if ($counter % 3 == 0) {
                    echo "<div class='row'>";
                }
                echo "
                <div class='card'>
                <br>
                    <img src='{$row["ImageURL"]}' alt='Pizza Image'>
                    <br>
                    <h3>{$row["Name"]}</h3>
                    <h5>ราคาเริ่มต้นที่: {$row["Price"]} ฿</h5>
                    <a href='view.php?PizzaID={$row["PizzaID"]}' class='btn btn-success'>View</a>
                    <br>
                </div>
            ";
                if ($counter % 3 == 2) {
                    echo "</div>";
                }
                $counter++;
            }

            if ($counter % 3 != 0) {
                echo "</div>";
            }
        } else {
            echo "No cards found.";
        }
        ?>
    </div>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
</body>

</html>