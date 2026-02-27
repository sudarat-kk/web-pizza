<?php

include("connect.php");

$PizzaID = $_GET["PizzaID"];
$sql = "SELECT Name, Description, Price, ImageURL FROM pizza WHERE PizzaID='$PizzaID'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $name = $row["Name"];
    $description = $row["Description"];
    $price = $row["Price"];
    $imageURL = $row["ImageURL"];
} else {
    echo "No pizza found with this ID.";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $description = $_POST["description"];
    $price = $_POST["price"];
    $ImageURL = $_POST["imageURL"];


    $sql = "UPDATE pizza SET Name='$name', Description='$description', Price='$price', ImageURL='$ImageURL' WHERE PizzaID='$PizzaID'";


    if ($conn->query($sql) === TRUE) {
        header("Location: shopowner.php");
    } else {
        echo "Error updating record: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
    <title>Edit Your Pizza</title>
    <link rel="stylesheet" href="edit.css">
</head>

<body>
    <div class="container">
        <h1>Edit Pizza Information</h1>
        <form method="post" class="sPj-7ho" action="edit.php?PizzaID=<?php echo $PizzaID; ?>">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo $name; ?>" required>
            </div>
            <div class="form-group">
                <label for="description">Description:</label>
                <textarea class="form-control" id="description" name="description" rows="7" cols="70" required><?php echo $description; ?></textarea>
            </div>
            <div class="form-group">
                <label for="imageURL">Image URL:</label>
                <input type="text" class="form-control" id="imageURL" name="imageURL" value="<?php echo $imageURL; ?>" required>
            </div>

            <div class="form-group">
                <label for="price">Price:</label>
                <input type="number" class="form-control" id="price" name="price" value="<?php echo $price; ?>" required>
            </div>
            <center> <button type="submit" class="btn btn-primary mt-3">Update</button> </center>
            <center><a href="shopowner.php" class="btn btn-secondary mt-2">Cancel</a></center>


        </form>

    </div>

</body>

</html>