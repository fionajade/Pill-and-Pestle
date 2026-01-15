<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$title = "Pill and Pestle | Reviews";
$page_title = "Reviews";

session_start();
include("connect.php");

if (!isset($_SESSION['username']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$filterDate = $_GET['filter_date'] ?? null;
$reviews = [];
$dbError = false;

try {
    // Query to fetch data directly from sms_incoming table
    $sql = "SELECT * FROM sms_incoming WHERE 1=1";

    $params = [];
    if ($filterDate) {
        $sql .= " AND DATE(received_at) = ?";
        $params[] = $filterDate;
    }

    $sql .= " ORDER BY received_at DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $dbError = true;
    echo "Database error: " . $e->getMessage();
}
?>

<?php include("admin_header.php"); ?>

<head>
    <style>
        /* Adding basic table layout styles */
        .review-card {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-top: 20px;
        }

        /* Table Layout */
        .review-list-header,
        .review-item {
            display: table;
            width: 100%;
            table-layout: fixed;
        }

        .review-list-header {
            font-weight: bold;
            color: #343a40;
            background-color: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
            padding: 10px;
        }

        .review-item {
            border-bottom: 1px solid #f1f1f1;
            padding: 10px;
        }

        /* Table Columns */
        .review-list-header > div,
        .review-item > div {
            display: table-cell;
            padding: 10px;
            text-align: left;
            vertical-align: middle;
        }

        /* Column-specific styling */
        .col-content {
            width: 60%;
            text-align: justify;
        }

        .col-orderID,
        .col-paymentID,
        .col-date {
            width: 15%;
            text-align: center;
        }

        /* Column Borders */
        .review-item .col-orderID,
        .review-item .col-paymentID {
            border-left: 1px solid #dee2e6;
        }

        .review-item .col-date {
            text-align: center;
        }

        /* Empty state */
        .no-reviews {
            text-align: center;
            color: #6c757d;
        }

        .no-reviews i {
            font-size: 50px;
            opacity: 0.25;
        }

        /* Style the filter section */
        .filter-container {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <?php include("admin_sidebar_mobile.php"); ?>

    <div class="container-fluid">
        <div class="row">
            <?php include("admin_sidebar_desktop.php"); ?>

            <main class="col-lg-10 col-12 p-4">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end mb-3">
                    <div>
                        <p class="page-title-pre">Customer Feedback</p>
                        <h1 class="page-title mb-0">Reviews</h1>
                    </div>

                    <form method="GET" class="filter-container">
                        <?php if ($filterDate): ?>
                            <a href="reviews.php" class="btn btn-outline-secondary btn-sm">Show All</a>
                        <?php endif; ?>

                        <div class="input-group">
                            <input type="date" name="filter_date" class="form-control" value="<?= htmlspecialchars($filterDate) ?>" required>
                            <button type="submit" class="btn btn-custom">Go</button>
                        </div>
                    </form>
                </div>

                <hr>

                <div class="review-card">
                    <!-- Header Row -->
                    <div class="review-list-header">
                        <div class="col-content">Review</div>
                        <div class="col-orderID">Order ID</div>
                        <div class="col-paymentID">Payment ID</div>
                        <div class="col-date">Date</div>
                    </div>

                    <?php if (!empty($reviews)): ?>
                        <?php foreach ($reviews as $row): ?>
                            <div class="review-item">
                                <div class="col-content">
                                    <?= htmlspecialchars($row['message']) ?>
                                </div>

                                <div class="col-orderID">
                                    <?= htmlspecialchars($row['order_id'] ?? 'N/A') ?>
                                </div>

                                <div class="col-paymentID">
                                    <?= htmlspecialchars($row['payment_id'] ?? 'N/A') ?>
                                </div>

                                <div class="col-date">
                                    <?= date('M d, Y', strtotime($row['received_at'])) ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="no-reviews p-5">
                            <i class="bi bi-chat-square-quote"></i>
                            <h5>No reviews found.</h5>
                            <p class="small">Try selecting a different date or clear the filter.</p>
                        </div>
                    <?php endif; ?>
                </div>

                <div style="height: 50px;"></div>

            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
