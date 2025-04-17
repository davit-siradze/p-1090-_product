<?php
require_once 'includes/config.php';
redirectIfNotLoggedIn();
redirectIfNotAdmin();

$message = '';
$orders = [];

// შეკვეთის სტატუსის განახლება
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_status'])) {
    $orderId = $_POST['order_id'];
    $newStatus = $_POST['status'];
    
    $stmt = $pdo->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $stmt->execute([$newStatus, $orderId]);
    $message = 'შეკვეთის სტატუსი განახლებულია!';
}

// შეკვეთების სიის მიღება
$stmt = $pdo->query("SELECT o.*, u.username, p.name as product_name 
                     FROM orders o 
                     JOIN users u ON o.user_id = u.id 
                     JOIN products p ON o.product_id = p.id 
                     ORDER BY o.created_at DESC");
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

include 'includes/header.php';
?>

<div class="content">
    <h2>შეკვეთების მართვა</h2>
    
    <?php if (!empty($message)): ?>
        <div class="message"><?php echo $message; ?></div>
    <?php endif; ?>
    
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>მომხმარებელი</th>
                <th>პროდუქტი</th>
                <th>რაოდენობა</th>
                <th>ჯამური ფასი</th>
                <th>სტატუსი</th>
                <th>თარიღი</th>
                <th>მოქმედებები</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($orders as $order): ?>
            <tr>
                <td><?php echo $order['id']; ?></td>
                <td><?php echo htmlspecialchars($order['username']); ?></td>
                <td><?php echo htmlspecialchars($order['product_name']); ?></td>
                <td><?php echo $order['quantity']; ?></td>
                <td><?php echo $order['total_price']; ?>₾</td>
                <td>
                    <form action="orders.php" method="post" class="status-form">
                        <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                        <select name="status">
                            <option value="pending" <?php echo $order['status'] == 'pending' ? 'selected' : ''; ?>>გადასახდელი</option>
                            <option value="paid" <?php echo $order['status'] == 'paid' ? 'selected' : ''; ?>>გადახდილი</option>
                            <option value="shipped" <?php echo $order['status'] == 'shipped' ? 'selected' : ''; ?>>გაგზავნილი</option>
                            <option value="delivered" <?php echo $order['status'] == 'delivered' ? 'selected' : ''; ?>>მიწოდებული</option>
                            <option value="cancelled" <?php echo $order['status'] == 'cancelled' ? 'selected' : ''; ?>>გაუქმებული</option>
                        </select>
                        <button type="submit" name="update_status">განახლება</button>
                    </form>
                </td>
                <td><?php echo $order['created_at']; ?></td>
                <td>
                    <a href="#" onclick="return confirm('დარწმუნებული ხართ?')">წაშლა</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include 'includes/footer.php'; ?>