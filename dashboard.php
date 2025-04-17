<?php
require_once 'includes/config.php';
redirectIfNotLoggedIn();

include 'includes/header.php';
?>

<div class="dashboard">
    <h2>მოგესალმებით, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>
    
    <div class="stats">
        <div class="stat-card">
            <h3>მომხმარებლები</h3>
            <?php
            $stmt = $pdo->query("SELECT COUNT(*) FROM users");
            $userCount = $stmt->fetchColumn();
            ?>
            <p><?php echo $userCount; ?></p>
        </div>
        
        <div class="stat-card">
            <h3>პროდუქტები</h3>
            <?php
            $stmt = $pdo->query("SELECT COUNT(*) FROM products");
            $productCount = $stmt->fetchColumn();
            ?>
            <p><?php echo $productCount; ?></p>
        </div>
        
        <div class="stat-card">
            <h3>შეკვეთები</h3>
            <?php
            $stmt = $pdo->query("SELECT COUNT(*) FROM orders");
            $orderCount = $stmt->fetchColumn();
            ?>
            <p><?php echo $orderCount; ?></p>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>