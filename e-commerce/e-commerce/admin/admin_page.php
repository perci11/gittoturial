<?php
$servername = "localhost";
$username = "root";
$password = ""; // Ensure your password is correct
$dbname = "registration_login_db";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$message = []; // Initialize message array for notifications

// Handle product addition
if(isset($_POST['add_product'])){
   $product_name = $_POST['product_name'];
   $product_price = $_POST['product_price'];
   $product_qty = $_POST['product_qty'];

   
   // File upload handling
   if(isset($_FILES['product_image']) && $_FILES['product_image']['error'] === UPLOAD_ERR_OK) {
      $product_image_tmp_name = $_FILES['product_image']['tmp_name'];
      $product_image_name = $_FILES['product_image']['name'];
      $product_image_folder = 'uploaded_img/'.$product_image_name;

      // Move uploaded file to desired location
      if(move_uploaded_file($product_image_tmp_name, $product_image_folder)) {
         // Prepare and execute SQL statement
         $stmt = $conn->prepare("INSERT INTO product (product_name, product_price, product_qty, product_image) VALUES (?, ?, ?, ?)");
         $stmt->bind_param("ssss", $product_name, $product_price, $product_qty, $product_image_folder,);
         if ($stmt->execute()) {
            $message[] = 'New product added successfully.';
         } else {
            $message[] = 'Could not add the product.';
         }
      } else {
         $message[] = 'Failed to move uploaded file.';
      }
   } else {
      $message[] = 'Please upload a product image.';
   }
}

// Handle product deletion
if(isset($_GET['delete'])){
   $id = $_GET['delete'];
   $stmt = $conn->prepare("DELETE FROM product WHERE id = ?");
   $stmt->bind_param("i", $id);
   if ($stmt->execute()) {
      $message[] = 'Product deleted successfully.';
   } else {
      $message[] = 'Could not delete the product.';
   }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Admin Updates | DASHBOARD</title>
    <link rel="icon" href="css/ptclogo.png" type="image/png">
   <!-- Font Awesome CDN link -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
   <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

   <!-- Custom CSS file link -->
   <link rel="stylesheet" href="css/header.css">

   <script>
      function displayFileName() {
         const fileInput = document.getElementById('product_image');
         const fileName = document.getElementById('file-name');

         fileName.textContent = fileInput.files[0].name;
      }
   </script>

</head>
<body>
<?php
if(!empty($message)) {
   foreach($message as $msg) {
      echo '<span class="message">'.$msg.'</span>';
   }
}
?>
<div class="bgtop" id="bgtop">
<div class= "menu">
<img src="css/ptclogo.png" alt="Ptc Logo" class="logo">
<i class='cart-user' >

<div class = "cart-user">  
</div>
</div>


 <div class = "cart-user">
 <a href="order_list.php" class="logout" id="logoutBtn">
    Order Management
    <i class='bx bxs-user' ></i>
    </a>
    

    <a href="http://localhost/e-commerce/index.php" class="logout" id="logoutBtn">
        LogOut
    </a>

  
</div>
</div>

<div class="container">
   <div class="admin-product-form-container">
      <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
         <h3>Add a New Product</h3>
         <input type="text" placeholder="Enter Product Name : " name="product_name" class="box">
         <input type="text" placeholder="Enter Product Price : " name="product_price" class="box">
         <input type="number" placeholder="Enter Product Quantity :" name="product_qty" class="box">

         <label for="product_image" class="file-upload-label">
         <input type="file" id="product_image" name="product_image" accept="image/png, image/jpeg, image/jpg" class="file-input" onchange="displayFileName()"> Choose Product Image </label>
         <span id="file-name"></span>
         <input type="submit" class="btn" name="add_product" value="Add Product">
      </form>
   </div>


   <div class="product-display">
      <h1 class = "prname">-  Product  -</h1>
      <table class="product-display-table">
         <thead>
            <tr>
               <th>Product Image</th>
               <th>Product Name</th>
               <th>Product Price</th>
               <th>Product Qty</th>
               <th>Action</th>
            </tr>
         </thead>
         <tbody>
            <?php
            $select = $conn->query("SELECT * FROM product");
            while($row = $select->fetch_assoc()) {
            ?>
            <tr>
               <td><img src="<?php echo $row['product_image']; ?>" height="100" alt=""></td>
               <td><?php echo $row['product_name']; ?></td>
               <td>₱ <?php echo $row['product_price']; ?></td>
               <td><?php echo $row['product_qty']; ?></td>
               <td>
                  <a href="admin_update.php?edit=<?php echo $row['id']; ?>" class="btn"><i class="fas fa-edit"></i> Edit </a>
                  <a href="<?php echo $_SERVER['PHP_SELF']; ?>?delete=<?php echo $row['id']; ?>" class="btn"><i class="fas fa-trash"></i> Delete </a>
               </td>
            </tr>
            <?php } ?>
         </tbody>
      </table> 
   </div>
</div>
            </div>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
           
   <div class="footer">
    &copy; 
    PTC E-CART. All rights reserved.
    PATEROS TECHNOLOGICAL COLLEGE
    205 College Street, Sto. Rosario-Kanluran Pateros, Metro Manila
    | <a href="#">Privacy Policy</a>
</div>

   

</body>
</html>

