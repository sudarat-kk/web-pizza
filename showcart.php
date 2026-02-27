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
$paymentStatus = "ยังไม่จ่ายเงิน"; // กำหนด Paystatus เป็น "ยังไม่จ่ายเงิน"


$cartItemCount = 0;

if (isset($_SESSION['UserID'])) {
    $userID = $_SESSION['UserID'];
    $sql = "SELECT COUNT(cartdetailID) AS itemCount FROM cartdetail WHERE UserID = $userID";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $data = $result->fetch_assoc();
        $cartItemCount = $data['itemCount'];
    }
}
if (isset($_SESSION['UserID'])) {
    $userID = $_SESSION['UserID'];

    $sql = "
    SELECT 
        cartdetail.CartDetailID,
        cartdetail.PizzaID, 
        cartdetail.SizeID,  
        cartdetail.CrustID, 
        pizza.ImageURL, 
        pizza.Price, 
        pizza.Name AS Name, 
        size.SizeName,
        size.multiplier,  
        crust.CrustType, 
        cartdetail.Quantity
    FROM 
        cartdetail
    INNER JOIN 
        pizza ON pizza.PizzaID = cartdetail.PizzaID
    INNER JOIN
        size ON size.SizeID = cartdetail.SizeID
    INNER JOIN
        crust ON crust.CrustID = cartdetail.CrustID
    WHERE 
        cartdetail.UserID = $userID";

    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();
}


