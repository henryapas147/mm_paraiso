<?php
require_once 'config/config.php';

// Fetch company information
$companyInfo = null;
try {
    $stmt = $pdo->prepare("SELECT * FROM company_information WHERE is_active = 1 LIMIT 1");
    $stmt->execute();
    $companyInfo = $stmt->fetch();
} catch (PDOException $e) {
    error_log("Failed to fetch company info: " . $e->getMessage());
}

// Use database value or fallback to default
$companyName = $companyInfo['company_name'] ?? 'M&M Paraiso';
?>
<header class="header-area header-sticky">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <nav class="main-nav">
                    <!-- ***** Logo Start ***** -->
                    <a href="index.php" class="logo">
                        <h1><?php echo htmlspecialchars($companyName); ?></h1>
                    </a>
                    <!-- ***** Logo End ***** -->
                    <!-- ***** Menu Start ***** -->
                    <ul class="nav">
                        <li><a href="index.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>">Home</a></li>
                        <li><a href="properties.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'properties.php' ? 'active' : ''; ?>">Properties</a></li>
                        <li><a href="rooms.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'rooms.php' ? 'active' : ''; ?>">Rooms</a></li>
                        <li><a href="property-details.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'property-details.php' ? 'active' : ''; ?>" hidden>Property Details</a></li>
                        <li><a href="contact.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'contact.php' ? 'active' : ''; ?>">Contact Us</a></li>
                        <li><a href="schedule-visit.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'schedule-visit.php' ? 'active' : ''; ?>"><i class="fa fa-calendar"></i> Schedule a visit</a></li>
                    </ul>
                    <div class="auth-nav">
                        <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true): ?>
                            <div class="user-dropdown">
                                <button class="user-dropdown-toggle" type="button">
                                    <i class="fa fa-user-circle"></i>
                                    <span class="user-email"><?php echo htmlspecialchars($_SESSION['email'] ?? 'User'); ?></span>
                                    <i class="fa fa-chevron-down chevron-icon"></i>
                                </button>
                                <div class="user-dropdown-menu">
                                    <a href="../build/" class="dropdown-item"><i class="fa fa-tachometer"></i> Dashboard</a>
                                    <a href="../build/profile" class="dropdown-item"><i class="fa fa-user"></i> Profile</a>
                                    <div class="dropdown-divider"></div>
                                    <a href="../build/logout" class="dropdown-item sign-out-item"><i class="fa fa-sign-out"></i> Sign Out</a>
                                </div>
                            </div>
                        <?php else: ?>
                            <a href="../build/sign-in" class="auth-button sign-in">Sign In</a>
                            <a href="../build/sign-up" class="auth-button sign-up">Sign Up</a>
                        <?php endif; ?>
                    </div>
                    <a class='menu-trigger'>
                        <span>Menu</span>
                    </a>
                    <!-- ***** Menu End ***** -->
                </nav>
            </div>
        </div>
    </div>
</header>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const toggle = document.querySelector('.user-dropdown-toggle');
    const dropdown = document.querySelector('.user-dropdown');
    if (toggle && dropdown) {
        toggle.addEventListener('click', function (e) {
            e.stopPropagation();
            dropdown.classList.toggle('open');
        });
        document.addEventListener('click', function () {
            dropdown.classList.remove('open');
        });
    }
});
</script>