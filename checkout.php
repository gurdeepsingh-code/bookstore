<?php
    session_start();
    require_once ('mysqli_connect.php');

    if(!$_SESSION['bookid']){
        header("location: index.php");
    }

    $bookid = $_SESSION['bookid'];

    $result = $mysqli->query("SELECT * FROM bookinventory WHERE id = $bookid");

    $num = $result->num_rows;

    if($num > 0) {
        if($row = $result->fetch_object()) {
            
            $image = $row->image;
            $bookname = $row->name;
            $bookDescription = $row->description;
            $bookprice = $row->price;
            $bookInStock = $row->instock;

        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include_once ('components/header.php') ?>

    <div class="container">
        <div class="py-5 text-center">
            <h2>Checkout form</h2>
        </div>

        <div class="row">
            <div class="col-md-4 order-md-2 mb-4">
                <h4 class="d-flex justify-content-between align-items-center mb-3">
                    <span class="text-muted">Your cart</span>
                    <span class="badge badge-secondary badge-pill">3</span>
                </h4>
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <img class="card-img" src="<?php echo $image; ?>" alt="">
                            <div class="card-body text-center">
                                <h4 class="card-title"><?php echo $bookname; ?></h4>
                                <p class="card-text">
                                    <?php echo $bookDescription; ?>           
                                </p>
                                <div class="text-center">
                                    <div class="price text-success"><h5 class="mt-4">$<?php echo $bookprice; ?></h5></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-8 order-md-1">
                <h4 class="mb-3">Billing address</h4>

                    <?php

                        if($_SERVER['REQUEST_METHOD'] === 'POST') {

                            if(isset($_POST['checkout'])) {
                                
                                $errors = [];

                                $checkoutDB_flag = $paymentDB_flag = false;

                                if(empty($_POST['firstName'])) {
                                    $errors[] = "First Name cannot be empty";
                                } else {
                                    $firstName = $mysqli->real_escape_string(trim($_POST['firstName']));
                                }

                                if(empty($_POST['lastName'])) {
                                    $errors[] = "Last Name cannot be empty";
                                } else {
                                    $lastName = $mysqli->real_escape_string(trim($_POST['lastName']));
                                }

                                if(empty($_POST['email'])) {
                                    $errors[] = "Email cannot be empty";
                                } else {
                                    $email = $mysqli->real_escape_string(trim($_POST['email']));
                                }

                                if(empty($_POST['address'])) {
                                    $errors[] = "Address cannot be empty";
                                } else {
                                    $address = $mysqli->real_escape_string(trim($_POST['address']));
                                }

                                if(empty($_POST['paymentMethod'])) {
                                    $errors[] = "Payment Method cannot be empty";
                                } else {
                                    $paymentMethod = $mysqli->real_escape_string(trim($_POST['paymentMethod']));
                                }

                                if(empty($_POST['cardHolder_name'])) {
                                    $errors[] = "Card Holder Name cannot be empty";
                                } else {
                                    $cardHolder_name = $mysqli->real_escape_string(trim($_POST['cardHolder_name']));
                                }

                                if(empty($_POST['card_number'])) {
                                    $errors[] = "Card Number cannot be empty";
                                } else if (strlen($_POST['card_number']) != 16) {
                                    $errors[] = "Enter valid card Number";
                                } else {
                                    $card_number = $mysqli->real_escape_string(trim($_POST['card_number']));
                                }

                                if(empty($_POST['card_expiry'])) {
                                    $errors[] = "Card Expiry cannot be empty";
                                } else {
                                    $card_expiry = $mysqli->real_escape_string(trim($_POST['card_expiry']));
                                }

                                if(empty($_POST['card_cvv'])) {
                                    $errors[] = "Card CVV cannot be empty";
                                } else if( strlen($_POST['card_cvv']) != 3 ) {
                                    $errors[] = "Enter valid CVV number";
                                } else {
                                    $card_cvv = $mysqli->real_escape_string(trim($_POST['card_cvv']));
                                }

                                if(empty($errors)) {
                                    $query = 'INSERT INTO checkout (id, bookid, firstName, lastname, email, address) VALUES (DEFAULT, ?, ?, ?, ?, ?)';

                                    $stmt = mysqli_prepare($mysqli, $query);

                                    mysqli_stmt_bind_param($stmt, 'issss', $bookid, $firstName, $lastName, $email, $address);

                                    $bookid = strip_tags($bookid);
                                    $firstName = strip_tags($_POST['firstName']);
                                    $lastName = strip_tags($_POST['lastName']);
                                    $email = strip_tags($_POST['email']);
                                    $address = strip_tags($_POST['address']);

                                    mysqli_stmt_execute($stmt);

                                    if (mysqli_stmt_affected_rows($stmt) == 1) {
                                        $checkoutDB_flag =  true;
                                    } else {
                                        echo '<p style="font-weight: bold; color: #C00">Failure</p>';
                                        echo '<p>' . mysqli_stmt_error($stmt) . '</p>';
                                    }

                                    mysqli_stmt_close($stmt);

                                    $result = $mysqli->query("SELECT * FROM checkout");

                                    $num = $result->num_rows;

                                    if($num > 0) {
                                        while($row = $result->fetch_object()) {
                                            
                                            $bookingid = $row->id;

                                        }
                                    }

                                    $query = 'INSERT INTO payment (id, booking_id, card_type, card_holder_name, card_number, card_expiry, card_cvv) VALUES (DEFAULT, ?, ?, ?, ?, ?, ?)';

                                    $stmt = mysqli_prepare($mysqli, $query);

                                    mysqli_stmt_bind_param($stmt, 'issisi', $bookingid, $paymentMethod, $cardHolder_name, $card_number, $card_expiry, $card_cvv);

                                    $bookingid = strip_tags($bookingid);
                                    $paymentMethod = strip_tags($_POST['paymentMethod']);
                                    $cardHolder_name = strip_tags($_POST['cardHolder_name']);
                                    $card_number = strip_tags($_POST['card_number']);
                                    $card_expiry = strip_tags($_POST['card_expiry']);
                                    $card_cvv = strip_tags($_POST['card_cvv']);

                                    mysqli_stmt_execute($stmt);

                                    if (mysqli_stmt_affected_rows($stmt) == 1) {
                                        $paymentDB_flag = true;
                                    } else {
                                        echo '<p style="font-weight: bold; color: #C00">Failure</p>';
                                        echo '<p>' . mysqli_stmt_error($stmt) . '</p>';
                                    }

                                    mysqli_stmt_close($stmt);

                                    mysqli_close($mysqli);
                                }

                                if(!empty($errors)) {
                                    echo '<div class="alert alert-danger">
                                    <ul>';
                                        foreach($errors as $err) {
                                            echo  '<li>'.$err.'</li>';
                                        }
                                    echo '</ul>
                                    </div>';
                                }

                                if($checkoutDB_flag == true && $paymentDB_flag == true) {
                                    
                                    header("Location: order-details.php");
                                }

                            }
                        }

                        
                    ?>
                    

                <form action="checkout.php" method="POST" class="mb-5">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="firstName">First name</label>
                            <input type="text" class="form-control" name="firstName" id="firstName" placeholder="" value="<?php if(isset($_POST['firstName'])) { echo $_POST['firstName']; } ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="lastName">Last name</label>
                            <input type="text" class="form-control" name="lastName" id="lastName" placeholder="" value="<?php if(isset($_POST['email'])) { echo $_POST['email']; } ?>">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="you@example.com" value="<?php if(isset($_POST['email'])) { echo $_POST['email']; } ?>">
                    </div>

                    <div class="mb-3">
                        <label for="address">Address</label>
                        <input type="text" class="form-control" id="address" name="address" placeholder="1234 Main St" value="<?php if(isset($_POST['address'])) { echo $_POST['address']; } ?>">
                    </div>
                    <hr class="mb-4">

                    <h4 class="mb-3">Payment</h4>

                    <div class="d-block my-3">
                        <div class="custom-control custom-radio">
                            <input id="credit" name="paymentMethod" value="Credit Card" type="radio" class="custom-control-input" checked required>
                            <label class="custom-control-label" for="credit">Credit card</label>
                        </div>
                        <div class="custom-control custom-radio">
                            <input id="debit" name="paymentMethod" value="Debit Card" type="radio" class="custom-control-input" required>
                            <label class="custom-control-label" for="debit">Debit card</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="cc-name">Name on card</label>
                            <input type="text" class="form-control" id="cc-name"  name="cardHolder_name" placeholder="" value="<?php if(isset($_POST['cardHolder_name'])) { echo $_POST['cardHolder_name']; } ?>">
                            <small class="text-muted">Full name as displayed on card</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="cc-number">Credit card number</label>
                            <input type="number" class="form-control" id="cc-number" name="card_number" placeholder="" value="<?php if(isset($_POST['card_number'])) { echo $_POST['card_number']; } ?>">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="cc-expiration">Expiration (YY/MM)</label>
                            <input type="month" class="form-control" id="cc-expiration" name="card_expiry" placeholder="" value="<?php if(isset($_POST['card_expiry'])) { echo $_POST['card_expiry']; } ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="cc-cvv">CVV</label>
                            <input type="number" class="form-control" id="cc-cvv" name="card_cvv" placeholder="" value="<?php if(isset($_POST['card_cvv'])) { echo $_POST['card_cvv']; } ?>">
                        </div>
                    </div>
                    <hr class="mb-4">
                    <div class="text-center">
                        <button class="btn btn-primary btn-lg btn-block" name="checkout" type="submit">Continue to checkout</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</body>
</html>