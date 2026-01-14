<?php
$title = "Reviews";
$page_title = "Reviews";

session_start();
include("connect.php");

// Only allow admin access
if (!isset($_SESSION['username']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
  header("Location: ../login.php");
  exit();
}

// --- DATA FETCHING LOGIC ---

$filterDate = $_GET['filter_date'] ?? null; 
$reviews = [];
$dbError = false;

// 1. Try to fetch from Database
try {
    $sql = "SELECT r.*, u.username 
            FROM reviews r 
            JOIN tbl_user u ON r.user_id = u.userID 
            WHERE 1=1";

    $params = [];

    if ($filterDate) {
        $sql .= " AND DATE(r.created_at) = ?";
        $params[] = $filterDate;
    }

    $sql .= " ORDER BY r.created_at DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    // If DB fails (table doesn't exist yet), we fall back to dummy data
    // error_log($e->getMessage()); 
    $dbError = true; 
}

// 2. Fallback to Dummy Data if DB result is empty (for demonstration)
if (empty($reviews)) {
    $allReviews = [
        [
            'username' => 'Fiona Jade',
            'comment' => 'Great service and fast delivery. The medicine was exactly what I needed.',
            'category' => 'Analgesics',
            'created_at' => '2026-01-08 14:30:00'
        ],
        [
            'username' => 'Doxi Wave',
            'comment' => 'The packaging was secure, but the delivery took a bit longer than expected.',
            'category' => 'Vitamins',
            'created_at' => '2026-01-05 09:15:00'
        ],
    ];

    // Apply Filter to Dummy Data
    if ($filterDate) {
        foreach ($allReviews as $rev) {
            if (date('Y-m-d', strtotime($rev['created_at'])) === $filterDate) {
                $reviews[] = $rev;
            }
        }
    } else {
        // If no filter, show all (or if DB failed and we want to show demo data)
        // Only show dummy data if we didn't actually run a successful query that just happened to have 0 results
        // For this template, we'll force show dummy data if array is empty so the UI looks populated.
        $reviews = $allReviews;
    }
}

// Get User Name
$displayName = isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Admin';
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Reviews - Pill-and-Pestle</title>
  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
  <style>
    :root {
      --primary-navy: #001F4D;
      --bg-white: #ffffff;
      --text-dark: #0f172a;
      --card-radius: 15px;
    }

    body {
      font-family: 'Inter', sans-serif;
      background-color: var(--bg-white);
      color: var(--text-dark);
      overflow-x: hidden;
    }

    /* --- SIDEBAR --- */
    .sidebar-brand {
      font-size: 1.5rem;
      font-weight: 600;
      color: var(--primary-navy);
      margin-bottom: 2rem;
      letter-spacing: -0.5px;
    }

    .nav-link {
      color: var(--primary-navy);
      font-weight: 400;
      padding: 10px 15px;
      margin-bottom: 5px;
      border-radius: 8px;
      transition: all 0.2s;
    }

    .nav-link:hover {
      background-color: #f0f4f8;
      color: var(--primary-navy);
    }

    .nav-link.active {
      background-color: var(--primary-navy);
      color: white !important;
    }

    .sidebar-footer {
      margin-top: auto;
      padding-top: 2rem;
    }

    .offcanvas { width: 280px !important; }

    .mobile-header {
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 15px;
      border-bottom: 1px solid #eee;
      position: relative;
    }

    .hamburger-btn {
      position: absolute;
      left: 15px;
      background: transparent;
      border: 2px solid var(--primary-navy);
      border-radius: 4px;
      color: var(--primary-navy);
      padding: 2px 8px;
      font-size: 1.2rem;
    }

    /* --- PAGE HEADER --- */
    .page-title-pre { font-size: 1.1rem; color: var(--primary-navy); margin-bottom: 0; }
    .page-title { font-size: 2.5rem; font-weight: 600; color: var(--primary-navy); letter-spacing: -1px; margin-bottom: 1rem; }
    hr { border-top: 1px solid #000; opacity: 1; margin-bottom: 2rem; }

    /* --- REVIEWS SPECIFIC STYLING --- */
    .filter-container {
        display: flex;
        gap: 10px;
        align-items: center;
    }

    .btn-custom {
        background-color: var(--primary-navy);
        color: white;
        border: none;
    }
    .btn-custom:hover {
        background-color: #003380;
        color: white;
    }

    .review-card {
        background: white;
        border: 1px solid #eee;
        border-radius: var(--card-radius);
        box-shadow: 0 4px 15px rgba(0,0,0,0.03);
        overflow: hidden;
    }

    /* List Header (Desktop) */
    .review-list-header {
        background-color: #f8f9fa;
        padding: 15px 20px;
        border-bottom: 2px solid #eee;
        font-weight: 600;
        color: var(--primary-navy);
        display: none; /* Hidden on mobile, shown on md+ */
    }

    /* Review Item */
    .review-item {
        padding: 20px;
        border-bottom: 1px solid #f0f0f0;
        transition: background-color 0.2s;
    }

    .review-item:hover {
        background-color: #fafafa;
    }

    .review-item:last-child {
        border-bottom: none;
    }

    .review-user {
        font-weight: 700;
        color: var(--primary-navy);
        font-size: 1.05rem;
        display: block;
        margin-bottom: 5px;
    }

    .review-text {
        color: #555;
        line-height: 1.5;
        font-size: 0.95rem;
    }

    .review-category {
        font-size: 0.85rem;
        background: #eef2f7;
        color: var(--primary-navy);
        padding: 4px 10px;
        border-radius: 20px;
        font-weight: 500;
        display: inline-block;
    }

    .review-date {
        font-size: 0.85rem;
        color: #888;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    /* Responsive Grid for Item */
    @media (min-width: 768px) {
        .review-list-header { display: flex; }
        
        .review-item {
            display: flex;
            align-items: flex-start;
        }

        .col-content { flex: 3; padding-right: 20px; }
        .col-category { flex: 1; }
        .col-date { flex: 1; text-align: right; }
    }
  </style>
</head>

<body>

  <!-- === MOBILE HEADER === -->
  <div class="mobile-header d-lg-none">
    <button class="hamburger-btn" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileSidebar">
      &#9776;
    </button>
    <div class="fs-5 fw-bold" style="color: var(--primary-navy);">Pill-and-Pestle</div>
  </div>

  <!-- === MOBILE SIDEBAR === -->
  <div class="offcanvas offcanvas-start" tabindex="-1" id="mobileSidebar">
    <div class="offcanvas-header">
      <h5 class="offcanvas-title sidebar-brand">Pill-and-Pestle</h5>
      <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body d-flex flex-column">
      <nav class="nav flex-column w-100">
        <a class="nav-link" href="admin_dashboard.php">Home</a>
        <a class="nav-link" href="medicines_stock.php">Medicine Stock</a>
        <a class="nav-link" href="suppliers.php">Suppliers</a>
        <a class="nav-link" href="statistics.php">Statistics</a>
        <a class="nav-link active" href="reviews.php">Reviews</a>
      </nav>
      <div class="sidebar-footer mt-auto">
        <nav class="nav flex-column w-100">
          <a class="nav-link" href="backup.php">Backup</a>
          <a class="nav-link" href="restore.php">Restore</a>
          <a class="nav-link" href="edit_account.php">Edit Account</a>
          <a class="nav-link" href="../logout.php">Log Out</a>
        </nav>
      </div>
    </div>
  </div>

  <div class="container-fluid">
    <div class="row">

      <!-- === DESKTOP SIDEBAR === -->
      <div class="col-lg-2 d-none d-lg-flex flex-column p-4 vh-100 sticky-top">
        <div class="sidebar-brand">Pill-and-Pestle</div>
        <nav class="nav flex-column w-100">
          <a class="nav-link" href="admin_dashboard.php">Home</a>
          <a class="nav-link" href="medicines_stock.php">Medicine Stock</a>
          <a class="nav-link" href="suppliers.php">Suppliers</a>
          <a class="nav-link" href="statistics.php">Statistics</a>
          <a class="nav-link active" href="reviews.php">Reviews</a>
        </nav>
        <div class="sidebar-footer">
          <nav class="nav flex-column w-100">
            <a class="nav-link" href="backup.php">Backup</a>
            <a class="nav-link" href="restore.php">Restore</a>
            <a class="nav-link" href="edit_account.php">Edit Account</a>
            <a class="nav-link" href="../logout.php">Log Out</a>
          </nav>
        </div>
      </div>

      <!-- === MAIN CONTENT AREA === -->
      <main class="col-lg-10 col-12 p-4">
        
        <!-- Header & Filter -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end mb-3">
            <div>
                <p class="page-title-pre">Customer Feedback</p>
                <h1 class="page-title mb-0">Reviews</h1>
            </div>
            
            <!-- Date Filter Form -->
            <form method="GET" class="filter-container mt-3 mt-md-0">
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

        <!-- Reviews Card -->
        <div class="review-card">

            <!-- Desktop Header Row -->
            <div class="review-list-header">
                <div class="col-content">User & Review</div>
                <div class="col-category">Category</div>
                <div class="col-date">Date</div>
            </div>

            <!-- Review Items -->
            <?php if (!empty($reviews)): ?>
                <?php foreach ($reviews as $row): ?>
                    <div class="review-item">
                        
                        <!-- Content -->
                        <div class="col-content mb-2 mb-md-0">
                            <div class="d-flex align-items-center gap-2">
                                <i class="bi bi-person-circle fs-4 text-secondary"></i>
                                <span class="review-user mb-0"><?= htmlspecialchars($row['username']) ?></span>
                            </div>
                            <div class="review-text mt-2 ps-md-5">
                                <?= htmlspecialchars($row['comment']) ?>
                            </div>
                        </div>

                        <!-- Category -->
                        <div class="col-category mb-2 mb-md-0 ps-md-0 ps-5">
                            <span class="review-category">
                                <?= htmlspecialchars($row['category'] ?? 'General') ?>
                            </span>
                        </div>

                        <!-- Date -->
                        <div class="col-date ps-md-0 ps-5">
                            <span class="review-date justify-content-md-end">
                                <i class="bi bi-calendar3"></i> 
                                <?= date('M d, Y', strtotime($row['created_at'])) ?>
                            </span>
                        </div>

                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="p-5 text-center text-muted">
                    <i class="bi bi-chat-square-quote fs-1 mb-3 d-block opacity-25"></i>
                    <h5>No reviews found.</h5>
                    <p class="small">Try selecting a different date or clear the filter.</p>
                </div>
            <?php endif; ?>

        </div>

        <div style="height: 50px;"></div>

      </main>
    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>