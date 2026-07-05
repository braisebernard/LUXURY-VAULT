<?php

session_set_cookie_params([
    'httponly' => true,
    'samesite' => 'Lax'
]);

session_start();
include("connect.php");

if(!isset($_SESSION['email'])){
    header("Location: login.php");
    exit();
}

$email = $_SESSION['email'];

/* -------------------------
   Validate Required Fields
------------------------- */

$required = [
    'customer_name',
    'phone',
    'address',
    'payment_method',
    'payment_status',
    'payment_reference',
    'total'
];

foreach($required as $field){

    if(!isset($_POST[$field])){

        header("Location: checkout.php");
        exit();

    }

}

/* -------------------------
   Sanitize Input
------------------------- */

$name = trim($_POST['customer_name']);
$phone = trim($_POST['phone']);
$address = trim($_POST['address']);

$payment = trim($_POST['payment_method']);
$paymentStatus = trim($_POST['payment_status']);
$paymentReference = trim($_POST['payment_reference']);

$total = (float)$_POST['total'];

/* -------------------------
   Validate Payment Method
------------------------- */

$allowedPayments = [
    "M-Pesa",
    "Airtel Money",
    "Tigo Pesa",
    "Cash On Delivery"
];

if(!in_array($payment,$allowedPayments)){
    die("Invalid payment method.");
}

/* -------------------------
   Verify Total From Database
------------------------- */

$stmt = $conn->prepare("

SELECT
SUM(products.price * cart.quantity) AS total

FROM cart

INNER JOIN products

ON cart.product_id = products.id

WHERE cart.user_email=?

");

$stmt->bind_param("s",$email);

$stmt->execute();

$result = $stmt->get_result();

$data = $result->fetch_assoc();

$dbTotal = (float)$data['total'];

if($dbTotal<=0){

    die("Your cart is empty.");

}

$total = $dbTotal;

/* -------------------------
   Default Order Status
------------------------- */

$orderStatus = "Processing";

/* -------------------------
   Start Transaction
------------------------- */

$conn->begin_transaction();

try{

/* -------------------------
   Create Order
------------------------- */

$stmt = $conn->prepare("

INSERT INTO orders

(

customer_name,

phone,

address,

payment_method,

payment_status,

payment_reference,

total,

status,

user_email

)

VALUES

(

?,?,?,?,?,?,?,?,?

)

");

$stmt->bind_param(

"ssssssdss",

$name,

$phone,

$address,

$payment,

$paymentStatus,

$paymentReference,

$total,

$orderStatus,

$email

);

$stmt->execute();

/* -------------------------
   Get New Order ID
------------------------- */

$orderId = $conn->insert_id;

/* -------------------------
   Read Cart Items
------------------------- */

$stmt = $conn->prepare("

SELECT

cart.product_id,

cart.quantity,

products.name,

products.image,

products.price,

products.seller_id

FROM cart

INNER JOIN products

ON cart.product_id = products.id

WHERE cart.user_email=?

");

$stmt->bind_param("s",$email);

$stmt->execute();

$cartItems = $stmt->get_result();

/* -------------------------
   Save Order Items
------------------------- */

while($item = $cartItems->fetch_assoc()){

    $productId = (int)$item['product_id'];

    $sellerId = $item['seller_id'];

    $productName = $item['name'];

    $productImage = $item['image'];

    $price = (float)$item['price'];

    $quantity = (int)$item['quantity'];

    $stmt = $conn->prepare("

    INSERT INTO order_items

    (

    order_id,

    product_id,

    seller_id,

    product_name,

    product_image,

    price,

    quantity

    )

    VALUES

    (

    ?,?,?,?,?,?,?

    )

    ");

    $stmt->bind_param(

    "iiissdi",

    $orderId,

    $productId,

    $sellerId,

    $productName,

    $productImage,

    $price,

    $quantity

    );

    $stmt->execute();

    /* -------------------------
       Reduce Stock
    ------------------------- */

    $stmt = $conn->prepare("

    UPDATE products

    SET quantity = quantity - ?

    WHERE id = ?

    ");

    $stmt->bind_param(

    "ii",

    $quantity,

    $productId

    );

    $stmt->execute();

}

/* -------------------------
   Clear Cart
------------------------- */

$stmt = $conn->prepare("

DELETE FROM cart

WHERE user_email=?

");

$stmt->bind_param("s",$email);

$stmt->execute();

/* -------------------------
   Commit Transaction
------------------------- */

$conn->commit();

/* -------------------------
   Redirect To Email Sandbox
------------------------- */

header(

"Location: send_email.php?"

.

"customer="

.

urlencode($name)

.

"&payment="

.

urlencode($payment)

.

"&payment_status="

.

urlencode($paymentStatus)

.

"&reference="

.

urlencode($paymentReference)

.

"&total="

.

urlencode($total)

.

"&status="

.

urlencode($orderStatus)

);

exit();

}catch(Exception $e){

$conn->rollback();

die("Order Failed: ".$e->getMessage());

}

?>