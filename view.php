<?php
include("connect.php");

// ดึง PizzaID จาก URL
$pizza_id = $_GET['PizzaID'];

// สร้าง SQL Query
$sql = "SELECT PizzaID, Name, Description, ImageURL, Price FROM pizza WHERE PizzaID = $pizza_id";


// ดึงข้อมูลจากฐานข้อมูล
$result = $conn->query($sql);
$row = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <link rel="stylesheet" href="view.css">
    <title>View Pizza</title>
</head>

<body>
    <div class="navbar  fixed-top">
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
                    <div class="col-6">
                        <input type="submit" name="v_search" value="search" class="btn btn-info">
                    </div>
                </div>
            </form>
        </div>

        <img src="https://cdn-icons-png.flaticon.com/512/219/219969.png" alt="" width="60px" class="rounded-circle">

        <div style="display: flex; flex-direction: column; align-items: flex-end; margin-right: 50px;">
            <h6 style="font-weight: bolder; margin: 0;">คุณ</h6> <!-- เปลี่ยนเป็นชื่อลูกค้าที่เข้าสู่ระบบ -->
            <h6 style="font-weight: bolder; margin: 0;">สถานะ : ลูกค้า</h6>
        </div>

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
                <li><a class="dropdown-item" href="#">ข้อมูลส่วนตัว</a></li>
                <li><a class="dropdown-item" href="#">รายการสั่งซื้อ</a></li>
                <li><a class="dropdown-item" href="index.html">ออกจากระบบ</a></li>
            </ul>
        </div>
    </div>

    
<div class="container mt-5">
        <div class="row justify-content-center">
        
            <div class="col-md-6">
                <div class="card custom-card">
                    <img src="<?php echo $row["ImageURL"]; ?>" class="card-img-top" alt="Pizza Image">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $row["Name"]; ?></h5>
                        <p class="card-text"><?php echo $row["Description"]; ?></p>

                        <form action="add_to_cart.php" method="post">
                            <div class="row">
                                <div class="col-6 mb-3">
                                    <label for="size" class="form-label mb-1">Size</label>
                                    <select class="form-select" id="size" name="size" onchange="updatePrice()">
                                        <?php
                                        $sizes = $conn->query("SELECT * FROM Size");
                                        while ($size = $sizes->fetch_assoc()) {
                                            echo "<option value='{$size['SizeID']}' data-multiplier='{$size['Multiplier']}'>{$size['SizeName']}</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-6 mb-3">
                                    <label for="crust" class="form-label mb-1">Crust</label>
                                    <select class="form-select" id="crust" name="crust">
                                        <option value="บางกรอบ">บางกรอบ</option>
                                        <option value="หนานุ่ม">หนานุ่ม</option>
                                        <option value="ขอบชีส">ขอบชีส</option>
                                    </select>
                                </div>
                            </div>
                            <h3 class="card-text mt-2 mb-3 center"><strong>ราคา: </strong><span id="basePrice" data-price="<?php echo $row["Price"]; ?>"><?php echo $row["Price"]; ?></span> บาท</h3>
                            <input type="hidden" name="PizzaID" value="<?php echo $row["PizzaID"]; ?>">
                            <button type="submit" class="btn btn-primary mt-2">เพิ่มลงตะกร้า</button>
                            <a href="customerpage.php" class="btn btn-secondary mt-2">กลับไปหน้าลูกค้า</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <script>
        function updatePrice() {
            const basePriceElement = document.getElementById('basePrice');
            const basePrice = parseFloat(basePriceElement.getAttribute('data-price'));
            const sizeDropdown = document.getElementById('size');
            const multiplier = parseFloat(sizeDropdown.options[sizeDropdown.selectedIndex].getAttribute('data-multiplier'));

            const totalPrice = basePrice * multiplier;

            basePriceElement.textContent = totalPrice.toFixed(2);
        }

        // ให้เรียกฟังก์ชัน updatePrice ทันทีเมื่อหน้าเว็บโหลดเสร็จ
        updatePrice();
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
</body>

</html>