?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- ... your other code ... -->

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <title>ShowCart</title>
    <style>
        body {
            padding-top: 80px;
            /* ความสูงของ navbar + สัก 20px เพื่อการเว้นวรรค */
            background-image: url("https://cdn.wallpapersafari.com/12/79/iQeBup.jpg");
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
        }

        .table-hover tbody tr:hover {
            background-color: rgba(0, 0, 0, 0.04);
            /* ใส่สีเวลาคลิกบนแถว */
        }

        /* ระยะห่างของแถว */
        .table-bordered {
            border-collapse: separate;
            border-spacing: 0 5px;
            /* ปรับระยะห่างตามต้องการ */
        }

        /* ทำให้แต่ละแถวโค้งมน */
        .table-bordered tbody tr:first-child td {
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
        }

        .table-bordered tbody tr:last-child td {
            border-bottom-left-radius: 10px;
            border-bottom-right-radius: 10px;
        }

        /* เพิ่มพื้นหลังสีขาวให้กับแถว */
        .table-bordered tbody tr {
            background-color: white;
        }

        td img {
            width: 150px;
            /* ปรับความกว้างของภาพ */
            height: auto;
            /* ปรับความสูงให้พอดีกับภาพที่ปรับความกว้างแล้ว */
        }

        td {
            text-align: center;
            /* จัดให้ข้อความอยู่ตรงกลางในเซลล์ตาราง */
            vertical-align: middle;
            /* จัดให้ข้อความอยู่ตรงกลางในเซลล์ตาราง */
        }

        body {
            font-family: Arial, sans-serif;
        }

        .container {
            padding: 50px;
        }

        #logo {
            margin-left: 20px;
            /* margin-top: 18px; */
        }

        .custom-container {
            max-width: 90%;
            /* หรือเปอร์เซ็นต์ใด ๆ ที่คุณต้องการ */
        }

        .modal-body img {
            width: 70%;
            height: auto;
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


<a href="showcart.php">
    <div class="cart-icon" style="position: relative; margin-right: 50px;">
        <img src="https://www.freeiconspng.com/thumbs/cart-icon/basket-cart-icon-27.png" alt="" width="50px">
        <div style="position: absolute; top: -10px; left: 35px; background-color: red; width: 20px; height: 20px; border-radius: 50%; display: flex; justify-content: center; align-items: center; color: white; font-weight: bold;">
            <?php echo $cartItemCount; ?> <!-- จำนวนรายการในตระกร้า -->
        </div>
    </div>
</a>



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
    
    <form action="pay.php" method="post" id="paymentForm">
    <input type="hidden" name="paymentStatus" value="<?php echo $paymentStatus; ?>"> <!-- ส่วนที่ซ่อนไว้ในฟอร์ม -->
        <div class="custom-container mx-auto mt-5">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr><th>row</th>
                        <th>Select</th>
                        <th>Image</th>
                        <th>Pizza Name</th>
                        <th>Size Name</th>
                        <th>Crust Name</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Actions</th>

                    </tr>
                </thead>
                <tbody>
                    <?php
                    $totalPrice = 0;
                    $rowNumber = 1; // Initialize the row number
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>"; 
                        echo "<td>" . $rowNumber . "</td>"; // Add row number
                        echo "<td><input type='checkbox' name='selectedItems[]' value='" . $row['CartDetailID'] . "'></td>"; // Checkbox
                        echo "<td><img src='" . $row['ImageURL'] . "' alt='Pizza Image' width='100'></td>";
                        echo "<td>" . $row['Name'] . "<input type='hidden' name='PizzaID[]' value='" . $row['PizzaID'] . "'></td>";  // เพิ่มฟิลด์ที่ซ่อนนี้
                        echo "<td>" . $row['SizeName'] . "<input type='hidden' name='SizeID[]' value='" . $row['SizeID'] . "'></td>";  // เพิ่มฟิลด์ที่ซ่อนนี้
                        echo "<td>" . $row['CrustType'] . "<input type='hidden' name='CrustID[]' value='" . $row['CrustID'] . "'></td>";  // เพิ่มฟิลด์ที่ซ่อนนี้
                        echo "<td>" . $row['Quantity'] . "<input type='hidden' name='Quantity[]' value='" . $row['Quantity'] . "'></td>";


                        // คำนวณราคาที่ถูกต้อง
                        $calculatedPrice = $row['Price'] * $row['Quantity'] * $row['multiplier'];
                        $totalPrice += $calculatedPrice;

                        // แสดงราคา
                        echo "<td>" . $calculatedPrice . " บาท</td>";
                        echo '<input type="hidden" name="calculatedPrices[]" value="' . $calculatedPrice . '">';


                        echo "<td>";
                        echo "<a href='add_quantity.php?CartDetailID=" . $row['CartDetailID'] . "' class='btn btn-success'>+</a> ";
                        echo "<a href='reduce_quantity.php?CartDetailID=" . $row['CartDetailID'] . "' class='btn btn-danger' onclick='confirmDelete(" . $row['CartDetailID'] . ")'>-</a>";
                        echo "<a href='delete_cart_item.php?CartDetailID=" . $row['CartDetailID'] . "' class='btn btn-danger'>ลบทั้งรายการ</a>";
                        echo "</td>";
                        echo "</tr>";
                        $rowNumber++; // Increment the row number
                    }
                    echo '<tfoot>';
                    echo '<tr>';
                    echo "<td>".$cartItemCount.'';
                    echo '<td><button type="button" id="toggleSelect" class="btn btn-outline-dark" onclick="toggleSelection()">เลือกทั้งหมด</button></td>';
                    echo '<td colspan="5" style="font-weight: bold;">ราคารวม'.$totalPrice.'(ค่าส่ง 15 บาท)</td>';
                    // echo '<td colspan="2" style="font-weight: bold;">' . $totalPrice . ' บาท</td>';
                    echo '<td colspan="2" id="totalPriceDisplay" style="font-weight: bold;"></td>';
                    echo '<input type="hidden" name="totalPrice" value="' . $totalPrice . '">';
                    echo '</tr>';
                    echo '</tfoot>';




                    ?>
                </tbody>

            </table>
        </div>
        <div style="text-align: center; margin-top: 20px;">
            <button type="button" class="btn btn-primary" onclick="checkSelectionAndPay()">สั่งซื้อสินค้า</button>
            <!-- Alert Modal -->
            <div class="modal fade" id="alertModal" tabindex="-1" aria-labelledby="alertModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="alertModalLabel">การแจ้งเตือน</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body" style="font-size: larger; font-weight: bold; ">
                            <img src="https://i.pinimg.com/564x/58/78/ff/5878ff3375ef4b0188f69302760c0cfb.jpg" alt="มีม">
                            <br>
                            <br>
                            โปรดเลือกสินค้าก่อนสั่งซื้อสินค้า
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </form>



    <br>
    <center><img src="https://media.tenor.com/VnZiBdemf9UAAAAC/watashi-ni-tenshi-money.gif" alt=""></center>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            updateListeners();
            updateTotalPrice();
        });

        function updateListeners() {
            let checkboxes = document.querySelectorAll("[name='selectedItems[]']");
            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', updateTotalPrice);
            });
        }

        function toggleSelection() {
            let btn = document.getElementById('toggleSelect');
            let checkboxes = document.querySelectorAll("input[type='checkbox']");

            if (btn.innerHTML === "เลือกทั้งหมด") {
                for (let checkbox of checkboxes) {
                    checkbox.checked = true;
                }
                btn.innerHTML = "ยกเลิกทั้งหมด";
            } else {
                for (let checkbox of checkboxes) {
                    checkbox.checked = false;
                }
                btn.innerHTML = "เลือกทั้งหมด";
            }

            updateTotalPrice(); // ปรับปรุงราคาเมื่อเลือก/ยกเลิกสินค้า
        }

        function checkSelectionAndPay() {
            var selectedItems = document.querySelectorAll("[name='selectedItems[]']:checked");
            if (selectedItems.length > 0) {
                // ทำการส่งฟอร์ม
                document.getElementById('paymentForm').submit();
            } else {
                // แสดง modal ว่ายังไม่ได้เลือกสินค้า
                var modal = new bootstrap.Modal(document.getElementById('alertModal'));
                modal.show();
            }
        }

        function updateTotalPrice() {
    let checkboxes = document.querySelectorAll("[name='selectedItems[]']:checked");
    let totalPrice = 0;

    checkboxes.forEach(checkbox => {
        let rowIndex = checkbox.closest('tr').rowIndex;
        let priceCell = document.querySelector(`table tbody tr:nth-child(${rowIndex}) td:nth-child(8)`);
        let itemPrice = parseFloat(priceCell.textContent);
        totalPrice += itemPrice;
    });

    // เพิ่มค่าจัดส่ง 15 บาท
    totalPrice += 15;

    // ปรับปรุงข้อความที่แสดงราคาทั้งหมด
    document.getElementById("totalPriceDisplay").textContent = "ราคาที่ต้องจ่าย " + totalPrice + " บาท";

    // ปรับปรุงค่าในฟิลด์ input hidden สำหรับราคาทั้งหมด
    document.querySelector("input[name='totalPrice']").value = totalPrice;
}

function confirmDelete(cartDetailID) {
    var modal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));
    document.querySelector(".modal-footer .btn-primary").onclick = function() {
        // กระทำลบเมื่อผู้ใช้ยืนยัน
        window.location = "reduce_quantity.php?CartDetailID=" + cartDetailID;
    };
    modal.show();
}



    </script>


</body>

</html>