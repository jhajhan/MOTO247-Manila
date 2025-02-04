<?php
session_start();
include('F:\xampp\htdocs\MOTO247-Manila\includes\user_function.php');
include('F:\xampp\htdocs\MOTO247-Manila\views\includes\header.php');
include('F:\xampp\htdocs\MOTO247-Manila\views\includes\navbar.php');

if (!isset($_SESSION['auth_user']['user_id'])) {
    $_SESSION['message'] = "You need to log in first to access the cart.";        
    header("Location: login.php"); // Redirect to login page
    exit(); // Stop further execution
}

?>


<div class="py-3 bg-primary">
    <div class="container">
        <h1 class="text-white">Orders</h1>
    </div>
</div>

<!-- Cart table section, outside the blue header -->
<div class="py-5">
    <div class="container">
        <div class="card card-body shadow">
            <div class="row">
                <div class="col-md-12">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tracking Number</th>
                                <th>Price</th>
                                <th>Payment Method</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>View</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                // Retrieve orders
                                $orders = getOrders();

                                if (mysqli_num_rows($orders) > 0) {
                                    foreach ($orders as $item) {
                                        ?>
                                        <tr>
                                            <td><?= $item['order_id']; ?></td>
                                            <td><?= $item['tracking_no']; ?></td>
                                            <td><?= number_format($item['total_amount'], 2); ?></td>
                                            <td><?= htmlspecialchars($item['payment_method']); ?></td>
                                            <td><?= htmlspecialchars($item['status']); ?></td>
                                            <td><?= date('Y-m-d H:i:s', strtotime($item['created_at'])); ?></td>
                                            <td>
                                                <a href="order-view.php?t=<?= $item['tracking_no']; ?>" class="btn btn-primary">View Details</a>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                } else {
                                    ?>
                                    <tr>
                                        <td colspan="7" class="text-center">No Orders Found</td>
                                    </tr>
                                    <?php
                                }
                            ?>
                        </tbody>
                    </table>
                   
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include('F:\xampp\htdocs\MOTO247-Manila\views\includes\footer.php'); 
?>

