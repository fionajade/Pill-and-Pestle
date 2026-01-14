<?php
$title = "Pill and Pestle Dashboard";
$page_title = "Dashboard";

session_start();
include("connect.php");

// Only allow admin access
if (!isset($_SESSION['username']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
  header("Location: ../login.php");
  exit();
}

// --- DATA FETCHING ---

// Total Statistics
$totalStocks = $pdo->query("SELECT SUM(quantity) FROM medicines")->fetchColumn();
$totalSuppliers = $pdo->query("SELECT COUNT(*) FROM suppliers")->fetchColumn();
$totalSales = $pdo->query("SELECT IFNULL(SUM(total_price), 0) FROM sales")->fetchColumn();
$totalCustomers = $pdo->query("SELECT COUNT(*) FROM tbl_user WHERE role = 'user'")->fetchColumn();
$expiringSoon = $pdo->query("SELECT COUNT(*) FROM medicines WHERE expiry_date <= DATE_ADD(CURDATE(), INTERVAL 30 DAY)")->fetchColumn();

// Fetch top stock categories
$topStockCategories = [];
try {
  $stockStmt = $pdo->query("SELECT c.id, c.name FROM categories c
        JOIN medicines m ON c.id = m.category_id
        GROUP BY c.id ORDER BY COUNT(m.medicine_id) DESC LIMIT 5");
  $topStockCategories = $stockStmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  error_log("Category fetch error: " . $e->getMessage());
}

// Fetch top suppliers
$topSuppliers = [];
try {
  $supplierStmt = $pdo->query("SELECT c.name AS category_name, s.name AS supplier_name
        FROM categories c
        JOIN medicines m ON c.id = m.category_id
        JOIN suppliers s ON m.supplier_id = s.id
        GROUP BY c.name, s.name
        ORDER BY c.name LIMIT 5");
  $topSuppliers = $supplierStmt->fetchAll();
} catch (PDOException $e) {
  error_log("Supplier fetch error: " . $e->getMessage());
}

// Get User Name for Header
$displayName = isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Admin';
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard - Pill-and-Pestle</title>
  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    /* --- ROOT VARIABLES --- */
    :root {
      --primary-navy: #001F4D;
      --bg-white: #ffffff;
      --text-dark: #0f172a;
      --card-radius: 20px;
    }

    body {
      font-family: 'Inter', sans-serif;
      background-color: var(--bg-white);
      color: var(--text-dark);
      overflow-x: hidden;
    }

    /* --- SIDEBAR STYLING --- */
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

    .offcanvas {
      width: 280px !important;
    }

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

    /* --- PAGE CONTENT STYLING --- */
    .page-title-pre {
      font-size: 1.1rem;
      color: var(--primary-navy);
      margin-bottom: 0;
    }

    .page-title {
      font-size: 2.5rem;
      font-weight: 600;
      color: var(--primary-navy);
      letter-spacing: -1px;
      margin-bottom: 1rem;
    }

    hr {
      border-top: 1px solid #000;
      opacity: 1;
      margin-bottom: 3rem;
    }

    /* --- DASHBOARD CARDS (Dark Theme) --- */
    .dark-card {
      background-color: var(--primary-navy);
      color: white;
      border-radius: var(--card-radius);
      padding: 2.5rem 2rem;
      height: 100%;
      min-height: 550px;
      /* Ensures uniform height */
      display: flex;
      flex-direction: column;
      align-items: center;
      text-align: center;
      box-shadow: 0 10px 30px rgba(0, 31, 77, 0.3);
      transition: transform 0.3s;
    }

    .dark-card:hover {
      transform: translateY(-5px);
    }

    .card-heading {
      font-size: 2rem;
      line-height: 1.1;
      font-weight: 300;
      margin-bottom: 2rem;
    }

    .card-heading span {
      font-weight: 600;
      display: block;
    }

    .card-list {
      width: 100%;
      flex-grow: 1;
      display: flex;
      flex-direction: column;
      gap: 1.2rem;
      font-size: 1.1rem;
      margin-bottom: 2rem;
    }

    .list-item {
      color: rgba(255, 255, 255, 0.9);
      text-decoration: none;
      font-weight: 300;
      transition: 0.2s;
    }

    a.list-item:hover {
      color: #fff;
      transform: translateX(5px);
    }

    .btn-view-more {
      margin-top: auto;
      background: transparent;
      border: 1px solid rgba(255, 255, 255, 0.5);
      color: white;
      width: 80%;
      padding: 12px;
      border-radius: 50px;
      transition: all 0.3s;
      font-size: 0.9rem;
      text-decoration: none;
      text-transform: uppercase;
      letter-spacing: 1px;
    }

    .btn-view-more:hover {
      background: white;
      color: var(--primary-navy);
      border-color: white;
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
        <a class="nav-link active" href="admin_dashboard.php">Home</a>
        <a class="nav-link" href="medicines_stock.php">Medicine Stock</a>
        <a class="nav-link" href="suppliers.php">Suppliers</a>
        <a class="nav-link" href="statistics.php">Statistics</a>
        <a class="nav-link" href="reviews.php">Reviews</a>
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
          <a class="nav-link active" href="admin_dashboard.php">Home</a>
          <a class="nav-link" href="medicines_stock.php">Medicine Stock</a>
          <a class="nav-link" href="suppliers.php">Suppliers</a>
          <a class="nav-link" href="statistics.php">Statistics</a>
          <a class="nav-link" href="reviews.php">Reviews</a>
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

        <p class="page-title-pre">Welcome back, <?= $displayName ?>!</p>
        <h1 class="page-title">Dashboard</h1>
        <hr>

        <!-- CARDS ROW -->
        <div class="row g-4 justify-content-center">

          <!-- Card 1: Top Stock Categories -->
          <div class="col-lg-4 col-md-6 col-12">
            <div class="dark-card">
              <div class="card-heading">
                Top Stock<br><span>Categories</span>
              </div>

              <div class="card-list">
                <?php foreach ($topStockCategories as $cat): ?>
                  <a href="medicines_stock.php#category-<?= $cat['id'] ?>" class="list-item">
                    <?= htmlspecialchars($cat['name']) ?>
                  </a>
                <?php endforeach; ?>
              </div>

              <a href="medicines_stock.php" class="btn-view-more">View More</a>
            </div>
          </div>

          <!-- Card 2: Top Suppliers -->
          <div class="col-lg-4 col-md-6 col-12">
            <div class="dark-card">
              <div class="card-heading">
                Top<br><span>Suppliers</span>
              </div>

              <div class="card-list" style="justify-content: center;">
                <?php if (!empty($topSuppliers)): ?>
                  <?php foreach ($topSuppliers as $sup): ?>
                    <div class="list-item">
                      <?= htmlspecialchars($sup['supplier_name']) ?>
                    </div>
                  <?php endforeach; ?>
                <?php else: ?>
                  <span class="list-item">No Supplier Data<br>Available.</span>
                <?php endif; ?>
              </div>

              <a href="suppliers.php" class="btn-view-more">View More</a>
            </div>
          </div>

          <!-- Card 3: Statistics -->
          <div class="col-lg-4 col-md-6 col-12">
            <div class="dark-card">
              <div class="card-heading">
                Quick<br><span>Statistics</span>
              </div>

              <div class="card-list">
                <div class="list-item">
                  Customers: <?= number_format($totalCustomers) ?>
                </div>
                <div class="list-item">
                  Total Sales: ₱<?= number_format($totalSales, 2) ?>
                </div>
                <div class="list-item">
                  Medicine Stock: <?= number_format($totalStocks) ?>
                </div>
                <div class="list-item">
                  Expiring (30d): <?= number_format($expiringSoon) ?>
                </div>
                <div class="list-item">
                  Suppliers: <?= number_format($totalSuppliers) ?>
                </div>
              </div>

              <a href="statistics.php" class="btn-view-more">View More</a>
            </div>
          </div>

        </div>

        <div class="mb-5"></div>
      </main>
    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>