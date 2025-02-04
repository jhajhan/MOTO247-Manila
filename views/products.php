<?php
session_start();
include('F:\xampp\htdocs\MOTO247-Manila\views\includes\header.php');
include('F:\xampp\htdocs\MOTO247-Manila\includes\user_function.php');
include('F:\xampp\htdocs\MOTO247-Manila\views\includes\navbar.php');
?>

<div class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1>Products</h1>
                <?php
                    $product = getAllActiveProducts('products');

                    if(mysqli_num_rows($product) > 0) {
                        while($item = mysqli_fetch_assoc($product)) { // Use mysqli_fetch_assoc
                ?>
                        <div class="col-md-4 mb-2">
                            <a href="product_description.php?id=<?= $item['prod_id'] ?>">
                            <div class="card-shadow">
                                <div class="card">
                                    <img src="<?= !empty($item['image']) ? $item['image'] : 'default-image.jpg' ?>" alt="<?= $item['name'] ?>" class="img-fluid">
                                    <h4><?= $item['name'] ?></h4>
                                </div>
                            </div>
                            </a>
                        </div>
                <?php
                        }
                    } else {
                        echo "<h3>No Product found</h3>";
                    }
                ?>
            </div>
        </div>
    </div>
</div>

<?php include('F:\xampp\htdocs\MOTO247-Manila\views\includes\footer.php'); ?>