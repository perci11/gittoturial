<?php
include('../conn/conn.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the user inputs
    $username = $_POST['username'];
    $password = $_POST['password'];

    try {
        // Check if the username exists in the login table
        $stmt = $conn->prepare("SELECT l.password, luser_id, u.first_name, u.role_as
                                FROM login l
                                JOIN tbl_user u ON l.user_id = u.user_id
                                WHERE l.username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        // If the user exists
        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch();
            $stored_password = $row['password'];
            $stored_user_id = $row['user_id'];
            $stored_first_name = $row['first_name'];
            $stored_role_as = $row['role_as'];

            // Verify the password using password_verify() for hashed passwords
            if (password_verify($password, $stored_password)) {
                // Start the session
                session_start();

                // Store session information
                $_SESSION['username'] = $username;
                $_SESSION['user_id'] = $stored_user_id;
                $_SESSION['role_as'] = $stored_role_as;
                $_SESSION['first_name'] = $stored_first_name;

                // Redirect based on user role
                if ($stored_role_as == 1) {
                    // Admin login
                    echo "
                    <script>
                        alert('Welcome to Admin Dashboard');
                        window.location.href = 'http://localhost/e-commerce/admin/admin_page.php';
                    </script>";
                } else {
                    // Regular user login
                    echo "
                    <script>
                        alert('Login Successfully!');
                        window.location.href = 'http://localhost/e-commerce/home.php';
                    </script>";
                }
            } else {
                // Password does not match
                echo "
                <script>
                    alert('Login Failed, Incorrect Password!');
                    window.location.href = 'http://localhost/e-commerce/login.php';
                </script>";
            }
        } else {
            // Username does not exist
            echo "
            <script>
                alert('Login Failed, User Not Found!');
                window.location.href = 'http://localhost/e-commerce/login.php';
            </script>";
        }
    } catch (PDOException $e) {
        // Handle potential errors
        echo "
        <script>
            alert('An error occurred: " . $e->getMessage() . "');
            window.location.href = 'http://localhost/e-commerce/login.php';
        </script>";
    }
}
?>
