<?php 
$title = "Pill and Pestle Suppliers"; 
$page_title = "Suppliers"; 

session_start();
include("connect.php");

// Only allow admin access
if (!isset($_SESSION['username']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
  header("Location: ../login.php");
  exit();
}

// --- SUPPLIER LOGIC START ---

// Fetch categories
$categories = [];
try {
    $categories = $pdo->query("SELECT id, name FROM categories ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Error fetching categories: " . $e->getMessage());
}

// Assign a unique color to each category
$baseColors = ['#FFDEE9', '#D0F4DE', '#E4C1F9', '#C1E1C1', '#FAD6A5', '#A0CED9', '#FFDAC1', '#D5AAFF'];
$category_colors = [];
$index = 0;
foreach ($categories as $cat) {
    $category_colors[$cat['id']] = $baseColors[$index % count($baseColors)];
    $index++;
}

// Fetch suppliers and join with category
$suppliers = [];
try {
    $stmt = $pdo->query("
        SELECT suppliers.*, categories.name AS category_name
        FROM suppliers
        LEFT JOIN categories ON suppliers.category_id = categories.id
        ORDER BY categories.name, suppliers.name
    ");
    $suppliers = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Error fetching suppliers: " . $e->getMessage());
}

// Group suppliers by category
$grouped = [];
foreach ($suppliers as $sup) {
    $cat_id = $sup['category_id'] ?? 'uncategorized';
    if (!isset($grouped[$cat_id]))
        $grouped[$cat_id] = [];
    $grouped[$cat_id][] = $sup;
}

// Get User Name for Header
$displayName = isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Admin';
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Suppliers - Pill-and-Pestle</title>
  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
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

    /* --- Sidebar Styling --- */
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

    /* Mobile Offcanvas Sidebar Overrides */
    .offcanvas {
      width: 280px !important;
    }

    /* Mobile Header */
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

    /* --- Main Content Styling --- */
    .page-title-pre {
      font-size: 1.1rem;
      color: var(--primary-navy);
      margin-bottom: 0;
    }

    .page-title {
      font-size: 3rem;
      font-weight: 600;
      color: var(--primary-navy);
      letter-spacing: -1px;
      margin-bottom: 1rem;
    }

    hr {
      border-top: 1px solid #000;
      opacity: 1;
      margin-bottom: 2rem;
    }

    /* --- Supplier Specific Styling --- */
    .btn-custom {
        background-color: var(--primary-navy);
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 8px;
        transition: 0.3s;
    }
    
    .btn-custom:hover {
        background-color: #003380;
        color: white;
    }

    .category-card {
        border-radius: 15px;
        overflow: hidden;
        background: white;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        border: 1px solid #eee;
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .category-header {
        padding: 15px 20px;
        font-weight: 700;
        color: var(--text-dark);
        font-size: 1.1rem;
        border-bottom: 1px solid rgba(0,0,0,0.05);
    }

    .supplier-list-container {
        padding: 0;
        flex-grow: 1;
    }

    .supplier-item {
        padding: 15px 20px;
        border-bottom: 1px solid #f0f0f0;
        transition: background-color 0.2s;
    }

    .supplier-item:last-child {
        border-bottom: none;
    }

    .supplier-item:hover {
        background-color: #fafafa;
    }

    .supplier-name {
        font-weight: 600;
        color: var(--primary-navy);
        margin-bottom: 5px;
    }

    .supplier-detail {
        font-size: 0.85rem;
        color: #666;
        margin-bottom: 2px;
    }

    .btn-edit-sm {
        font-size: 0.75rem;
        padding: 4px 10px;
        margin-top: 8px;
        border: 1px solid var(--primary-navy);
        color: var(--primary-navy);
        background: transparent;
        border-radius: 4px;
    }

    .btn-edit-sm:hover {
        background: var(--primary-navy);
        color: white;
    }

    /* Modal Styling */
    .modal-header {
        background-color: var(--primary-navy);
        color: white;
    }
    .btn-close-white {
        filter: invert(1) grayscale(100%) brightness(200%);
    }

  </style>
</head>

<body>

  <!-- === MOBILE HEADER (Visible only on lg and below) === -->
  <div class="mobile-header d-lg-none">
    <button class="hamburger-btn" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileSidebar">
      &#9776;
    </button>
    <div class="fs-5 fw-bold" style="color: var(--primary-navy);">Pill-and-Pestle</div>
  </div>

  <!-- === MOBILE SIDEBAR (Offcanvas) === -->
  <div class="offcanvas offcanvas-start" tabindex="-1" id="mobileSidebar">
    <div class="offcanvas-header">
      <h5 class="offcanvas-title sidebar-brand">Pill-and-Pestle</h5>
      <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body d-flex flex-column">
      <nav class="nav flex-column w-100">
        <a class="nav-link" href="admin_dashboard.php">Home</a>
        <a class="nav-link" href="medicines_stock.php">Medicine Stock</a>
        <a class="nav-link active" href="suppliers.php">Suppliers</a>
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

      <!-- === DESKTOP SIDEBAR (Visible only on lg and up) === -->
      <div class="col-lg-2 d-none d-lg-flex flex-column p-4 vh-100 sticky-top">
        <div class="sidebar-brand">Pill-and-Pestle</div>
        <nav class="nav flex-column w-100">
          <a class="nav-link" href="dashboard.php">Home</a>
          <a class="nav-link" href="medicines_stock.php">Medicine Stock</a>
          <a class="nav-link active" href="suppliers.php">Suppliers</a>
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
        
        <!-- Header & Action Button -->
        <div class="d-flex justify-content-between align-items-end mb-3">
            <div>
                <p class="page-title-pre">Management</p>
                <h1 class="page-title mb-0">Suppliers</h1>
            </div>
            <button class="btn btn-custom shadow-sm mb-2" data-bs-toggle="modal" data-bs-target="#supplierModal">
                + Add Supplier
            </button>
        </div>
        <hr>

        <!-- Suppliers Grid -->
        <!-- Responsive Logic: 1 col on mobile, 2 on tablet, 3 on large screens -->
        <div class="row g-4">
            <?php foreach ($categories as $cat): ?>
                <?php
                $cat_id = $cat['id'];
                $cat_name = $cat['name'];
                $color = $category_colors[$cat_id] ?? '#f0f0f0';
                ?>
                <div class="col-12 col-md-6 col-xl-4">
                    <div class="category-card">
                        <!-- Colored Header -->
                        <div class="category-header" style="background-color: <?= $color ?>;">
                            <?= htmlspecialchars($cat_name) ?>
                        </div>

                        <!-- List of Suppliers in this Category -->
                        <div class="supplier-list-container">
                        <?php if (!empty($grouped[$cat_id])): ?>
                            <?php foreach ($grouped[$cat_id] as $sup): ?>
                                <div class="supplier-item">
                                    <div class="supplier-name"><?= htmlspecialchars($sup['name']) ?></div>
                                    <div class="supplier-detail">
                                        <strong>Addr:</strong> <?= htmlspecialchars($sup['address']) ?>
                                    </div>
                                    <div class="supplier-detail">
                                        <strong>Tel:</strong> <?= htmlspecialchars($sup['contact']) ?>
                                    </div>
                                    <div class="supplier-detail">
                                        <strong>Email:</strong> <?= htmlspecialchars($sup['email']) ?>
                                    </div>
                                    <button class="btn btn-edit-sm" onclick='editSupplier(<?= json_encode($sup) ?>)'>
                                        Edit Details
                                    </button>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="p-4 text-center text-muted small">
                                No suppliers listed for this category.
                            </div>
                        <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <!-- Bottom spacing for mobile scrolling -->
        <div class="mb-5"></div>
      </main>
    </div>
  </div>

  <!-- Add/Edit Supplier Modal -->
  <div class="modal fade" id="supplierModal" tabindex="-1" aria-labelledby="supplierModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form action="save_supplier.php" method="post" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="supplierModalLabel">Add Supplier</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="supplier_id" id="supplier_id">

                    <div class="mb-3">
                        <label for="name" class="form-label text-muted small">Supplier Name</label>
                        <input type="text" class="form-control" name="name" id="name" required placeholder="Company Name">
                    </div>

                    <div class="mb-3">
                        <label for="category_id" class="form-label text-muted small">Category</label>
                        <select name="category_id" id="category_id" class="form-select" required>
                            <option value="">Select Category</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="contact" class="form-label text-muted small">Contact Number</label>
                            <input type="text" class="form-control" name="contact" id="contact" required placeholder="0912...">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label text-muted small">Email Address</label>
                            <input type="email" class="form-control" name="email" id="email" required placeholder="contact@example.com">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="address" class="form-label text-muted small">Address</label>
                        <textarea class="form-control" name="address" id="address" rows="3" required placeholder="Full office address"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-custom">Save Supplier</button>
                </div>
            </form>
        </div>
    </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  <!-- Edit Script -->
  <script>
        function editSupplier(supplier) {
            document.getElementById('supplier_id').value = supplier.id;
            document.getElementById('name').value = supplier.name;
            document.getElementById('address').value = supplier.address;
            document.getElementById('contact').value = supplier.contact;
            document.getElementById('email').value = supplier.email;
            document.getElementById('category_id').value = supplier.category_id;

            // Update Modal Title
            document.getElementById('supplierModalLabel').innerText = "Edit Supplier";

            const modal = new bootstrap.Modal(document.getElementById('supplierModal'));
            modal.show();
        }

        // Reset modal on close
        const myModal = document.getElementById('supplierModal');
        myModal.addEventListener('hidden.bs.modal', function () {
            document.querySelector('form').reset();
            document.getElementById('supplier_id').value = '';
            document.getElementById('supplierModalLabel').innerText = "Add Supplier";
        });
  </script>
</body>

</html>