<?php
// Database connection
$server = "localhost";
$username = "root";
$password = "";

// Create database connection
$con = mysqli_connect($server, $username, $password);

// Get the ID from the URL
$id = $_GET['id'] ?? null;
    
// SQL statement
$stmt = $con->prepare("SELECT * FROM `food`.`dish` WHERE sl_no = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $name = $row['name'];  
    $dish = $row['dish'];
    $guests = $row['guests'];
    $email = $row['email'];
    $phone = $row['phone'];
    $request = $row['request'];

} else {
    echo "No record found with ID: " . $id;
    $stmt->close();
    $con->close();
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Brew & Bite Order</title>
    <link rel="stylesheet" href="dstyle.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fascinate+Inline&display=swap" rel="stylesheet">
</head>

<body>
    <img class="bg" src="Images/display.jpg" alt="background">
    <div class="container">
        <h1>Order Details</h1>
        <p><strong>Name:</strong>
            <?php echo $name; ?>
        </p>
        <p><strong>Dish:</strong>
            <?php echo $dish; ?>
        </p>
        <p><strong>Number of Guests:</strong>
            <?php echo $guests; ?>
        </p>
        <p><strong>Email:</strong>
            <?php echo $email; ?>
        </p>
        <p><strong>Phone:</strong>
            <?php echo $phone; ?>
        </p>
        <p><strong>Special Requests:</strong>
            <?php echo $request; ?>
        </p>
        <p><a href="edit.php?id=<?php echo urlencode($id); ?>"><button>Edit</button></a></p>
    </div>
</body>

</html>