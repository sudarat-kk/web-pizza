<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="stylesheet" href="index.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"> -->
</head>

<body class="background">

    <?php
    session_start();
    // ต้องการการเชื่อมต่อฐานข้อมูลที่นี่
    include("connect.php");
    if (isset($_POST["email"]) && isset($_POST["password"])) {
        $email = $_POST["email"];
        $password = $_POST["password"];

        $stmt = $conn->prepare("SELECT UserID, PasswordHash, Role FROM User WHERE Username = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();

        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            if (password_verify($password, $row["PasswordHash"])) {
                session_start(); // เริ่ม session ถ้าคุณยังไม่ได้เริ่ม
                $_SESSION["email"] = $email;
                $_SESSION["role"] = $row["Role"];
                $_SESSION["UserID"] = $row["UserID"];

                if ($row["Role"] == "Customer") {
                    header("Location: customerpage.php");
                    exit;
                } elseif ($row["Role"] == "ShopOwner") {
                    header("Location: shopowner.php");
                    exit;
                } else {
                    echo "<script>alert('บทบาทผิดพลาด');</script>";
                }
            } else {
                echo "<script>alert('รหัสผ่านผิดพลาด');</script>";
            }
        } else {
            echo "<script>alert('อีเมลไม่ถูกต้อง');</script>";
        }

        $stmt->close();
    }

    ?>
    <div class="container">
        <div class="d-grid justify-content-center align-items-center" style="min-height: 100vh;">
            <div class="card" style="width: 600px; height: 830px;">
                <div class="card-body">
                    <div class="text-center">
                        <br><br><br>
                        <img src="https://static.wixstatic.com/media/504651_41af2ee99dfc49ccaa7d529eef3ab6c9~mv2.gif" alt="LogoPizza" class="rounded-circle" width="170">
                        <h1 style="text-align: center; color: rgb(142, 4, 4); font-weight: bold; margin-top: 20px;">Makima's Pizza</h1>
                        <form id="loginForm" method="POST">
                            <div class="mb-3">
                                <br>
                                <label for="email" class="form-label label-left">อีเมล</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label label-left">&nbsp;&nbsp;&nbsp;&nbsp;รหัสผ่าน</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <br><br>
                            <button type="submit" style="margin-top: 20px;" id="setButton" data-bs-toggle="modal" data-bs-target="#exampleModal">เข้าสู่ระบบ</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
</body>

</html>