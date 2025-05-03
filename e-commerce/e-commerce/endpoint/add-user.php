<?php
include('../conn/conn.php');

// Get user input from POST request
$firstName = $_POST['first_name'];
$lastName = $_POST['last_name'];
$contactNumber = $_POST['contact_number'];
$email = $_POST['email'];
$username = $_POST['username'];
$password = $_POST['password'];

try {
    // Check if the email domain is valid
    if (!str_ends_with($email, '@paterostechnologicalcollege.edu.ph')) {
        echo "<script>
                alert('Only emails with @paterostechnologicalcollege.edu.ph domain are allowed.');
                window.location.href = 'http://localhost/e-commerce/index.php';
              </script>";
        exit;
    }

    // Check if the username already exists
    $stmt = $conn->prepare("SELECT `username` FROM `tbl_user` WHERE `username` = :username");
    $stmt->execute(['username' => $username]);
    $usernameExist = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!empty($usernameExist)) {
        echo "<script>
                alert('Username is already taken. Please choose another one.');
                window.location.href = 'http://localhost/e-commerce/index.php';
              </script>";
        exit;
    }

    // Check if a user with the same first and last name already exists
    $stmt = $conn->prepare("SELECT `first_name`, `last_name` FROM `tbl_user` 
                            WHERE `first_name` = :first_name AND `last_name` = :last_name");
    $stmt->execute([
        'first_name' => $firstName,
        'last_name' => $lastName
    ]);
    $nameExist = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!empty($nameExist)) {
        echo "<script>
                alert('User with the same name already exists.');
                window.location.href = 'http://localhost/e-commerce/index.php';
              </script>";
        exit;
    }

    // If validations pass, register the user
    $conn->beginTransaction();

    // Directly store the plain-text password (Not Recommended)
    $insertStmt = $conn->prepare("
        INSERT INTO `tbl_user` (`user_id`, `first_name`, `last_name`, `contact_number`, `email`, `username`, `password`) 
        VALUES (NULL, :first_name, :last_name, :contact_number, :email, :username, :password)
    ");
    $insertStmt->bindParam(':first_name', $firstName, PDO::PARAM_STR);
    $insertStmt->bindParam(':last_name', $lastName, PDO::PARAM_STR);
    $insertStmt->bindParam(':contact_number', $contactNumber, PDO::PARAM_STR); // Use PARAM_STR for phone numbers
    $insertStmt->bindParam(':email', $email, PDO::PARAM_STR);
    $insertStmt->bindParam(':username', $username, PDO::PARAM_STR);
    $insertStmt->bindParam(':password', $password, PDO::PARAM_STR); // Store plain-text password
    $insertStmt->execute();

    $conn->commit();

    echo "<script>
            alert('Registered Successfully');
            window.location.href = 'http://localhost/e-commerce/index.php';
          </script>";
} catch (PDOException $e) {
    $conn->rollBack(); // Roll back transaction on error
    echo "<script>
            alert('Error: " . $e->getMessage() . "');
            window.location.href = 'http://localhost/e-commerce/index.php';
          </script>";
}
?>
