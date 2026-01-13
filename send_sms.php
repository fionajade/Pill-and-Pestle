<?php
include("connect.php"); // Your database connection
session_start();

$userID = isset($_SESSION['userID']) ? $_SESSION['userID'] : null;
if (!$userID) {
    die("User not logged in.");
}

// Fetch the phone number from the database
$query = $conn->prepare("SELECT contact FROM tbl_user WHERE userID = ?");
$query->bind_param("i", $userID);
$query->execute();
$result = $query->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $recipient = $row['contact']; // now the SMS will go to the user's number
} else {
    die("Phone number not found for this user.");
}

// Your SMS code
$gateway_url = "http://10.150.82.21:8080";
$username    = "sms";
$password    = "ABu1O9aE";

$message = "Payment successful! Ref ID: $paymentID. Thank you for choosing MediTrack. We appreciate your feedback.";

$url = rtrim($gateway_url, '/') . '/messages';
$payload = [
    "phoneNumbers" => [$recipient],
    "message"      => $message
];

$options = [
    'http' => [
        'method'  => 'POST',
        'header'  => [
            'Content-Type: application/json',
            'Authorization: Basic ' . base64_encode("$username:$password")
        ],
        'content' => json_encode($payload)
    ]
];

$context = stream_context_create($options);
$response = file_get_contents($url, false, $context);

echo "<h3>Message Sent</h3>";
echo "<p>Recipient: <strong>$recipient</strong></p>";
echo "<p>Message: <strong>$message</strong></p>";
echo "<h4>API Response:</h4>";
echo "<pre>" . htmlspecialchars($response ?: "No response from SMSGate.") . "</pre>";
?>


