<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home | PTC E-CART</title>
    <link rel="icon" href="ptclogo.png" type="image/png">

    <link rel="stylesheet" href="styles.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
    
<div class="bgtop" id="background">
    <img src="ptclogo.png" alt="Ptc Logo" class="logo">
    <div class="menu"><b>
        
        <a href="http://localhost/e-commerce/product/product.php">Products</a>
    </b>
 </div>

 <div class = "cart-user">
    
 <a href="http://localhost/e-commerce/product/history.php" class="user">
            <i class='bx bxs-user' ></i>
        </a>


 <a href="http://localhost/e-commerce/product/cart.php" class="cart">
            <i class='bx bxs-cart-alt'></i>
        </a>
        
    
    <a href="http://localhost/e-commerce/index.php" class="logout" id="logoutBtn">
        LogOut
    </a>
</div>
</div>

<div class="container">
    <div class="intro">
        <h1>PTC E-CART</h1>
        <p>Welcome to PTC E-CART. Our user-friendly website offers You to have a vast variety of goodies and other school supplies. Allowing you to browse and purchase your essential items with ease.
           
         We understand the importance of having the right tools to succeed in your academics, which is why we've curated an extensive collection of top-quality exclusive PTC supplies available.</p> <br>
        <a href = "product/product.php" button class="menu-button" >Check Our Supplies </button> </a>
    </div>
</div>

<div class="category_container">
    <div class="ctg">
        <b>
        <h2>Categories</h2>
        <br>
        <br>
        <br>
        <a href="">School Uniforms</a>
        

        <a href="">P.E Uniforms</a>
        

        <a href="">Mechandise T-Shirts</a>
        
    </b>
    <div class = "images">
        <img src="product/uploaded_img/mUni.png" alt="Ptc Logo" class="lg">
        <img src="product/uploaded_img/PE Shirt Front.png" alt="Ptc Logo" class="lg">
        <img src="product/uploaded_img/Merch Shirt Front .png" alt="Ptc Logo" class="lg">
    </div>

    <div class="bottom-content">
        <h3>Explore More</h3>
        <p>Discover more products and details..</p>
        <a href="product/product.php" class="explore-button">Explore Now</a>
    </div>
    </div>

    <section id="contact" style="padding: 50px 20px; background-color: #f5f5f5;">
  <div style="max-width: 800px; margin: auto; text-align: center;">
    <h2>Contact Us</h2>
    <p>Have questions or inquiries? Send us a message below.</p>
    
    <form action="send_message.php" method="POST" style="text-align: left;">
      <label for="name">Name:</label><br>
      <input type="text" id="name" name="name" required style="width: 100%; padding: 10px;"><br><br>

      <label for="email">Email:</label><br>
      <input type="email" id="email" name="email" required style="width: 100%; padding: 10px;"><br><br>

      <label for="message">Message:</label><br>
      <textarea id="message" name="message" rows="5" required style="width: 100%; padding: 10px;"></textarea><br><br>

      <button type="submit" style="padding: 10px 20px; background-color: green; color: white; border: none;">Send Message</button>
    </form>
  </div>
</section>

   
<div class="footer">
    &copy; 
    PTC E-CART. All rights reserved.
    PATEROS TECHNOLOGICAL COLLEGE
    205 College Street, Sto. Rosario-Kanluran Pateros, Metro Manila
    | <a href="#">Privacy Policy</a>
</div>

<script src="script.js"></script>

</body>
</html>
