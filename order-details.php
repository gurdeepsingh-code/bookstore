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


    $bookInStock = $bookInStock - 1;

    $query = "UPDATE bookinventory SET instock = $bookInStock WHERE id = $bookid";

    if ($mysqli->query($query) === TRUE) {
    } else {
        echo "Error updating record: " . $mysqli->error;
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="row text-center">
            <h1 class="text-danger">Order Confirmed</h1>
            <p>Thank you for choosing our service.</p>
            <p>Order Details: </p>
            <p class="text-muted">Book Name: <?php echo $bookname; ?></p>
            <a href="index.php">
                <button class="btn btn-danger">Home</button>
            </a>
        </div>
    </div>
</body>
</html>