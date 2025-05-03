<?php
session_start();
require 'config.php';

$grand_total = 0;
$allItems = '';
$items = [];

// Check if there are any items in the cart
$sql = "SELECT COUNT(*) AS item_count FROM cart";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$item_count = $row['item_count'];

// If there are items in the cart, proceed with fetching the items for display
if ($item_count > 0) {
    $sql = "SELECT CONCAT(product_name, '(',qty,')') AS ItemQty, total_price FROM cart";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $grand_total += $row['total_price'];
        $items[] = $row['ItemQty'];
    }
    $allItems = implode(', ', $items);
} else {
    $cart_empty = true; // Cart is empty
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="author" content="Sahil Kumar">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>CheckOut | PTC E-CART</title>
    <link rel="icon" href="ptclogo.png" type="image/png">

  <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.min.css' />
  <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.9.0/css/all.min.css' />
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
  <link rel='stylesheet' href='checkout.css'>
</head>

<body>
  <!-- Navbar start -->
  <nav class="navbar navbar-expand-md bg-dark navbar-dark">
    <img src="ptclogo.png" alt="Ptc Logo" class="logo"></a>
    
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

  <div class="container">
    <div class="row justify-content-center">
      <?php if (isset($cart_empty) && $cart_empty): ?>
        <!-- If the cart is empty, show a message -->
        <div class="col-lg-6 px-4 pb-4">
          <h4 class="text-center text-warning">Your cart is empty!</h4>
          <p class="text-center">Please add items to your cart before proceeding to checkout.</p>
          <a href="product.php" class="btn btn-primary btn-block">Go to Products</a>
        </div>
      <?php else: ?>
        <!-- If the cart is not empty, show the checkout form -->
        <div class="col-lg-6 px-4 pb-4" id="order">
          <h4 class="text-center text-info p-2">Complete your Order!</h4>
          <div class="jumbotron p-3 mb-2 text-center">
            <h6 class="lead"><b>-  School Pickup  -</b></h6>
            <br>
            <h6 class="lead"><b>Product : </b><br> <?= $allItems; ?></h6>
            <br>
            <h5><b>Total Amount Payable : </b>₱ &nbsp;<?= number_format($grand_total,2) ?></h5>
          </div>
          <form action="action.php" method="post" id="placeOrder">
            <input type="hidden" name="products" value="<?= $allItems; ?>">
            <input type="hidden" name="grand_total" value="<?= $grand_total; ?>">
            <input type="hidden" name="order_date" value="<?= date('Y-m-d H:i:s'); ?>">
            <div class="form-group">
              <input type="text" name="name" class="form-control" placeholder="Enter Name" required>
            </div>
            <div class="form-group">
              <input type="email" name="email" class="form-control" placeholder="Enter E-Mail" required>
            </div>
            <div class="form-group">
              <input type="text" name="student_number" id="student_number" class="form-control" placeholder="Enter Student-Number" required>
            </div>
            <div class="form-group">
              <input type="tel" name="phone" class="form-control" placeholder="Enter Phone" required>
            </div>
            <h6 class="text-center lead">Select Payment Mode</h6>
            <div class="form-group">
              <select name="pmode" class="form-control">
                <option value="" selected disabled>-Select Payment Mode-</option>
                <option value="School Pick Up">School Pick Up </option>
              </select>
            </div>
            <div class="form-group">
              <input type="submit" name="submit" value="Place Order" class="btn btn-danger btn-block">
            </div>
          </form>
        </div>
      <?php endif; ?>
    </div>
  </div>

  <br>

  <div class="footer">
    &copy; 
    PTC E-CART. All rights reserved.
    PATEROS TECHNOLOGICAL COLLEGE
    205 College Street, Sto. Rosario-Kanluran Pateros, Metro Manila
    | <a href="#">Privacy Policy</a>
  </div>

  <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js'></script>
  <script src='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/js/bootstrap.min.js'></script>

  <script type="text/javascript">
  $(document).ready(function() {

    // Sending Form data to the server
    $("#placeOrder").submit(function(e) {
      e.preventDefault();
      $.ajax({
        url: 'action.php',
        method: 'post',
        data: $('form').serialize() + "&action=order",
        success: function(response) {
          $("#order").html(response);
        }
      });
    });

    // Load total no.of items added in the cart and display in the navbar
    load_cart_item_number();

    function load_cart_item_number() {
      $.ajax({
        url: 'action.php',
        method: 'get',
        data: {
          cartItem: "cart_item"
        },
        success: function(response) {
          $("#cart-item").html(response);
        }
      });
    }
  });
  </script>
</body>

</html>
