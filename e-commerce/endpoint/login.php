<?php
// Include the database connection file
include('../conn/conn.php');

// Start a session to manage user login sessions
session_start();

// Check if the form was submitted using the POST method
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Check if both username and password fields are set in the form submission
    if (isset($_POST['username']) && isset($_POST['password'])) {
        $username = $_POST['username']; // Store the submitted username
        $password = $_POST['password']; // Store the submitted password

        // Check if the user is an admin by querying the admin table
        $stmt = $conn->prepare("SELECT `password` FROM `tbl_admin` WHERE `username` = :username");
        $stmt->bindParam(':username', $username); // Bind the username parameter to prevent SQL injection
        $stmt->execute(); // Execute the SQL query

        if ($stmt->rowCount() > 0) { // Check if a record is found in the admin table
            // Fetch the stored password for the admin
            $row = $stmt->fetch();
            $stored_password = $row['password'];

            // Compare the submitted password with the stored password
            if ($password === $stored_password) {
                // If passwords match, set session variables for admin login
                $_SESSION['username'] = $username;
                $_SESSION['is_admin'] = true; // Indicate this session belongs to an admin

                // Redirect to the admin dashboard with a success message
                echo "<script>alert('Welcome to Admin Dashboard'); window.location.href = 'http://localhost/e-commerce/admin/admin_page.php'; </script>";
            } else {
                // If passwords don't match, show an error message
                echo "<script>alert('Login Failed, Incorrect Admin Password!'); window.location.href = 'http://localhost/e-commerce/index.php'; </script>";
            }
        } else {
            // If not found in admin table, check the regular user table
            $stmt = $conn->prepare("SELECT `password` FROM `tbl_user` WHERE `username` = :username");
            $stmt->bindParam(':username', $username); // Bind the username parameter
            $stmt->execute(); // Execute the SQL query

            if ($stmt->rowCount() > 0) { // Check if a record is found in the user table
                // Fetch the stored password for the user
                $row = $stmt->fetch();
                $stored_password = $row['password'];

                // Compare the submitted password with the stored password
                if ($password === $stored_password) {
                    // If passwords match, set session variables for user login
                    $_SESSION['username'] = $username;
                    $_SESSION['is_admin'] = false; // Indicate this session belongs to a regular user

                    // Redirect to the user home page with a success message
                    echo "<script>alert('Login Successfully!'); window.location.href = 'http://localhost/e-commerce/home.php'; </script>";
                } else {
                    // If passwords don't match, show an error message
                    echo "<script>alert('Login Failed, Incorrect Password!'); window.location.href = 'http://localhost/e-commerce/index.php'; </script>";
                }
            } else {
                // If no record is found in the user table, show an error message
                echo "<script>alert('Login Failed, User Not Found!'); window.location.href = 'http://localhost/e-commerce/index.php'; </script>";
            }
        }
    } else {
        // If either username or password field is empty, show an error message
        echo "<script>alert('Please fill in all the required fields.'); window.location.href = 'http://localhost/e-commerce/index.php'; </script>";
    }
}
?>
