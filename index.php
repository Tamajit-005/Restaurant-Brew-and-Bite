<?php
    // Database connection
    $server = "localhost";
    $username = "root";
    $password = "";

    // Create database connection
    $con = mysqli_connect($server, $username, $password);
    
    // Check connection
    if (!$con) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Create database if it doesn't exist
    $sql = "CREATE DATABASE IF NOT EXISTS food";
    if (!mysqli_query($con, $sql)) {
        die("Error creating database: " . mysqli_error($con));
    }

    // Select the database
    mysqli_select_db($con, "food");

    // Create table if it doesn't exist
    $sql = "CREATE TABLE IF NOT EXISTS `dish` (
    `sl_no` INT AUTO_INCREMENT PRIMARY KEY,
    `name` text COLLATE utf8mb4_general_ci NOT NULL,
    `dish` text COLLATE utf8mb4_general_ci NOT NULL,
    `guests` INT NOT NULL,
    `email` text COLLATE utf8mb4_general_ci NOT NULL,
    `phone` text COLLATE utf8mb4_general_ci NOT NULL,
    `request` text COLLATE utf8mb4_general_ci NOT NULL,
    `date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
    )";
    if (!mysqli_query($con, $sql)) {
        die("Error creating table: " . mysqli_error($con));
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $name = $_POST['name'] ?? '';
      $dish = $_POST['dish'] ?? '';
      $guests = $_POST['guests'] ?? '';
      $email = $_POST['email'] ?? '';
      $phone = $_POST['phone'] ?? '';
      $request = $_POST['request'] ?? '';

      // SQL statement
      $stmt = $con->prepare("INSERT INTO `food`.`dish` (`name`, `dish`, `guests`, `email`, `phone`, `request`, `date`) VALUES (?, ?, ?, ?, ?, ?, current_timestamp())");
      $stmt->bind_param("ssisss", $name, $dish, $guests, $email, $phone, $request);

      if ($stmt->execute()) {
        // Get the ID of the last inserted row
        $last_id = $con->insert_id;
        $stmt->close();
        $con->close();
        // Redirect to the display page with the ID
        header("Location: display.php?id=" . $last_id);
        exit();
      } else {
        $message = "Error: " . $stmt->error;
        $stmt->close();
      }
    } else {
      $message = "Database error: " . $con->error;
    }
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Brew & Bite</title>
    <link rel="stylesheet" href="style.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Kablammo&display=swap"
      rel="stylesheet"
    />
  </head>
  <body>
    <img class="bg" src="Images/Restaurant.jpg" alt="Brew & Bite" />
    <div class="container">
      <h1>Brew & Bite</h1>
      <form action="index.php" method="post">
        <input type="text" name="name" id="name" placeholder="Full Name" />
        <input type="text" name="dish" id="dish" placeholder="Enter dish" />
        <input
          type="number"
          name="guests"
          id="guests"
          placeholder="No. of guests"
        />
        <input type="email" name="email" id="email" placeholder="Email" />
        <input
          type="tel"
          name="phone"
          id="phone"
          placeholder="Phone Number"
        />
        <textarea
          name="request"
          id="request"
          cols="30"
          rows="6"
          placeholder="Special Requests"
        ></textarea>
        <button class="btn">Submit</button>
      </form>
    </div>
  </body>
</html>
