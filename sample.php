<?php
include 'db_connect.php'; // Include your PDO connection file

header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *"); // Allow requests from any origin

function checkRefundEligibility($customerId, $productId, $orderId) {
    global $conn;

    // Fetch customer details (loyalty points, return-to-accept ratio)
    $customerQuery = $conn->prepare("SELECT loyalty_points, total_refunded, total_spent FROM customers WHERE id = :customerId");
    
    try {
        $customerQuery->execute(['customerId' => $customerId]);
        $customer = $customerQuery->fetch(PDO::FETCH_ASSOC);

        if (!$customer) {
            echo json_encode(['status' => 'error', 'message' => 'Customer not found.']);
            return;
        }

        // Fetch product details (refundable/returnable)
        $productQuery = $conn->prepare("SELECT refundable, returnable FROM products WHERE id = :productId");
        $productQuery->execute(['productId' => $productId]);
        $product = $productQuery->fetch(PDO::FETCH_ASSOC);

        if (!$product) {
            echo json_encode(['status' => 'error', 'message' => 'Product not found.']);
            return;
        }

        // Fetch order details (to get purchase date, etc.)
        $orderQuery = $conn->prepare("SELECT purchase_date FROM orders WHERE id = :orderId AND customer_id = :customerId AND product_id = :productId");
        $orderQuery->execute(['orderId' => $orderId, 'customerId' => $customerId, 'productId' => $productId]);
        $order = $orderQuery->fetch(PDO::FETCH_ASSOC);

        if (!$order) {
            echo json_encode(['status' => 'error', 'message' => 'Order not found.']);
            return;
        }

        // Check product returnability and refundability
        if (!$product['returnable']) {
            echo json_encode(['status' => 'denied', 'message' => 'This product is not returnable, no refund allowed.']);
            return;
        }

        // Calculate return-to-accept ratio
        $totalRefunded = $customer['total_refunded'];
        $totalSpent = $customer['total_spent'];
        $returnToAcceptRatio = ($totalSpent > 0) ? ($totalRefunded / $totalSpent) : 0;

        // Define threshold ratio (e.g., 0.4)
        $thresholdRatio = 0.4;

        // Refund logic based on ratio
        if ($returnToAcceptRatio > $thresholdRatio) {
            // Warn user due to high refund ratio
            echo json_encode([
                'status' => 'warning',
                'message' => 'Warning: You have a high refund rate. Please be aware of the refund policy for future purchases.'
            ]);
            return;
        } else {
            // Check if the order is within the return/refund period (e.g., 7 days)
            $purchaseDate = new DateTime($order['purchase_date']);
            $currentDate = new DateTime();
            $dateDiff = $currentDate->diff($purchaseDate)->days;

            if ($dateDiff > 7) {
                echo json_encode(['status' => 'denied', 'message' => 'Refund/Return period has expired.']);
            } else {
                echo json_encode(['status' => 'approved', 'message' => 'Your refund/replacement request has been approved.']);
            }
            return;
        }
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Failed to process request: ' . $e->getMessage()]);
    }
}

// Example usage (these values should come from a request or session)
$customerId = 1; // Example customer ID
$productId = 123; // Example product ID
$orderId = 456; // Example order ID

checkRefundEligibility($customerId, $productId, $orderId);
?>
