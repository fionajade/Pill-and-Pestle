<?php 
$title = "Medicine Stock"; 
$page_title = "Medicine Stock"; 

session_start();
include("connect.php");

// Only allow admin access
if (!isset($_SESSION['username']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
  header("Location: ../login.php");
  exit();
}

// --- PHP FORM HANDLERS ---

// 1. Add Category
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_category'])) {
    $name = trim($_POST['category_name']);
    if ($name !== '') {
        try {
            $stmt = $pdo->prepare("INSERT INTO categories (name) VALUES (?)");
            $stmt->execute([$name]);
            header("Location: " . $_SERVER['PHP_SELF']); 
            exit;
        } catch (PDOException $e) {
            $error_msg = "Error adding category: " . $e->getMessage();
        }
    }
}

// 2. Delete Category
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_category'])) {
    try {
        $stmt = $pdo->prepare("DELETE FROM categories WHERE id = ?");
        $stmt->execute([$_POST['category_id']]);
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    } catch (PDOException $e) {
        $error_msg = "Cannot delete category (it likely contains medicines).";
    }
}

// 3. Add Medicine
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_medicine'])) {
    try {
        $stmt = $pdo->prepare("INSERT INTO medicines (name, unit_price, quantity, expiry_date, category_id, supplier_id, unit) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $unit = $_POST['unit'] ?? 'Box'; 
        
        $stmt->execute([
            $_POST['medicine_name'],
            $_POST['unit_price'],
            $_POST['quantity'],
            $_POST['expiry_date'],
            $_POST['category_id'],
            $_POST['supplier_id'],
            $unit
        ]);
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    } catch (PDOException $e) {
        $error_msg = "Error adding medicine: " . $e->getMessage();
    }
}

