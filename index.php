<?php require_once ('mysqli_connect.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Shop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <?php include ('components/header.php') ?>

    <div class="container">
        <div class="row mt-3">

            <?php

                $result = $mysqli->query("SELECT * FROM bookinventory");

                $num = $result->num_rows;

                if($num > 0) {
                    while($row = $result->fetch_object()) {

                        $id = $row->id;
                        $image = $row->image;
                        $bookname = $row->name;
                        $bookDescription = $row->description;
                        $bookprice = $row->price;
                        $bookInStock = $row->instock;
                        
                        echo '
                            <div class="card col-md-4 p-2 m-0 border-0">
                                <div class="border">
                                    <img src="'.$image.'" width="100%" class="card-img-top" alt="">
                                    <div class="card-body text-center">
                                        <h5 class="card-title">'.$bookname.'</h5>
                                        <p class="card-text">'.$bookDescription.'</p>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <p>Price: $'.$bookprice.'</p>
                                            </div>
                                            <div class="col-md-6">
                                                <p>In-Stock: '.$bookInStock.'</p>
                                            </div>
                                        </div>';
                                        if($bookInStock == 0){
                                            $alert = "`Book out of stock`";
                                            echo '<button onclick="alert('.$alert.')" class="btn btn-danger">Buy Book</button>';
                                        } else {
                                            echo '<a href="buyBook.php?bookid='.$id.'" class="btn btn-primary">Buy Book</a>';
                                        }
                                    echo '</div>
                                </div>
                            </div>
        
                        ';

                    }
                } else {
                    echo 'There are currently 0 Users';
                }

            ?>

        </div>
    </div>
    

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js" ></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.6.0/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.min.js"></script>
</body>
</html>