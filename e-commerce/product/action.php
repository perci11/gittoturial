<?php
session_start();
require 'config.php';

if (isset($_POST['pid'])) {
    $pid = $_POST['pid'];
    $pname = $_POST['pname'];
    $pprice = $_POST['pprice'];
    $pimage = $_POST['pimage'];
    $pcode = $_POST['pcode'];
    $pqty = $_POST['pqty'];
    $total_price = $pprice * $pqty;

   
    $stmt = $conn->prepare("SELECT product_qty FROM product WHERE id = ?");
    $stmt->bind_param("i", $pid);
    $stmt->execute();
    $stmt->bind_result($stock);
    $stmt->fetch();
    $stmt->close();

  
    if ($pqty > $stock) {
        echo '<div class="alert alert-danger alert-dismissible mt-2 animated fadeInOut">
                <button type="button" class="close" data-dismiss="alert">X</button>
                <strong>Order quantity exceeds available stock!</strong>
              </div>';
        exit;
    }

    $newStock = $stock - $pqty;
    $stmt = $conn->prepare("UPDATE product SET product_qty = ? WHERE id = ?");
    $stmt->bind_param("ii", $newStock, $pid);
    $stmt->execute();
    $stmt->close();


    $stmt = $conn->prepare('SELECT product_code, qty FROM cart WHERE product_code=?');
    $stmt->bind_param('s', $pcode);
    $stmt->execute();
    $res = $stmt->get_result();
    $r = $res->fetch_assoc();
    $existingQty = $r['qty'] ?? 0;

    if ($existingQty) {
       
        $newQty = $existingQty + $pqty;
        if ($newQty > $stock) {
            echo '<div class="alert alert-danger alert-dismissible mt-2 animated fadeInOut">
                    <button type="button" class="close" data-dismiss="alert">X</button>
                    <strong>Cannot add more. Exceeds available stock!</strong>
                  </div>';
            exit;
        }
        $newTotalPrice = $newQty * $pprice;
        $query = $conn->prepare('UPDATE cart SET qty=?, total_price=? WHERE product_code=?');
        $query->bind_param('iis', $newQty, $newTotalPrice, $pcode);
        $query->execute();

        echo '<div class="alert alert-success alert-dismissible mt-2 animated fadeInOut">
                <button type="button" class="close" data-dismiss="alert">X</button>
                <strong>Product quantity updated successfully!</strong>
              </div>';
    } else {
      
        $query = $conn->prepare('INSERT INTO cart (product_name, product_price, product_image, qty, total_price, product_code) VALUES (?,?,?,?,?,?)');
        $query->bind_param('ssssss', $pname, $pprice, $pimage, $pqty, $total_price, $pcode);
        $query->execute();

        echo '<div class="alert alert-success alert-dismissible mt-2 animated fadeInOut">
                <button type="button" class="close" data-dismiss="alert">X</button>
                <strong>Product added to cart successfully!</strong>
              </div>';
    }
}


if (isset($_GET['cartItem']) && $_GET['cartItem'] == 'cart_item') {
    $stmt = $conn->prepare('SELECT * FROM cart');
    $stmt->execute();
    $stmt->store_result();
    $rows = $stmt->num_rows;

    echo $rows;
}


if (isset($_GET['remove'])) {
    $id = $_GET['remove'];

    $stmt = $conn->prepare('DELETE FROM cart WHERE id=?');
    $stmt->bind_param('i', $id);
    $stmt->execute();

    $_SESSION['showAlert'] = 'block';
    $_SESSION['message'] = 'Item removed from the cart!';
    header('location:cart.php');
}


if (isset($_GET['clear'])) {
    $stmt = $conn->prepare('DELETE FROM cart');
    $stmt->execute();
    $_SESSION['showAlert'] = 'block';
    $_SESSION['message'] = 'All Items removed from the cart!';
    header('location:cart.php');
}


if (isset($_POST['qty'])) {
    $qty = $_POST['qty'];
    $pid = $_POST['pid'];
    $pprice = $_POST['pprice'];

    
    $stmt = $conn->prepare("SELECT product_qty FROM product WHERE id = ?");
    $stmt->bind_param("i", $pid);
    $stmt->execute();
    $stmt->bind_result($stock);
    $stmt->fetch();
    $stmt->close();

   
    if ($qty > $stock) {
        echo '<div class="alert alert-danger alert-dismissible mt-2 animated fadeInOut">
                <button type="button" class="close" data-dismiss="alert">X</button>
                <strong>Quantity exceeds available stock!</strong>
              </div>';
        exit;
    }


    $tprice = $qty * $pprice;
    $stmt = $conn->prepare('UPDATE cart SET qty=?, total_price=? WHERE id=?');
    $stmt->bind_param('isi', $qty, $tprice, $pid);
    $stmt->execute();
}


if (isset($_POST['action']) && $_POST['action'] == 'order') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $student_number = $_POST['student_number'];
    $phone = $_POST['phone'];
    $products = $_POST['products'];
    $grand_total = $_POST['grand_total'];
    $pmode = $_POST['pmode'];

    
    if (empty($student_number)) {
        die('Student number is required.');
    }

    $order_date = date('Y-m-d H:i:s');

    $stmt = $conn->prepare('INSERT INTO orders (name, email, student_number, phone, pmode, products, amount_paid, order_date) VALUES (?,?,?,?,?,?,?,?)');
    $stmt->bind_param('ssssssss', $name, $email, $student_number, $phone, $pmode, $products, $grand_total, $order_date);
    $stmt->execute();

   
    $stmt2 = $conn->prepare('DELETE FROM cart');
    $stmt2->execute();

    echo '<div class="text-center">
    <h1 class="text-success animated fadeInOut">Your Order Placed Successfully!</h1>
    <h4 class="bg-danger text-light rounded p-2">Items Purchased: ' . $products . '</h4>
    <h4>Your Name: ' . $name . '</h4>
    <h4>Your E-mail: ' . $email . '</h4>
    <h4>Your Student Number: ' . $student_number . '</h4>
    <h4>Your Phone: ' . $phone . '</h4>
    <h4>Total Amount Paid: ' . number_format($grand_total, 2) . '</h4>
    <h4>Payment Mode: ' . $pmode . '</h4>
    <h4 class="alert alert-info mt-3 p-3" style="font-size: 18px; font-weight: bold; background-color: #ff9800; color: white; border-radius: 5px;">
        Please take a screenshot of your receipt!
    </h4>
  </div>';

}
?>
