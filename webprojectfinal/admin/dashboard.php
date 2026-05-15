<?php
require_once '../includes/config.php';
if (session_status() === PHP_SESSION_NONE) session_start();
requireAdmin();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard – Dastarkhan Admin</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>
:root {
  --gold: #c9a84c; --gold-light: #f0d98a; --dark: #1a1208; --sidebar-w: 260px;
  --bg: #f8f4ee; --card: #fff; --text: #2c2c2c; --muted: #888;
  --border: #ede8de; --success: #2e7d32; --warning: #e65100; --danger: #c62828;
}
* { box-sizing: border-box; }
body { font-family:'Inter',sans-serif; background:var(--bg); color:var(--text); margin:0; }

/* SIDEBAR */
.sidebar {
  position: fixed; left:0; top:0; bottom:0; width:var(--sidebar-w);
  background: linear-gradient(180deg, var(--dark) 0%, #2d1c0a 100%);
  display:flex; flex-direction:column; z-index:1000;
  box-shadow: 4px 0 20px rgba(0,0,0,.3);
}
.sidebar-brand {
  padding: 28px 24px 20px;
  border-bottom: 1px solid rgba(201,168,76,.2);
}
.sidebar-brand h1 {
  font-family:'Playfair Display',serif; font-size:1.5rem;
  color:#fff; margin:0; line-height:1;
}
.sidebar-brand h1 span { color:var(--gold); }
.sidebar-brand small { color: rgba(255,255,255,.4); font-size:.7rem; letter-spacing:2px; text-transform:uppercase; }
.sidebar-nav { flex:1; padding:12px 0; overflow-y:auto; }
.nav-section { padding: 16px 20px 6px; font-size:.65rem; letter-spacing:2px; color:rgba(255,255,255,.3); text-transform:uppercase; }
.nav-link {
  display:flex; align-items:center; gap:12px;
  padding: 11px 24px; color:rgba(255,255,255,.65); font-size:.875rem;
  font-weight:500; text-decoration:none; transition:all .2s; border-left:3px solid transparent;
}
.nav-link:hover { color:#fff; background:rgba(201,168,76,.1); }
.nav-link.active { color:var(--gold); background:rgba(201,168,76,.12); border-left-color:var(--gold); }
.nav-link i { font-size:1.1rem; width:20px; text-align:center; }
.sidebar-footer { padding:16px 24px; border-top:1px solid rgba(201,168,76,.15); }
.admin-info { display:flex; align-items:center; gap:10px; color:rgba(255,255,255,.7); font-size:.8rem; }
.admin-avatar { width:34px;height:34px;border-radius:50%;background:var(--gold);display:flex;align-items:center;justify-content:center;color:var(--dark);font-weight:700;font-size:.9rem; }

/* MAIN */
.main-content { margin-left:var(--sidebar-w); min-height:100vh; }
.topbar {
  background:var(--card); border-bottom:1px solid var(--border);
  padding: 16px 32px; display:flex; align-items:center; justify-content:space-between;
  position:sticky; top:0; z-index:100; box-shadow:0 2px 8px rgba(0,0,0,.05);
}
.topbar-title { font-size:1.2rem; font-weight:700; color:var(--dark); }
.page-body { padding: 28px 32px; }

/* STAT CARDS */
.stat-card {
  background:var(--card); border-radius:16px; padding:24px;
  border:1px solid var(--border); transition:transform .2s, box-shadow .2s;
}
.stat-card:hover { transform:translateY(-3px); box-shadow:0 8px 24px rgba(0,0,0,.08); }
.stat-icon {
  width:52px; height:52px; border-radius:14px;
  display:flex; align-items:center; justify-content:center; font-size:1.4rem; margin-bottom:14px;
}
.stat-value { font-size:1.9rem; font-weight:700; line-height:1; margin-bottom:4px; }
.stat-label { font-size:.8rem; color:var(--muted); font-weight:500; }
.stat-change { font-size:.75rem; margin-top:8px; }

/* TABLES */
.data-table { width:100%; border-collapse:collapse; }
.data-table th { padding:12px 16px; font-size:.78rem; font-weight:600; color:var(--muted); text-transform:uppercase; letter-spacing:.5px; border-bottom:2px solid var(--border); background:#faf7f2; }
.data-table td { padding:14px 16px; font-size:.88rem; border-bottom:1px solid var(--border); vertical-align:middle; }
.data-table tbody tr:hover { background:#faf7f2; }

/* STATUS BADGES */
.badge-status {
  padding:4px 12px; border-radius:20px; font-size:.72rem; font-weight:600; text-transform:capitalize;
}
.s-pending   { background:#fff3e0; color:#e65100; }
.s-confirmed { background:#e3f2fd; color:#1565c0; }
.s-preparing { background:#f3e5f5; color:#7b1fa2; }
.s-ready     { background:#e8f5e9; color:#2e7d32; }
.s-delivered { background:#e8f5e9; color:#1b5e20; }
.s-cancelled { background:#ffebee; color:#c62828; }

/* CARDS */
.panel-card { background:var(--card); border-radius:16px; border:1px solid var(--border); overflow:hidden; }
.panel-header { padding:18px 24px; border-bottom:1px solid var(--border); display:flex; align-items:center; justify-content:space-between; }
.panel-header h5 { margin:0; font-weight:700; font-size:1rem; }
.panel-body { padding:24px; }

/* MENU CARD */
.menu-item-card { border:1px solid var(--border); border-radius:12px; overflow:hidden; transition:transform .2s; }
.menu-item-card:hover { transform:translateY(-3px); box-shadow:0 8px 20px rgba(0,0,0,.08); }
.menu-item-card img { width:100%; height:140px; object-fit:cover; }
.menu-item-info { padding:12px; }
.menu-item-info h6 { font-weight:700; font-size:.9rem; margin-bottom:4px; }
.menu-item-info .price { color:var(--gold); font-weight:700; font-size:1rem; }

/* MODAL */
.modal-content { border-radius:16px; border:none; }
.modal-header { background:var(--dark); color:#fff; border-radius:16px 16px 0 0; }
.modal-header .btn-close { filter:invert(1); }

/* TABS */
.admin-tabs .nav-link { color:var(--muted); font-weight:500; border-bottom:2px solid transparent; border-radius:0; padding:10px 20px; }
.admin-tabs .nav-link.active { color:var(--gold); border-bottom-color:var(--gold); background:none; font-weight:700; }

/* FORM */
.form-control, .form-select {
  border:2px solid var(--border); border-radius:10px; padding:10px 14px;
  transition:border-color .2s; font-size:.9rem;
}
.form-control:focus, .form-select:focus { border-color:var(--gold); box-shadow:0 0 0 3px rgba(201,168,76,.15); }
.btn-gold { background:linear-gradient(135deg,var(--gold),#a07830); color:#fff; border:none; border-radius:10px; font-weight:600; padding:10px 24px; cursor:pointer; transition:.2s; }
.btn-gold:hover { opacity:.9; transform:translateY(-1px); }

/* Loader */
.spinner-overlay { position:fixed;inset:0;background:rgba(255,255,255,.8);display:flex;align-items:center;justify-content:center;z-index:9999;display:none; }
.spinner-overlay.show { display:flex; }

/* Toast */
.toast-container { position:fixed;bottom:24px;right:24px;z-index:9999; }
</style>
</head>
<body>

<!-- SIDEBAR -->
<div class="sidebar">
  <div class="sidebar-brand">
    <h1>دسترخوان <span>Admin</span></h1>
    <small>Management Panel</small>
  </div>
  <nav class="sidebar-nav">
    <div class="nav-section">Main</div>
    <a href="#" class="nav-link active" onclick="showSection('dashboard',this)"><i class="bi bi-grid-1x2-fill"></i> Dashboard</a>
    <div class="nav-section">Operations</div>
    <a href="#" class="nav-link" onclick="showSection('orders',this)"><i class="bi bi-bag-fill"></i> Orders <span id="pendingBadge" class="badge bg-danger ms-auto" style="display:none"></span></a>
    <a href="#" class="nav-link" onclick="showSection('reservations',this)"><i class="bi bi-calendar2-check-fill"></i> Reservations</a>
    <div class="nav-section">Content</div>
    <a href="#" class="nav-link" onclick="showSection('menu',this)"><i class="bi bi-journal-bookmark-fill"></i> Menu Items</a>
    <a href="#" class="nav-link" onclick="showSection('messages',this)"><i class="bi bi-chat-dots-fill"></i> Messages <span id="msgBadge" class="badge bg-danger ms-auto" style="display:none"></span></a>
    <div class="nav-section">System</div>
    <a href="#" class="nav-link" onclick="showSection('employees',this)">
      <i class="bi bi-people-fill"></i> Employees
    </a>
    <a href="../index.php" target="_blank" class="nav-link"><i class="bi bi-house-fill"></i> View Site</a>
    <a href="logout.php" class="nav-link"><i class="bi bi-box-arrow-right"></i> Logout</a>
  </nav>
  <div class="sidebar-footer">
    <div class="admin-info">
      <div class="admin-avatar"><?= strtoupper(substr($_SESSION['admin_name'],0,1)) ?></div>
      <div><div style="color:#fff;font-weight:600"><?= htmlspecialchars($_SESSION['admin_name']) ?></div><div style="color:rgba(255,255,255,.4);font-size:.7rem"><?= ucfirst($_SESSION['admin_role']) ?></div></div>
    </div>
  </div>
</div>

<!-- MAIN CONTENT -->
<div class="main-content">
  <div class="topbar">
    <div class="topbar-title" id="pageTitle">Dashboard</div>
    <div class="d-flex align-items-center gap-3">
      <span class="text-muted small"><?= date('l, d M Y') ?></span>
      <a href="../index.php" target="_blank" class="btn btn-sm btn-outline-secondary rounded-pill px-3"><i class="bi bi-box-arrow-up-right me-1"></i>View Site</a>
    </div>
  </div>

  <div class="page-body">

    <!-- ===== DASHBOARD SECTION ===== -->
    <div id="sec-dashboard">
      <div class="row g-3 mb-4" id="statCards">
        <div class="col-md-3"><div class="stat-card"><div class="stat-icon" style="background:#fff8e1"><span style="font-size:1.5rem">🛍️</span></div><div class="stat-value" id="s-orders">–</div><div class="stat-label">Total Orders</div></div></div>
        <div class="col-md-3"><div class="stat-card"><div class="stat-icon" style="background:#e8f5e9"><span style="font-size:1.5rem">💰</span></div><div class="stat-value" id="s-revenue">–</div><div class="stat-label">Total Revenue</div></div></div>
        <div class="col-md-3"><div class="stat-card"><div class="stat-icon" style="background:#e3f2fd"><span style="font-size:1.5rem">👥</span></div><div class="stat-value" id="s-customers">–</div><div class="stat-label">Customers</div></div></div>
        <div class="col-md-3"><div class="stat-card"><div class="stat-icon" style="background:#fce4ec"><span style="font-size:1.5rem">📅</span></div><div class="stat-value" id="s-reservations">–</div><div class="stat-label">Reservations</div></div></div>
      </div>
      <div class="row g-3">
        <div class="col-md-8">
          <div class="panel-card">
            <div class="panel-header"><h5>Recent Orders</h5></div>
            <div class="panel-body p-0">
              <table class="data-table">
                <thead><tr><th>Order #</th><th>Customer</th><th>Amount</th><th>Status</th><th>Time</th></tr></thead>
                <tbody id="recentOrdersTbl"></tbody>
              </table>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="panel-card h-100">
            <div class="panel-header"><h5>Top Items</h5></div>
            <div class="panel-body" id="topItemsList"></div>
          </div>
        </div>
      </div>
    </div>

    <!-- ===== ORDERS SECTION ===== -->
    <div id="sec-orders" style="display:none">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="d-flex gap-2 flex-wrap">
          <button class="btn btn-sm btn-outline-secondary rounded-pill" onclick="loadOrders()">All</button>
          <button class="btn btn-sm rounded-pill" style="background:#fff3e0;color:#e65100" onclick="loadOrders('pending')">Pending</button>
          <button class="btn btn-sm rounded-pill" style="background:#f3e5f5;color:#7b1fa2" onclick="loadOrders('preparing')">Preparing</button>
          <button class="btn btn-sm rounded-pill" style="background:#e8f5e9;color:#2e7d32" onclick="loadOrders('delivered')">Delivered</button>
        </div>
      </div>
      <div class="panel-card">
        <div class="panel-body p-0">
          <table class="data-table" id="ordersTable">
            <thead><tr><th>Order #</th><th>Customer</th><th>Items</th><th>Total</th><th>Type</th><th>Status</th><th>Date</th><th>Actions</th></tr></thead>
            <tbody id="ordersTbl"></tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- ===== RESERVATIONS SECTION ===== -->
    <div id="sec-reservations" style="display:none">
      <div class="panel-card">
        <div class="panel-body p-0">
          <table class="data-table">
            <thead><tr><th>Name</th><th>Contact</th><th>Date & Time</th><th>Guests</th><th>Occasion</th><th>Status</th><th>Actions</th></tr></thead>
            <tbody id="resvTbl"></tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- ===== MENU SECTION ===== -->
    <div id="sec-menu" style="display:none">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="d-flex gap-2">
          <input type="text" class="form-control" style="max-width:220px" placeholder="Search items..." oninput="filterMenuItems(this.value)">
          <select class="form-select" style="max-width:180px" id="menuCatFilter" onchange="filterMenuItems()">
            <option value="">All Categories</option>
          </select>
        </div>
        <button class="btn-gold" onclick="openAddMenuModal()"><i class="bi bi-plus-lg me-1"></i>Add Item</button>
      </div>
      <div class="row g-3" id="menuGrid"></div>
    </div>

    <!-- ===== MESSAGES SECTION ===== -->
    <div id="sec-messages" style="display:none">
      <div class="panel-card">
        <div class="panel-body p-0">
          <table class="data-table">
            <thead><tr><th>Name</th><th>Email</th><th>Phone</th><th>Subject</th><th>Message</th><th>Date</th></tr></thead>
            <tbody id="msgTbl"></tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- ===== EMPLOYEES SECTION ===== -->
    <div id="sec-employees" style="display:none">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0">Employees</h5>
        <button class="btn-gold" onclick="openAddEmployeeModal()">
          <i class="bi bi-plus-lg me-1"></i>Add Employee
        </button>
      </div>

      <div class="panel-card">
        <div class="panel-body p-0">
          <table class="data-table">
            <thead>
              <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Salary</th>
                <th>Status</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody id="empTbl"></tbody>
          </table>
        </div>
      </div>
    </div>

  </div>
</div>

<!-- ORDER DETAIL MODAL -->
<div class="modal fade" id="orderModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Order Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body" id="orderModalBody"></div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn-gold" id="saveStatusBtn">Update Status</button>
      </div>
    </div>
  </div>
</div>

<!-- ADD/EDIT MENU MODAL -->
<div class="modal fade" id="menuModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="menuModalTitle">Add Menu Item</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="menuItemId">
        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label fw-semibold">Item Name *</label>
            <input type="text" class="form-control" id="menuName" placeholder="e.g. Chicken Karahi">
          </div>
          <div class="col-md-3">
            <label class="form-label fw-semibold">Price (PKR) *</label>
            <input type="number" class="form-control" id="menuPrice" placeholder="0.00" min="1">
          </div>
          <div class="col-md-3">
            <label class="form-label fw-semibold">Spice Level</label>
            <select class="form-select" id="menuSpice">
              <option>Mild</option><option selected>Medium</option><option>Hot</option><option>Extra Hot</option>
            </select>
          </div>
          <div class="col-md-6">
            <label class="form-label fw-semibold">Category *</label>
            <select class="form-select" id="menuCategory"></select>
          </div>
          <div class="col-md-6">
            <label class="form-label fw-semibold">Image URL</label>
            <input type="text" class="form-control" id="menuImage" placeholder="https://...">
          </div>
          <div class="col-12">
            <label class="form-label fw-semibold">Description</label>
            <textarea class="form-control" id="menuDesc" rows="3" placeholder="Describe the dish..."></textarea>
          </div>
          <div class="col-md-6">
            <div class="form-check form-switch mt-2">
              <input class="form-check-input" type="checkbox" id="menuFeatured">
              <label class="form-check-label fw-semibold">Featured Item</label>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-check form-switch mt-2">
              <input class="form-check-input" type="checkbox" id="menuAvailable" checked>
              <label class="form-check-label fw-semibold">Available</label>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn-gold" onclick="saveMenuItem()">Save Item</button>
      </div>
    </div>
  </div>
</div>

<!-- ADD EMPLOYEE MODAL -->
<div class="modal fade" id="employeeModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add New Employee</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label class="form-label fw-semibold">Full Name *</label>
          <input type="text" class="form-control" id="empName" placeholder="Enter full name">
        </div>
        <div class="mb-3">
          <label class="form-label fw-semibold">Email *</label>
          <input type="email" class="form-control" id="empEmail" placeholder="Enter email">
        </div>
        <div class="mb-3">
          <label class="form-label fw-semibold">Phone</label>
          <input type="text" class="form-control" id="empPhone" placeholder="Enter phone number">
        </div>
        <div class="mb-3">
          <label class="form-label fw-semibold">Designation *</label>
          <select class="form-select" id="empDesignation">
            <option value="">Select Designation</option>
            <option>Manager</option>
            <option>Chef</option>
            <option>Waiter</option>
            <option>Cashier</option>
            <option>Cleaner</option>
          </select>
        </div>
        <div class="mb-3">
          <label class="form-label fw-semibold">Salary *</label>
          <input type="number" class="form-control" id="empSalary" placeholder="Enter salary">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn-gold" onclick="addEmployee()">Add Employee</button>
      </div>
    </div>
  </div>
</div>

<!-- EDIT EMPLOYEE MODAL -->
<div class="modal fade" id="editEmployeeModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Employee</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="editEmpId">
        <div class="mb-3">
          <label class="form-label fw-semibold">Full Name *</label>
          <input type="text" class="form-control" id="editEmpName">
        </div>
        <div class="mb-3">
          <label class="form-label fw-semibold">Email *</label>
          <input type="email" class="form-control" id="editEmpEmail">
        </div>
        <div class="mb-3">
          <label class="form-label fw-semibold">Phone</label>
          <input type="text" class="form-control" id="editEmpPhone">
        </div>
        <div class="mb-3">
          <label class="form-label fw-semibold">Designation *</label>
          <select class="form-select" id="editEmpDesignation">
            <option value="">Select Designation</option>
            <option>Manager</option>
            <option>Chef</option>
            <option>Waiter</option>
            <option>Cashier</option>
            <option>Cleaner</option>
          </select>
        </div>
        <div class="mb-3">
          <label class="form-label fw-semibold">Salary *</label>
          <input type="number" class="form-control" id="editEmpSalary">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn-gold" onclick="updateEmployee()">Save Changes</button>
      </div>
    </div>
  </div>
</div>

<!-- Toast -->
<div class="toast-container">
  <div id="adminToast" class="toast align-items-center border-0" role="alert">
    <div class="d-flex">
      <div class="toast-body fw-semibold" id="toastMsg"></div>
      <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast"></button>
    </div>
  </div>
</div>

<div class="spinner-overlay" id="loadingOverlay"><div class="spinner-border text-warning" style="width:3rem;height:3rem"></div></div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
const BASE = '../api';
let allMenuItems = [], allCategories = [], currentOrderId = null;

function showToast(msg, success=true) {
  const el = document.getElementById('adminToast');
  el.className = `toast align-items-center border-0 text-white bg-${success?'success':'danger'}`;
  document.getElementById('toastMsg').textContent = msg;
  bootstrap.Toast.getOrCreateInstance(el, {delay:3000}).show();
}

function showLoader(v) { document.getElementById('loadingOverlay').classList.toggle('show',v); }

function showSection(name, link) {
  ['dashboard','orders','reservations','menu','messages','employees'].forEach(s => {
    document.getElementById('sec-'+s).style.display = s===name?'':'none';
  });
  document.querySelectorAll('.nav-link').forEach(l=>l.classList.remove('active'));
  if(link) link.classList.add('active');
  document.getElementById('pageTitle').textContent =
    {dashboard:'Dashboard',orders:'Orders',reservations:'Reservations',menu:'Menu Management',messages:'Contact Messages',employees:'Employees'}[name];

  if(name==='orders') loadOrders();
  if(name==='reservations') loadReservations();
  if(name==='menu') loadMenu();
  if(name==='messages') loadMessages();
  if(name==='employees') loadEmployees();
  if(name==='dashboard') loadDashboard();
}

// ===== DASHBOARD =====
async function loadDashboard() {
  try {
    const r = await fetch(`${BASE}/stats.php`);
    const d = await r.json();
    if(!d.success) return;
    const s = d.data;
    document.getElementById('s-orders').textContent = s.total_orders.toLocaleString();
    document.getElementById('s-revenue').textContent = 'Rs '+s.total_revenue.toLocaleString('en-PK',{minimumFractionDigits:0});
    document.getElementById('s-customers').textContent = s.total_customers.toLocaleString();
    document.getElementById('s-reservations').textContent = s.total_reservations.toLocaleString();

    const pending = s.status_breakdown.find(x=>x.status==='pending');
    if(pending && pending.count>0) { const b=document.getElementById('pendingBadge'); b.textContent=pending.count; b.style.display=''; }
    if(s.unread_messages>0) { const b=document.getElementById('msgBadge'); b.textContent=s.unread_messages; b.style.display=''; }

    const tbl = document.getElementById('recentOrdersTbl');
    tbl.innerHTML = s.recent_orders.map(o=>`
      <tr>
        <td><strong>#${String(o.id).padStart(4,'0')}</strong></td>
        <td>${o.customer_name}</td>
        <td style="color:var(--gold);font-weight:700">Rs ${parseFloat(o.total_amount).toLocaleString()}</td>
        <td><span class="badge-status s-${o.status}">${o.status}</span></td>
        <td class="text-muted">${new Date(o.created_at).toLocaleTimeString('en-PK',{hour:'2-digit',minute:'2-digit'})}</td>
      </tr>`).join('');

    const list = document.getElementById('topItemsList');
    const entries = Object.entries(s.top_items);
    const max = entries[0]?.[1]||1;
    list.innerHTML = entries.map(([name,count])=>`
      <div class="mb-3">
        <div class="d-flex justify-content-between mb-1"><span class="fw-semibold" style="font-size:.85rem">${name}</span><span class="text-muted" style="font-size:.8rem">${count} orders</span></div>
        <div class="progress" style="height:6px;border-radius:4px"><div class="progress-bar" style="width:${count/max*100}%;background:var(--gold)"></div></div>
      </div>`).join('');
  } catch(e) { console.error(e); }
}

// ===== ORDERS =====
async function loadOrders(status='') {
  showLoader(true);
  try {
    const r = await fetch(`${BASE}/orders.php${status?'?status='+status:''}`);
    const d = await r.json();
    showLoader(false);
    if(!d.success) return;
    const tbl = document.getElementById('ordersTbl');
    tbl.innerHTML = d.data.map(o=>`
      <tr>
        <td><strong>#${String(o.id).padStart(4,'0')}</strong></td>
        <td>
          <div class="fw-semibold">${o.customer_name}</div>
          <div class="text-muted" style="font-size:.75rem">${o.customer_phone}</div>
        </td>
        <td>${Array.isArray(o.items)?o.items.reduce((s,i)=>s+i.qty,0)+' items':'–'}</td>
        <td style="color:var(--gold);font-weight:700">Rs ${parseFloat(o.total_amount).toLocaleString()}</td>
        <td><span class="badge bg-secondary">${o.order_type}</span></td>
        <td><span class="badge-status s-${o.status}">${o.status}</span></td>
        <td class="text-muted" style="font-size:.78rem">${new Date(o.created_at).toLocaleDateString('en-PK')}</td>
        <td><button class="btn btn-sm btn-outline-secondary rounded-pill" onclick='openOrderModal(${JSON.stringify(o)})'>View</button></td>
      </tr>`).join('') || '<tr><td colspan="8" class="text-center py-4 text-muted">No orders found</td></tr>';
  } catch(e) { showLoader(false); }
}

function openOrderModal(order) {
  currentOrderId = order.id;
  const items = Array.isArray(order.items) ? order.items : [];
  document.getElementById('orderModalBody').innerHTML = `
    <div class="row g-3 mb-3">
      <div class="col-md-6">
        <strong>Customer:</strong> ${order.customer_name}<br>
        <strong>Phone:</strong> ${order.customer_phone}<br>
        <strong>Email:</strong> ${order.customer_email||'–'}<br>
        <strong>Type:</strong> ${order.order_type}<br>
        ${order.delivery_address?'<strong>Address:</strong> '+order.delivery_address+'<br>':''}
        ${order.notes?'<strong>Notes:</strong> '+order.notes:''}
      </div>
      <div class="col-md-6">
        <strong>Order #:</strong> ${String(order.id).padStart(4,'0')}<br>
        <strong>Date:</strong> ${new Date(order.created_at).toLocaleString('en-PK')}<br>
        <strong>Payment:</strong> ${order.payment_method}<br>
        <strong>Subtotal:</strong> Rs ${parseFloat(order.subtotal).toLocaleString()}<br>
        <strong>Tax (9%):</strong> Rs ${parseFloat(order.tax).toLocaleString()}<br>
        <strong style="color:var(--gold)">Total:</strong> <strong style="color:var(--gold)">Rs ${parseFloat(order.total_amount).toLocaleString()}</strong>
      </div>
    </div>
    <hr>
    <h6 class="fw-bold mb-3">Ordered Items</h6>
    <div class="row g-2 mb-3">
      ${items.map(i=>`
        <div class="col-12">
          <div class="d-flex align-items-center gap-3 p-2 border rounded-3">
            <img src="${i.image}" style="width:56px;height:56px;object-fit:cover;border-radius:8px">
            <div class="flex-grow-1">
              <div class="fw-semibold">${i.name}</div>
              <div class="text-muted" style="font-size:.8rem">Qty: ${i.qty} × Rs ${parseFloat(i.price).toLocaleString()}</div>
            </div>
            <div class="fw-bold" style="color:var(--gold)">Rs ${(i.qty*i.price).toLocaleString()}</div>
          </div>
        </div>`).join('')}
    </div>
    <div class="d-flex align-items-center gap-2">
      <strong>Update Status:</strong>
      <select class="form-select" style="max-width:200px" id="statusSelect">
        ${['pending','confirmed','preparing','ready','delivered','cancelled'].map(s=>`<option value="${s}" ${s===order.status?'selected':''}>${s.charAt(0).toUpperCase()+s.slice(1)}</option>`).join('')}
      </select>
    </div>`;
  new bootstrap.Modal(document.getElementById('orderModal')).show();
}

document.getElementById('saveStatusBtn').onclick = async () => {
  const status = document.getElementById('statusSelect').value;
  const r = await fetch(`${BASE}/orders.php`, { method:'PUT', headers:{'Content-Type':'application/json'}, body:JSON.stringify({id:currentOrderId, status}) });
  const d = await r.json();
  showToast(d.message, d.success);
  if(d.success) { bootstrap.Modal.getInstance(document.getElementById('orderModal')).hide(); loadOrders(); }
};

// ===== RESERVATIONS =====
async function loadReservations() {
  showLoader(true);
  try {
    const r = await fetch(`${BASE}/reservations.php`);
    const d = await r.json();
    showLoader(false);
    const tbl = document.getElementById('resvTbl');
    tbl.innerHTML = d.data.map(res=>`
      <tr>
        <td><div class="fw-semibold">${res.name}</div></td>
        <td>${res.phone}<br><span class="text-muted" style="font-size:.78rem">${res.email}</span></td>
        <td>${res.date} at ${res.time.slice(0,5)}</td>
        <td>${res.guests} guests</td>
        <td>${res.occasion||'–'}</td>
        <td><span class="badge-status s-${res.status}">${res.status}</span></td>
        <td>
          <select class="form-select form-select-sm" style="width:auto" onchange="updateResv(${res.id},this.value)">
            ${['pending','confirmed','cancelled','completed'].map(s=>`<option ${s===res.status?'selected':''}>${s}</option>`).join('')}
          </select>
        </td>
      </tr>`).join('') || '<tr><td colspan="7" class="text-center py-4 text-muted">No reservations</td></tr>';
  } catch(e) { showLoader(false); }
}

async function updateResv(id, status) {
  const r = await fetch(`${BASE}/reservations.php`, { method:'PUT', headers:{'Content-Type':'application/json'}, body:JSON.stringify({id, status}) });
  const d = await r.json();
  showToast(d.message, d.success);
}

// ===== MENU =====
async function loadMenu() {
  showLoader(true);
  try {
    const [mr, cr] = await Promise.all([fetch(`${BASE}/menu.php`), fetch(`${BASE}/menu.php?action=categories`)]);
    const [md, cd] = await Promise.all([mr.json(), cr.json()]);
    showLoader(false);
    allMenuItems = md.data || [];
    allCategories = cd.data || [];
    const sel = document.getElementById('menuCatFilter');
    sel.innerHTML = '<option value="">All Categories</option>' + allCategories.map(c=>`<option value="${c.id}">${c.name}</option>`).join('');
    const mSel = document.getElementById('menuCategory');
    mSel.innerHTML = allCategories.map(c=>`<option value="${c.id}">${c.name}</option>`).join('');
    renderMenuGrid(allMenuItems);
  } catch(e) { showLoader(false); }
}

function renderMenuGrid(items) {
  const grid = document.getElementById('menuGrid');
  grid.innerHTML = items.map(item=>`
    <div class="col-md-3 col-sm-6">
      <div class="menu-item-card">
        <img src="${item.image_url}" alt="${item.name}" onerror="this.src='https://images.unsplash.com/photo-1565557623262-b51c2513a641?w=600&q=80'">
        <div class="menu-item-info">
          <h6>${item.name}</h6>
          <div class="d-flex justify-content-between align-items-center">
            <div class="price">Rs ${parseFloat(item.price).toLocaleString()}</div>
            <span class="badge bg-secondary" style="font-size:.65rem">${item.spice_level}</span>
          </div>
          <div class="text-muted" style="font-size:.72rem;margin-top:4px">${item.category_name}</div>
          <div class="d-flex gap-1 mt-2">
            <button class="btn btn-sm btn-outline-primary flex-grow-1" onclick='editMenuItem(${JSON.stringify(item)})'><i class="bi bi-pencil"></i></button>
            <button class="btn btn-sm btn-outline-danger flex-grow-1" onclick="deleteMenuItem(${item.id},'${item.name}')"><i class="bi bi-trash"></i></button>
            <button class="btn btn-sm ${item.is_available?'btn-success':'btn-outline-secondary'} flex-grow-1" onclick="toggleAvailable(${item.id},${item.is_available})"><i class="bi bi-${item.is_available?'check':'x'}-circle"></i></button>
          </div>
        </div>
      </div>
    </div>`).join('') || '<div class="col-12 text-center py-5 text-muted">No items found</div>';
}

function filterMenuItems(search='') {
  const cat = document.getElementById('menuCatFilter').value;
  const q = (search || document.querySelector('input[placeholder="Search items..."]')?.value||'').toLowerCase();
  let items = allMenuItems;
  if(cat) items = items.filter(i=>i.category_id==cat);
  if(q) items = items.filter(i=>i.name.toLowerCase().includes(q)||i.description?.toLowerCase().includes(q));
  renderMenuGrid(items);
}

function openAddMenuModal() {
  document.getElementById('menuItemId').value = '';
  document.getElementById('menuModalTitle').textContent = 'Add Menu Item';
  ['menuName','menuDesc','menuImage'].forEach(id=>document.getElementById(id).value='');
  document.getElementById('menuPrice').value = '';
  document.getElementById('menuSpice').value = 'Medium';
  document.getElementById('menuFeatured').checked = false;
  document.getElementById('menuAvailable').checked = true;
  new bootstrap.Modal(document.getElementById('menuModal')).show();
}

function editMenuItem(item) {
  document.getElementById('menuItemId').value = item.id;
  document.getElementById('menuModalTitle').textContent = 'Edit Menu Item';
  document.getElementById('menuName').value = item.name;
  document.getElementById('menuPrice').value = item.price;
  document.getElementById('menuCategory').value = item.category_id;
  document.getElementById('menuSpice').value = item.spice_level;
  document.getElementById('menuDesc').value = item.description||'';
  document.getElementById('menuImage').value = item.image_url||'';
  document.getElementById('menuFeatured').checked = item.is_featured==1;
  document.getElementById('menuAvailable').checked = item.is_available==1;
  new bootstrap.Modal(document.getElementById('menuModal')).show();
}

async function saveMenuItem() {
  const id = document.getElementById('menuItemId').value;
  const data = {
    name: document.getElementById('menuName').value.trim(),
    price: document.getElementById('menuPrice').value,
    category_id: document.getElementById('menuCategory').value,
    spice_level: document.getElementById('menuSpice').value,
    description: document.getElementById('menuDesc').value.trim(),
    image_url: document.getElementById('menuImage').value.trim()||'https://images.unsplash.com/photo-1565557623262-b51c2513a641?w=600&q=80',
    is_featured: document.getElementById('menuFeatured').checked?1:0,
    is_available: document.getElementById('menuAvailable').checked?1:0
  };
  if(!data.name||!data.price||!data.category_id) { showToast('Please fill required fields',false); return; }
  if(id) data.id = id;
  const method = id ? 'PUT' : 'POST';
  const r = await fetch(`${BASE}/menu.php`, { method, headers:{'Content-Type':'application/json'}, body:JSON.stringify(data) });
  const d = await r.json();
  showToast(d.message, d.success);
  if(d.success) { bootstrap.Modal.getInstance(document.getElementById('menuModal')).hide(); loadMenu(); }
}

async function deleteMenuItem(id, name) {
  if(!confirm(`Delete "${name}"? This cannot be undone.`)) return;
  const r = await fetch(`${BASE}/menu.php?id=${id}`, {method:'DELETE'});
  const d = await r.json();
  showToast(d.message, d.success);
  if(d.success) loadMenu();
}

async function toggleAvailable(id, current) {
  const r = await fetch(`${BASE}/menu.php`, { method:'PUT', headers:{'Content-Type':'application/json'}, body:JSON.stringify({id, is_available: current?0:1}) });
  const d = await r.json();
  showToast(d.message, d.success);
  if(d.success) loadMenu();
}

// ===== MESSAGES =====
async function loadMessages() {
  showLoader(true);
  try {
    const r = await fetch(`${BASE}/contact.php`);
    const d = await r.json();
    showLoader(false);
    const tbl = document.getElementById('msgTbl');
    tbl.innerHTML = d.data.map(m=>`
      <tr>
        <td class="fw-semibold">${m.name}</td>
        <td>${m.email}</td>
        <td>${m.phone||'–'}</td>
        <td>${m.subject||'–'}</td>
        <td>${m.message.substring(0,80)}${m.message.length>80?'…':''}</td>
        <td class="text-muted" style="font-size:.78rem">${new Date(m.created_at).toLocaleDateString('en-PK')}</td>
      </tr>`).join('') || '<tr><td colspan="6" class="text-center py-4 text-muted">No messages</td></tr>';
  } catch(e) { showLoader(false); }
}

// ===== EMPLOYEES (FULL) =====
async function loadEmployees() {
  showLoader(true);
  try {
    const r = await fetch(`${BASE}/employees.php`);
    const d = await r.json();
    showLoader(false);

    const tbl = document.getElementById('empTbl');
    
    if (!d.success || !d.data.length) {
      tbl.innerHTML = '<tr><td colspan="7" class="text-center py-4 text-muted">No employees found</td></tr>';
      return;
    }

    tbl.innerHTML = d.data.map(e => `
      <tr>
        <td>#${e.id}</td>
        <td>
          <div class="fw-semibold">${e.name}</div>
          <small class="text-muted">${e.phone || 'N/A'}</small>
        </td>
        <td>${e.email}</td>
        <td><span class="badge bg-info">${e.role}</span></td>
        <td>Rs ${parseFloat(e.salary || 0).toLocaleString()}</td>
        <td>
          <span class="badge ${e.status === 'Active' ? 'bg-success' : 'bg-danger'}">
            ${e.status}
          </span>
        </td>
        <td>
          <button class="btn btn-sm btn-outline-primary" onclick="openEditModal(${e.id}, '${e.name.replace(/'/g, "\\'")}', '${e.email.replace(/'/g, "\\'")}', '${(e.phone||'').replace(/'/g, "\\'")}', '${e.role.replace(/'/g, "\\'")}', ${e.salary})">
            <i class="bi bi-pencil"></i>
          </button>
          ${e.status === 'Active' ? 
            `<button class="btn btn-sm btn-outline-danger ms-1" onclick="fireEmployee(${e.id})">
              <i class="bi bi-person-x"></i>
            </button>` : 
            `<button class="btn btn-sm btn-outline-success ms-1" onclick="rehireEmployee(${e.id})">
              <i class="bi bi-person-check"></i>
            </button>`
          }
        </td>
      </tr>
    `).join('');

  } catch (e) {
    showLoader(false);
    showToast('Failed to load employees', false);
  }
}

function openAddEmployeeModal() {
  document.getElementById('empName').value = '';
  document.getElementById('empEmail').value = '';
  document.getElementById('empPhone').value = '';
  document.getElementById('empDesignation').value = '';
  document.getElementById('empSalary').value = '';
  new bootstrap.Modal(document.getElementById('employeeModal')).show();
}

async function addEmployee() {
  const name = document.getElementById('empName').value.trim();
  const email = document.getElementById('empEmail').value.trim();
  const phone = document.getElementById('empPhone').value.trim();
  const designation = document.getElementById('empDesignation').value;
  const salary = document.getElementById('empSalary').value;

  if (!name || !email || !designation || !salary) {
    showToast('Please fill all required fields', false);
    return;
  }

  const data = {
    full_name: name,
    email: email,
    phone: phone,
    designation: designation,
    salary: salary
  };

  try {
    const r = await fetch(`${BASE}/employees.php`, {
      method: 'POST',
      headers: {'Content-Type': 'application/json'},
      body: JSON.stringify(data)
    });
    const d = await r.json();
    showToast(d.message, d.success);
    if (d.success) {
      bootstrap.Modal.getInstance(document.getElementById('employeeModal')).hide();
      loadEmployees();
    }
  } catch (e) {
    showToast('Failed to add employee', false);
  }
}

function openEditModal(id, name, email, phone, designation, salary) {
  document.getElementById('editEmpId').value = id;
  document.getElementById('editEmpName').value = name;
  document.getElementById('editEmpEmail').value = email;
  document.getElementById('editEmpPhone').value = phone || '';
  document.getElementById('editEmpDesignation').value = designation;
  document.getElementById('editEmpSalary').value = salary;
  new bootstrap.Modal(document.getElementById('editEmployeeModal')).show();
}

async function updateEmployee() {
  const id = document.getElementById('editEmpId').value;
  const name = document.getElementById('editEmpName').value.trim();
  const email = document.getElementById('editEmpEmail').value.trim();
  const phone = document.getElementById('editEmpPhone').value.trim();
  const designation = document.getElementById('editEmpDesignation').value;
  const salary = document.getElementById('editEmpSalary').value;

  if (!name || !email || !designation || !salary) {
    showToast('Please fill all required fields', false);
    return;
  }

  const data = {
    id: id,
    full_name: name,
    email: email,
    phone: phone,
    designation: designation,
    salary: salary
  };

  try {
    const r = await fetch(`${BASE}/employees.php`, {
      method: 'PUT',
      headers: {'Content-Type': 'application/json'},
      body: JSON.stringify(data)
    });
    const d = await r.json();
    showToast(d.message, d.success);
    if (d.success) {
      bootstrap.Modal.getInstance(document.getElementById('editEmployeeModal')).hide();
      loadEmployees();
    }
  } catch (e) {
    showToast('Failed to update employee', false);
  }
}

async function fireEmployee(id) {
  if (!confirm('Are you sure you want to fire this employee?')) return;
  
  try {
    const r = await fetch(`${BASE}/employees.php`, {
      method: 'PUT',
      headers: {'Content-Type': 'application/json'},
      body: JSON.stringify({id, status: 'Fired'})
    });
    const d = await r.json();
    showToast(d.message, d.success);
    if (d.success) loadEmployees();
  } catch (e) {
    showToast('Failed to update employee', false);
  }
}

async function rehireEmployee(id) {
  if (!confirm('Rehire this employee?')) return;
  
  try {
    const r = await fetch(`${BASE}/employees.php`, {
      method: 'PUT',
      headers: {'Content-Type': 'application/json'},
      body: JSON.stringify({id, status: 'Active'})
    });
    const d = await r.json();
    showToast(d.message, d.success);
    if (d.success) loadEmployees();
  } catch (e) {
    showToast('Failed to update employee', false);
  }
}

// Init
loadDashboard();
</script>
</body>
</html>