// --- DATA FETCHING ---
$categories = $pdo->query("SELECT * FROM categories ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);
$suppliers = $pdo->query("SELECT * FROM suppliers ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Medicine Stock - Pill-and-Pestle</title>
  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    /* --- ROOT VARIABLES (Merged for compatibility) --- */
    :root {
      --primary-navy: #001F4D;
      --bg-white: #ffffff;
      --text-dark: #0f172a;
      
      /* Mapping your CSS variables to the Theme */
      --primary-dark: #001F4D; 
      --text-gray: #6c757d;
    }

    body {
      font-family: 'Inter', sans-serif;
      background-color: var(--bg-white);
      color: var(--text-dark);
      overflow-x: hidden;
    }

    /* --- SIDEBAR & LAYOUT (Standardized) --- */
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

    /* ========================================= */
    /* === YOUR CUSTOM MEDICINES STOCK CSS === */
    /* ========================================= */

    /* Secondary Sticky Navbar */
    .cat-navbar {
        background-color: var(--primary-dark);
        border-radius: 50px;
        padding: 0.5rem 1rem;
        margin-bottom: 2rem;
        position: sticky;
        top: 1rem;
        z-index: 900;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        
        /* Layout tweak for flex items */
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 10px;
    }

    .cat-nav-link {
        color: rgba(255, 255, 255, 0.7);
        text-decoration: none;
        padding: 5px 15px;
        transition: 0.3s;
        font-size: 0.9rem;
    }

    .cat-nav-link:hover,
    .cat-nav-link:focus {
        color: #fff;
    }

    /* Action Cards (White) */
    .light-card {
        background: #fff;
        border: 1px solid #e0e0e0;
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.03);
    }

    .light-card h5 {
        color: var(--primary-dark);
        font-weight: 600;
        margin-bottom: 1.5rem;
    }

    /* Medicine Cards */
    .med-section-title {
        color: var(--primary-dark);
        font-size: 1.8rem;
        font-weight: 600;
        margin-bottom: 1.5rem;
        margin-top: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 1px solid #eee;
        scroll-margin-top: 100px; /* Fix for sticky header covering title */
    }

    .medicine-card {
        background: #fff;
        border: 1px solid #f0f0f0;
        border-radius: 15px;
        padding: 1.5rem;
        height: 100%;
        transition: transform 0.3s, box-shadow 0.3s;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.03);
    }

    .medicine-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 31, 63, 0.15);
        border-color: var(--primary-dark);
    }

    .med-name {
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--primary-dark);
        margin-bottom: 0.5rem;
        white-space: nowrap; 
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .med-info {
        font-size: 0.9rem;
        color: var(--text-gray);
        margin-bottom: 0.2rem;
        display: flex;
        justify-content: space-between;
    }

    .med-info span {
        color: var(--primary-dark);
        font-weight: 500;
    }

    /* Form Elements */
    .form-control,
    .form-select {
        border-radius: 10px;
        border: 1px solid #dee2e6;
        padding: 0.6rem 1rem;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: var(--primary-dark);
        box-shadow: 0 0 0 2px rgba(0, 31, 63, 0.1);
    }

    .btn-custom-dark {
        background-color: var(--primary-dark);
        color: white;
        border-radius: 10px;
        border: none;
        padding: 0.6rem 1.5rem;
        transition: 0.3s;
    }

    .btn-custom-dark:hover {
        background-color: #003366;
        color: white;
    }

    html {
        scroll-behavior: smooth;
        scroll-padding-top: 120px; /* Offset for sticky navs */
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
        <a class="nav-link" href="dashboard.php">Home</a>
        <a class="nav-link active" href="medicines_stock.php">Medicine Stock</a>
        <a class="nav-link" href="suppliers.php">Suppliers</a>
        <a class="nav-link" href="statistics.php">Statistics</a>
        <a class="nav-link" href="reviews.php">Reviews</a>
      </nav>
      <div class="sidebar-footer mt-auto">
        <nav class="nav flex-column w-100">
          <a class="nav-link" href="#">Backup</a>
          <a class="nav-link" href="#">Restore</a>
          <a class="nav-link" href="#">Edit Account</a>
          <a class="nav-link" href="#">Log Out</a>
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
          <a class="nav-link active" href="medicines_stock.php">Medicine Stock</a>
          <a class="nav-link" href="suppliers.php">Suppliers</a>
          <a class="nav-link" href="statistics.php">Statistics</a>
          <a class="nav-link" href="reviews.php">Reviews</a>
        </nav>
        <div class="sidebar-footer">
          <nav class="nav flex-column w-100">
            <a class="nav-link" href="#">Backup</a>
            <a class="nav-link" href="#">Restore</a>
            <a class="nav-link" href="#">Edit Account</a>
            <a class="nav-link" href="#">Log Out</a>
          </nav>
        </div>
      </div>

      <!-- === MAIN CONTENT AREA === -->
      <main class="col-lg-10 col-12 p-4">
        
        <p class="page-title-pre">Inventory Management</p>
        <h1 class="page-title">Medicine Stock</h1>
        <hr>

        <?php if(isset($error_msg)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error_msg) ?></div>
        <?php endif; ?>

        <div class="row">

            <!-- === LEFT COLUMN: Management Forms === -->
            <div class="col-lg-4 col-12">

                <!-- 1. Add Category Form -->
                <div class="light-card">
                    <h5>Manage Categories</h5>
                    <form method="POST" class="mb-3">
                        <div class="input-group">
                            <input type="text" name="category_name" class="form-control" placeholder="New Category Name" required>
                            <button name="add_category" class="btn btn-custom-dark">Add</button>
                        </div>
                    </form>

                    <!-- List of Categories -->
                    <div style="max-height: 200px; overflow-y: auto;">
                        <ul class="list-group list-group-flush">
                            <?php foreach ($categories as $cat): ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center bg-transparent px-0 py-2">
                                    <span style="color: var(--text-gray); font-weight:500;">
                                        <?= htmlspecialchars($cat['name']) ?>
                                    </span>
                                    <form method="POST" onsubmit="return confirm('Delete category: <?= addslashes($cat['name']) ?>?')" style="margin:0;">
                                        <input type="hidden" name="category_id" value="<?= $cat['id'] ?>">
                                        <button name="delete_category" class="btn btn-outline-danger btn-sm py-0 px-2" style="font-size: 0.7rem;">Del</button>
                                    </form>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>

                <!-- 2. Add Medicine Form -->
                <div class="light-card">
                    <h5>Add New Medicine</h5>
                    <form method="POST" class="row g-3">
                        <div class="col-12">
                            <label class="form-label small text-muted mb-1">Medicine Name</label>
                            <input type="text" name="medicine_name" class="form-control" placeholder="e.g. Paracetamol" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label small text-muted mb-1">Price (₱)</label>
                            <input type="number" name="unit_price" step="0.01" class="form-control" placeholder="0.00" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label small text-muted mb-1">Quantity</label>
                            <input type="number" name="quantity" class="form-control" placeholder="0" required>
                        </div>
                        <div class="col-12">
                             <label class="form-label small text-muted mb-1">Unit</label>
                             <input type="text" name="unit" class="form-control" placeholder="e.g. Box/Strip">
                        </div>
                        <div class="col-12">
                            <label class="form-label small text-muted mb-1">Expiry Date</label>
                            <input type="date" name="expiry_date" class="form-control" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label small text-muted mb-1">Category</label>
                            <select name="category_id" class="form-select" required>
                                <option value="">Select Category</option>
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label small text-muted mb-1">Supplier</label>
                            <select name="supplier_id" class="form-select" required>
                                <option value="">Select Supplier</option>
                                <?php foreach ($suppliers as $sup): ?>
                                    <option value="<?= $sup['id'] ?>"><?= htmlspecialchars($sup['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-12 mt-4">
                            <button name="add_medicine" class="btn btn-custom-dark w-100">Add Medicine</button>
                        </div>
                    </form>
                </div>

            </div>

            <!-- === RIGHT COLUMN: Stock Display === -->
            <div class="col-lg-8 col-12">
                
                <!-- Sticky Category Pills -->
                <div class="cat-navbar">
                    <?php foreach ($categories as $category): ?>
                        <a class="cat-nav-link" href="#category-<?= $category['id'] ?>">
                            <?= htmlspecialchars($category['name']) ?>
                        </a>
                    <?php endforeach; ?>
                </div>

                <!-- Loop Categories and Medicines -->
                <?php foreach ($categories as $category): ?>
                    <div id="category-<?= $category['id'] ?>" class="mb-5">
                        <h3 class="med-section-title"><?= htmlspecialchars($category['name']) ?></h3>

                        <?php
                        // Fetch medicines for this category
                        $stmt = $pdo->prepare("SELECT * FROM medicines WHERE category_id = ? ORDER BY name");
                        $stmt->execute([$category['id']]);
                        $medicines = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        ?>

                        <?php if ($medicines): ?>
                            <div class="row g-3">
                                <?php foreach ($medicines as $med): ?>
                                    <div class="col-md-6 col-xl-4">
                                        <div class="medicine-card">
                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                <h4 class="med-name" title="<?= htmlspecialchars($med['name']) ?>">
                                                    <?= htmlspecialchars($med['name']) ?>
                                                </h4>
                                                <span class="badge bg-light text-secondary border">#<?= $med['medicine_id'] ?></span>
                                            </div>

                                            <div class="med-info">
                                                Price: <span>₱<?= number_format($med['unit_price'], 2) ?></span>
                                            </div>
                                            <div class="med-info">
                                                Stock: <span class="<?= $med['quantity'] < 20 ? 'text-danger' : 'text-success' ?>">
                                                    <?= $med['quantity'] ?>
                                                </span>
                                            </div>
                                            <div class="med-info">
                                                Expiry: <span class="<?= (strtotime($med['expiry_date']) < strtotime('+30 days')) ? 'text-danger fw-bold' : '' ?>">
                                                    <?= $med['expiry_date'] ?>
                                                </span>
                                            </div>

                                            <hr class="my-3 opacity-25">

                                            <!-- Update Stock Controls -->
                                            <form method="post" action="update_stock.php" class="d-flex align-items-center gap-2 mt-2">
                                                <input type="hidden" name="medicine_id" value="<?= $med['medicine_id'] ?>">
                                                <input type="number" name="quantity" class="form-control form-control-sm"
                                                    style="width: 70px;" min="1" placeholder="Qty" required>
                                                
                                                <button type="submit" name="action" value="add" title="Add Stock"
                                                    class="btn btn-success btn-sm rounded-circle d-flex align-items-center justify-content-center"
                                                    style="width: 32px; height: 32px;">+</button>
                                                
                                                <button type="submit" name="action" value="subtract" title="Reduce Stock"
                                                    class="btn btn-danger btn-sm rounded-circle d-flex align-items-center justify-content-center"
                                                    style="width: 32px; height: 32px;">−</button>
                                            </form>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <div class="alert alert-light text-center border text-muted small">
                                No medicines available in this category.
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
            <!-- End Right Column -->

        </div>
      </main>
    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>