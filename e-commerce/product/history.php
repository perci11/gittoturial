<?php
session_start();
require 'config.php';

// Fetch orders from the database
$sql = "SELECT orders.products, orders.amount_paid, orders.pmode, orders.order_date, tbl_user.username
        FROM orders
        INNER JOIN tbl_user ON orders. id = id
        WHERE tbl_user .user_id 
        ORDER BY orders.order_date DESC";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>OrderHistory | PTC E-CART</title>
  <link rel="icon" href="ptclogo.png" type="image/png">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.9.0/css/all.min.css">
  <link rel="stylesheet" href="history.css">
</head>

<body>
  <!-- Navbar start -->
  <body>
  <!-- Navbar start -->
  <nav class="navbar navbar-expand-md bg-dark navbar-dark">
  <img src="ptclogo.png" alt="Ptc Logo" class="logo">
  </a>
    
    <div class="collapse navbar-collapse" id="collapsibleNavbar">
      
      <ul class="navbar-nav ml-auto">
      
        <li class="nav-item">
          <a class="nav-link active" href="http://localhost/e-commerce/home.php"><i class="fas fa-home mr-2"></i>Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="product.php"><i class="fas fa-money-check-alt mr-2"></i>Products</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href = "cart.php"><i class="fas fa-shopping-cart"></i> <span id="cart-item" class="badge badge-danger">0</span></a>
        </li>
      </ul>
</div>
</nav>
</body>
<br>
<br>
<br>
<br>
<br>
  <div class="container">
    <h2 class="my-4 text-center">-  Order History  -</h2>
    <table class="table table-bordered table-striped">
      <thead>
        <tr>
          <th>Products</th>
          <th>Total Amount</th>
          <th>Payment Mode</th>
          <th>Order Date</th>
          <th>Username</th> <!-- Added for displaying the username from tbl_user -->
        </tr>
      </thead>
      <tbody>
        <?php
          if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
        ?>
              <tr>  
                <td><?= $row['products']; ?></td>
                <td><?= number_format($row['amount_paid'], 2); ?></td>
                <td><?= $row['pmode']; ?></td>
                <td><?= $row['order_date']; ?></td>
                <td><?= $row['username']; ?></td> <!-- Displaying username -->
              </tr>
        <?php
            }
          } else {
        ?>
            <tr>
              <td colspan="5" class="text-center">No orders found.</td>
            </tr>
        <?php
          }
        ?>
      </tbody>
    </table>
        <br>
        <br>
        <br>
       

        
  </div>

  <div class="footer">
    &copy; 
    PTC E-CART. All rights reserved.
    PATEROS TECHNOLOGICAL COLLEGE
    205 College Street, Sto. Rosario-Kanluran Pateros, Metro Manila
    | <a href="#">Privacy Policy</a>
</div>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
  
</body>

</html>
