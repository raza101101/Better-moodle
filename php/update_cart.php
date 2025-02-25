<?php
session_start();
if (isset($_POST['action']) && $_POST['action'] === 'remove' && isset($_POST['product'])) {
    $productName = $_POST['product'];
    if (isset($_SESSION['cart'][$productName])) {
        unset($_SESSION['cart'][$productName]);
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Product not found in cart']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}
exit();