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

if(isset($_GET['t'])){
    $tracking_no = $_GET['t'];
    $orderData = checkTrackingNum($tracking_no);

    if(mysqli_num_rows($orderData) < 0){
    ?>
    <h4> Something Went Wrong </h4>
    <?php
    die();
    }

}
else{
    ?>
    <h4> Something Went Wrong </h4>
    <?php
    die();
}

$data = mysqli_fetch_array($orderData);

?>


<div class="py-3 bg-primary">
    <div class="container">
        <h1 class="text-white">Order Details</h1>
    </div>
</div>

<!-- Cart table section, outside the blue header -->
<div class="py-5">
    <div class="container">
        <div class="card card-body shadow">
            <div class="row">
                <div class="col-md-12">
                    <div class="card-header">
                        
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h4>Customer Details</h4>
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <label class="fw-bold">Customer Name</label>
                                         <div class="border p-1">
                                             <?= $data['name'];?>
                                        </div> 
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label class="fw-bold">Email</label>
                                         <div class="border p-1">
                                             <?= $data['email'];?>
                                        </div> 
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label class="fw-bold">Contact Number</label>
                                         <div class="border p-1">
                                             <?= $data['phone'];?>
                                        </div> 
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label class="fw-bold">Tracking No.</label>
                                         <div class="border p-1">
                                             <?= $data['tracking_no'];?>
                                        </div> 
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label class="fw-bold">Addres</label>
                                         <div class="border p-1">
                                             <?= $data['address'];?>
                                        </div> 
                                    </div>
                                </div>
                                        <div class="mb-3">
                                            <a href="orders.php" class="btn btn-outline-secondary">
                                                <i class="bi bi-arrow-left"></i> Back to Orders
                                            </a>
                                        </div>
                                
                            </div>
                            <div class="col-md-6">
                                <h4>Order Details </h4>
                                <hr>
                                <table class="table text-center">
                                    <thead>
                                        <tr>
                                            <th>Image</th>
                                            <th>Product</th>
                                            <th>Price</th>
                                            <th>Quantity</th>
                                        </tr>

                                    </thead>
                                    <tbody>
                                       

                                        <?php
                                            $userId = $_SESSION['auth_user']['user_id'];

                                            $order_query = "SELECT o.order_id as oid, o.tracking_no, o.user_id, oi.*, p.*
                                                            FROM `order` o
                                                            INNER JOIN order_item oi ON oi.order_id = o.order_id
                                                            INNER JOIN product p ON p.prod_id = oi.product_id
                                                            WHERE o.user_id = '$userId'
                                                            AND o.tracking_no = '$tracking_no'";
                                            
                                            $order_query_run = mysqli_query($conn, $order_query);

                                            if(mysqli_num_rows($order_query_run) > 0)
                                            {
                                                foreach($order_query_run as $item)
                                                {
                                                    ?>
                                                        <tr>
                                                            <td class="align-middle text-center">
                                                                <img src="<?= $item['image']; ?>" alt="<?= $item['name'] ; ?>" width="50px" height="50px" >
                                                            </td>
                                                            <td class="align-middle text-center">
                                                                    <?= $item['name']; ?>
                                                            </td>
                                                            <td class="align-middle text-center">
                                                                    <?= $item['price']; ?>
                                                            </td>
                                                            <td class="align-middle text-center">
                                                                    <?= $item['quantity']; ?>
                                                            </td>

                                                        
                                                        </tr>
                                                    <?php

                                                }
                                            }
                                        ?>
                                         </tr>
                                   </tbody>
                                </table>
                                <hr>
                                <h4>Total Price : <span class="float-end"><?=$data['total_amount']?></span></h4>
                                <!-- Payment Method Display -->
                                    <div class="border p-3 mb-3 rounded shadow-sm bg-light">
                                        <label class="fw-bold d-block">Payment Method</label>    
                                        <span class="fs-5"><?= htmlspecialchars($data['payment_method']) ?></span>
                                    </div>

                                    <!-- Payment Status -->
                                    <div class="border p-3 mb-3 rounded shadow-sm bg-light">
                                        <label class="fw-bold d-block">Order Status</label>
                                        <span class="fs-5 fw-semibold tx-primary">
                                            <?= htmlspecialchars($data['status']) ?>
                                        </span>
                                    </div>

                                     <!-- Payment Status Display -->
                                     <div class="border p-3 mb-3 rounded shadow-sm bg-light">
                                        <label class="fw-bold d-block">Payment Status</label>
                                        <span class="fs-5 fw-semibold 
                                            <?= strtolower($data['payment_status']) == 'pending' ? 'text-danger' : (strtolower($data['payment_status']) == 'paid' ? 'text-success' : 'text-secondary') ?>">
                                            <?= htmlspecialchars($data['payment_status']) ?>
                                        </span>
                                    </div>

                                    <!-- Pay Here Button & Modal Trigger (Only if GCash is selected) -->
                                    <?php if (strtolower($data['payment_method']) == 'gcash'): ?>
                                        <div class="mt-3 text-center">
                                            <button type="button" class="btn btn-lg btn-primary fw-bold px-4 py-2 shadow" data-bs-toggle="modal" data-bs-target="#gcashModal">
                                                <i class="bi bi-wallet2"></i> Pay Here
                                            </button>
                                        </div>
                                    <?php endif; ?>

                                    <!-- Bootstrap Modal for GCash QR Code -->
                                    <div class="modal fade" id="gcashModal" tabindex="-1" aria-labelledby="gcashModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title fw-bold" id="gcashModalLabel">Scan to Pay via GCash</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body text-center">
                                                    <img src="your-qr-code-image-path.jpg" alt="GCash QR Code" class="img-fluid rounded shadow">
                                                    <p class="mt-3 text-muted">Scan the QR code using your GCash app to proceed with payment.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Back Button -->
  

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include('F:\xampp\htdocs\MOTO247-Manila\views\includes\footer.php'); 
?>

