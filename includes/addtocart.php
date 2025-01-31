<?php

session_start();
include('F:\xampp\htdocs\MOTO247-Manila\config\db.php');


    if(isset($_SESSION['authenticated'])){
        if(isset($_POST['scope'])){
            $scope = $_SESSION['scope'];
            switch ($scope)
            {
                case "add":
                $prod_id = $_POST['[prod_id'];
                $prod_qty = $_POST['prod_qty'];
                    
                $user_id = $_SESSION['auth_user']['user_id'];

                $insert_query = "INSERT INTO cart (user_id, prod_id, prod_qty) VALUES ('$user_id', '$prod_id', '$prod_qty')";
                $insert_query_run = mysqli_query($conn, $insert_query);


                if($insert_query_run){
                
                    echo 201;
                
                }
                else{
                    echo 500;
                }

                break;

                default: 
                    echo 500;
            }   
        }
    }
    else{
        echo 401;
    }


?>