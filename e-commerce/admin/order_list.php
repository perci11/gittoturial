<?php
session_start();
require 'config.php'; // Include database connection

// Handle order status update logic
if (isset($_GET['id']) && isset($_GET['status'])) {
    $id = intval($_GET['id']);
    $status = $_GET['status'];

    // Validate status input
    if (!in_array($status, ['Pending', 'Received'])) {
        $_SESSION['message'] = "Invalid status provided.";
        $_SESSION['msg_type'] = "danger";
        header("Location: order_management.php");
        exit();
    }

    // Update the order status in the database
    $sql = "UPDATE orders SET status = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('si', $status, $id);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Order status updated to '$status' successfully!";
        $_SESSION['msg_type'] = "success";
    } else {
        $_SESSION['message'] = "Failed to update order status.";
        $_SESSION['msg_type'] = "danger";
    }

    header("Location: order_list.php");
    exit();
}

// Fetch all orders from the database
$result = $conn->query("SELECT * FROM orders") or die($conn->error);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order list</title>

    <!-- Include Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="odrmng.css">
</head>
<body>

<div class="bgtop" id="background">
    <img src="css/ptclogo.png" alt="PTC Logo" class="logo">
    <div class="cart-user">
        <a href="http://localhost/e-commerce/product/cart.php" class="cart">
            <i class='bx bxs-cart-alt'></i>
        </a>
        <a href="admin_page.php" class="logout" id="logoutBtn">Back</a>
        <a href="http://localhost/e-commerce/index.php" class="logout" id="logoutBtn">LogOut</a>
    </div>
</div>

<div class="container">
    <h2 class="text-center">Order list</h2>

    <!-- Flash Messages -->
    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-<?= $_SESSION['msg_type'] ?> alert-dismissible fade show" role="alert">
            <?= $_SESSION['message']; ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <?php unset($_SESSION['message']); ?>
    <?php endif; ?>

    <!-- Orders Table -->
    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Student Number</th>
                    <th>Phone</th>
                    <th>Payment Mode</th>
                    <th>Products</th>
                    <th>Amount Paid</th>
                    <th>Order Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['id']; ?></td>
                        <td><?= htmlspecialchars($row['name']); ?></td>
                        <td><?= htmlspecialchars($row['email']); ?></td>
                        <td><?= htmlspecialchars($row['student_number']); ?></td>
                        <td><?= htmlspecialchars($row['phone']); ?></td>
                        <td><?= htmlspecialchars($row['pmode']); ?></td>
                        <td><?= htmlspecialchars($row['products']); ?></td>
                        <td>PHP <?= number_format($row['amount_paid'], 2); ?></td>
                        <td><?= htmlspecialchars($row['order_date']); ?></td>
                        <td><?= htmlspecialchars($row['status']); ?></td>
                        <td>
                            <?php if ($row['status'] === 'Pending'): ?>
                                <a href="order_list.php?id=<?= $row['id']; ?>&status=Received"
                                   class="btn btn-success btn-sm">Mark as Received</a>
                            <?php elseif ($row['status'] === 'Received'): ?>
                                <a href="order_list.php?id=<?= $row['id']; ?>&status=Pending"
                                   class="btn btn-warning btn-sm">Mark as Pending</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Include Bootstrap JS and Dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
