<?php
include 'config.php'; // Ensure database connection is included

$message = []; // Initialize message array for notifications

if(isset($_POST['update_product'])){
   $id = $_GET['edit']; // Fetch product ID from URL parameter
   
   $product_name = $_POST['product_name'];
   $product_price = $_POST['product_price'];
   $product_qty = $_POST['product_qty'];

   // File upload handling (if needed)
   // Assuming you are updating image only if a new image is uploaded

   if(!empty($_FILES['product_image']['name'])) {
      $product_image_tmp_name = $_FILES['product_image']['tmp_name'];
      $product_image_name = $_FILES['product_image']['name'];
      $product_image_folder = 'uploaded_img/'.$product_image_name;
      
      // Move uploaded file to desired location
      if(move_uploaded_file($product_image_tmp_name, $product_image_folder)) {
         // Prepare and execute SQL statement
         $stmt = $conn->prepare("UPDATE product SET product_name=?, product_price=?, product_qty=?, product_image=? WHERE id=?");
         $stmt->bind_param("sssss", $product_name, $product_price, $product_qty, $product_image_folder,$id);
      } else {
         $message[] = 'Failed to move uploaded file.';
      }
   } else {
      // No new image uploaded, update without changing the image path
      $stmt = $conn->prepare("UPDATE product SET product_name=?, product_price=?, product_qty=? WHERE id=?");
      $stmt->bind_param("sssss", $product_name, $product_price, $product_qty, $id);
   }
   
   // Execute SQL statement
   if ($stmt->execute()) {
      $message[] = 'Product updated successfully.';
   } else {
      $message[] = 'Failed to update the product.';
   }
   $stmt->close();
}

// Fetch existing product details for edit form pre-filling
if(isset($_GET['edit'])) {
   $id = $_GET['edit'];
   $stmt = $conn->prepare("SELECT * FROM product WHERE id = ?");
   $stmt->bind_param("i", $id);
   $stmt->execute();
   $result = $stmt->get_result();
   if($row = $result->fetch_assoc()) {
      // Store fetched data into variables for pre-filling the form
      $product_name_edit = $row['product_name'];
      $product_price_edit = $row['product_price'];
      $product_qty_edit = $row['product_qty'];
      $product_image_edit = $row['product_image']; // If you want to display the current image
   } else {
      $message[] = 'Product not found.';
   }
   $stmt->close();
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Product Update | DASHBOARD</title>
   <link rel="icon" href="css/ptclogo.png" type="image/png">
   <!-- Include CSS files -->
   <link rel="stylesheet" href="css/update.css">
   <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
   <script>
      function displayFileName() {
         const fileInput = document.getElementById('product_image');
         const fileName = document.getElementById('file-name');

         fileName.textContent = fileInput.files[0].name;
      }
   </script>

<div class="bgtop" id="bgtop">
<div class= "menu">
<img src="css/ptclogo.png" alt="Ptc Logo" class="logo">
</div>
    

    <a href="http://localhost/e-commerce/admin/admin_page.php" class="user">
            <i class='bx bxs-user' >
            </i>
            <span class = "name"> Back  </span>
            </a>

 <div class = "cart-user">

    <a href="http://localhost/e-commerce/index.php" class="logout" id="logoutBtn">
        LogOut
    </a>
</div>
</div>
</head>
<body>

<?php
if(!empty($message)) {
   foreach($message as $msg) {
      echo '<span class="message">'.$msg.'</span>';
   }
}
?>

<div class="container">
   <div class="admin-product-form-container">
      <form action="<?php echo $_SERVER['PHP_SELF'] . '?edit=' . $id; ?>" method="post" enctype="multipart/form-data">
         <h3>Edit Product</h3>
         <input type="text" placeholder="Enter Product Name" name="product_name" class="box" value="<?php echo isset($product_name_edit) ? htmlspecialchars($product_name_edit) : ''; ?>" required>
         <input type="text" placeholder="Enter Product Price" name="product_price" class="box" value="<?php echo isset($product_price_edit) ? htmlspecialchars($product_price_edit) : ''; ?>" required>
         <input type="number" placeholder="Enter Product Quantity" name="product_qty" class="box" value="<?php echo isset($product_qty_edit) ? htmlspecialchars($product_qty_edit) : ''; ?>" required>
         
         <label for="product_image" class="file-upload-label">
         <input type="file" id="product_image" name="product_image" accept="image/png, image/jpeg, image/jpg" class="file-input" onchange="displayFileName()"> Choose Product Image </label>
         <span id="file-name"></span>
         <input type="submit" class="btn" name="update_product" value="Update Product">
      </form>
   </div>
</div>

          
<div class="footer">
    &copy; 
    PTC E-CART. All rights reserved.
    PATEROS TECHNOLOGICAL COLLEGE
    205 College Street, Sto. Rosario-Kanluran Pateros, Metro Manila
    | <a href="#">Privacy Policy</a>
</div>



</body>
</html>
