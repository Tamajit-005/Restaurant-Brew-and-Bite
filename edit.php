<?php
// Database connection
$server = "localhost";
$username = "root";
$password = "";

// Create database connection
$con = mysqli_connect($server, $username, $password);

// Get the id from URL
$id = $_GET['id'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Check if this is a delete request
    if (isset($_POST['delete'])) {
        // DELETE query
        $stmt = $con->prepare("DELETE FROM `food`.`dish` WHERE sl_no = ?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            $stmt->close();
            $con->close();
            // Redirect after successful deletion
            header("Location: index.php?message=deleted");
            exit;
        } else {
            $error = "Delete failed: " . $stmt->error;
            $stmt->close();
        }
    } else {
        // UPDATE request 
        $name = $_POST['name'] ?? '';
        $dish = $_POST['dish'] ?? '';
        $guests = intval($_POST['guests'] ?? 0);
        $email = $_POST['email'] ?? '';
        $phone = $_POST['phone'] ?? '';
        $request = $_POST['request'] ?? '';
        // UPDATE query
        $stmt = $con->prepare("UPDATE `food`.`dish` SET name=?, dish=?, guests=?, email=?, phone=?, request=? WHERE sl_no=?");
        $stmt->bind_param("ssisssi", $name, $dish, $guests, $email, $phone, $request, $id);
        if ($stmt->execute()) {
            $stmt->close();
            $con->close();
            // Redirect after successful update
            header("Location: display.php?id=" . $id);
            exit;
        } else {
            $error = "Update failed: " . $stmt->error;
            $stmt->close();
        }
    }
} else {
    // GET request — fetch existing data to populate the form
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
    $stmt->close();
}
$con->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Brew & Bite Edit</title>
<link rel="stylesheet" href="estyle.css">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Pirata+One&display=swap" rel="stylesheet">
</head>

<body>
<img class="bg" src="Images/edit.jpg" alt="background">
<div class="edit-container">
    <h1>Edit Order</h1>

    <?php if (!empty($error)): ?>
        <div class="error"><?php echo $error; ?></div>
    <?php endif; ?>

    <!-- Update Form -->
    <form action="edit.php?id=<?php echo $id; ?>" method="post" novalidate>
        <input type="text" name="name" value="<?php echo $name; ?>" placeholder="Full Name" required />
        <input type="text" name="dish" value="<?php echo $dish; ?>" placeholder="Enter dish" required />
        <input type="number" name="guests" value="<?php echo $guests; ?>" placeholder="No. of guests" required min="1" />
        <input type="email" name="email" value="<?php echo $email; ?>" placeholder="Email" required />
        <input type="tel" name="phone" value="<?php echo $phone; ?>" placeholder="Phone Number" />
        <textarea name="request" placeholder="Special Requests"><?php echo $request; ?></textarea>
        <button type="submit" class="update-btn">Update</button>
    </form>

    <!-- Delete Form -->
    <form action="edit.php?id=<?php echo $id; ?>" method="post" onsubmit="return confirm('Are you sure you want to delete this entry?');">
        <input type="hidden" name="delete" value="1" />
        <button type="submit" class="delete-btn">Delete</button>
    </form>
</div>
</body>

</html>
