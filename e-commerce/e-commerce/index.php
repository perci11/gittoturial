<?php include ('./conn/conn.php') ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LogIn | SignUp</title>
    <link rel="icon" href="ptclogo.png" type="image/png">


    
    <link rel="stylesheet" href="style.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>

<div class="bgtop" id="bgtop">
    <img src="ptclogo.png" alt="Ptc Logo" class="logo">
    <div class="menu"><b>
        <a href="http://localhost/e-commerce/page/homepage.html">Home</a>
        <a href="">About</a>
    </b>
        
 </div>
</div>

<div class="container">
    <!-- Login Area -->
    <div class="login" id="loginForm">
        <h1 class="text-center">LogIn</h1>
        <div class="login-form">
            <form action="./endpoint/login.php" method="POST">
                <div class="form-group">
                    <input type="text" class="form-control" id="username" name="username" placeholder="Username" required>
                    <i class='bx bxs-user'></i>
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                    <i class='bx bxs-lock-alt'></i>
                </div>
                <div class="form-group">
                    <input type="hidden" name="role_as" value="1" required>
                    <button type="submit" class="btn btn-dark login-btn form-control">Login</button>
                </div>
                <p class="registrationForm" onclick="showRegistrationForm()">No Account? Register Here.</p>
            </form>
        </div>
    </div>

        <!-- Registration Area -->
        <div class="registration" id="registrationForm">
            <h1 class="text-center">Registration Form</h1>
            <div class="registration-form">
            <form action="endpoint/add-user.php" method="POST">
                <div class="form-group row">
                    <div class="col-6">
                   
                        <label for="firstName">First Name: </label>
                        <br>
                        <input type="text" class="form-control" id="firstName" name="first_name">
                    </div>
                    <div class="col-6">
                        <label for="lastName">Last Name: </label>
                        <br>
                        <input type="text" class="form-control" id="lastName" name="last_name">
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-5">
                        <label for="contactNumber">Contact Number: </label>
                        <br>
                        <input type="number" class="form-control" id="contactNumber" name="contact_number" maxlength="11">
                    </div>
                    <div class="col-7">
                        <label for="email">Email:</label>
                        <br>
                        <input type="text" class="form-control" id="email" name="email">
                    </div>
                </div>

                <div class="form-group">
                    <label for="registerUsername">Username:</label>
                    <br>
                    <input type="text" class="form-control" id="registerUsername" name="username">
                </div>
                <div class="form-group">
                    <label for="registerPassword">Password:</label>
                    <br>
                    <input type="password" class="form-control" id="registerPassword" name="password">
                </div>
                <p class="registrationForm" onclick="showLoginForm()"><- Back</p>
        
                <button type="submit" class="btn btn-dark login-register form-control">Register</button>
            </form>

            </div>
    
        </div>

    </div>

    <script>
        // Constant variables
        const loginForm = document.getElementById('loginForm');
        const registrationForm = document.getElementById('registrationForm');

        // Hide registration form
        registrationForm.style.display = "none";


        function showRegistrationForm() {
            registrationForm.style.display = "";
            loginForm.style.display = "none";
        }

        function showLoginForm() {
            registrationForm.style.display = "none";
            loginForm.style.display = "";
        }

    </script>

    <!-- Bootstrap Js -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script>

</body>
</html>