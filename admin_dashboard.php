<?php
session_start();
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header('Location: admin.html'); exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Admin Dashboard | Blood Donor Finder</title>
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    .cards { max-width:1100px; margin:30px auto; display:grid; grid-template-columns: repeat(auto-fit, minmax(220px,1fr)); gap:18px; padding:0 18px; }
    .card { background:#fff; border-radius:10px; padding:22px; box-shadow:0 6px 18px rgba(0,0,0,0.06); text-align:center; border-top:5px solid #8B0000; }
    .card h3 { color:#8B0000; margin:12px 0; }
    .card p { color:#444; margin:10px 0 18px; font-size:14px; }
    .card a button { background:#8B0000; color:#fff; border:0; padding:10px 14px; border-radius:8px; cursor:pointer; font-weight:600; }
    .card a button:hover { background:#5a0000; }
    .top-actions { text-align:center; margin-top:8px; }
  </style>
</head>
<body>
<nav class="navbar">
  <div class="logo"><i class="fa-solid fa-shield-halved"></i> Admin Panel</div>
  <div class="nav-links">
    <a href="admin_dashboard.php">Dashboard</a>
    <a href="announcements.php">Announcements</a>
    <a href="approve_requests.php">Approve Requests</a>
    <a href="view_donor_details.php">Donor Details</a>
    <a href="statistics.php">Statistics</a>
    <a href="recent_requests.php">Recent Requests</a>
    <a href="index.html">Logout</a>
  </div>
</nav>
<section class="form-section">
  <h2><i class="fa-solid fa-gauge-high"></i> Admin Dashboard</h2>
  <p style="text-align:center; font-size:16px;">Welcome, <?php echo htmlspecialchars($_SESSION['admin_user'] ?? 'Admin'); ?> — quick actions below.</p>

  <div class="cards">
    <div class="card">
      <i class="fa-solid fa-bullhorn" style="font-size:28px;color:#8B0000"></i>
      <h3>Post Announcement</h3>
      <p>Create a campus-wide announcement that displays to students.</p>
      <div class="top-actions"><a href="announcements.html"><button>Create</button></a></div>
    </div>

    <div class="card">
      <i class="fa-solid fa-user-check" style="font-size:28px;color:#8B0000"></i>
      <h3>Approve Registrations</h3>
      <p>View pending donors and approve or reject registrations.</p>
      <div class="top-actions"><a href="approve_requests.php"><button>Manage</button></a></div>
    </div>

    <div class="card">
      <i class="fa-solid fa-list" style="font-size:28px;color:#8B0000"></i>
      <h3>Recent Requests</h3>
      <p>View recent blood requests submitted by users.</p>
      <div class="top-actions"><a href="recent_requests.php"><button>View</button></a></div>
    </div>

    <div class="card">
      <i class="fa-solid fa-chart-simple" style="font-size:28px;color:#8B0000"></i>
      <h3>Statistics</h3>
      <p>Campus-level donor statistics and blood-group breakdown.</p>
      <div class="top-actions"><a href="statistics.php"><button>Open</button></a></div>
    </div>

    <div class="card">
      <i class="fa-solid fa-user-pen" style="font-size:28px;color:#8B0000"></i>
      <h3>Donor Details</h3>
      <p>Search and view detailed donor contact information (verified only).</p>
      <div class="top-actions"><a href="view_donor_details.php"><button>Search</button></a></div>
    </div>

    <div class="card">
      <i class="fa-solid fa-user-slash" style="font-size:28px;color:#8B0000"></i>
      <h3>Manage Accounts</h3>
      <p>Remove fake or inactive accounts from the donors list.</p>
      <div class="top-actions"><a href="remove_account.php"><button>Manage</button></a></div>
    </div>
  </div>

</section>

<footer>
  <p style="text-align:center; margin:40px 0 20px;">&copy; 2025 Campus Blood Donor Finder</p>
</footer>
</body>
</html>
