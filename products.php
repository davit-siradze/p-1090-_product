<?php
require_once 'includes/config.php';
redirectIfNotLoggedIn();
redirectIfNotAdmin();

$message = '';
$products = [];

// პროდუქტის დამატება
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_product'])) {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $price = trim($_POST['price']);
    $quantity = trim($_POST['quantity']);
    
    if (!empty($name) && !empty($price) && !empty($quantity)) {
        $stmt = $pdo->prepare("INSERT INTO products (name, description, price, quantity) VALUES (?, ?, ?, ?)");
        $stmt->execute([$name, $description, $price, $quantity]);
        $message = 'პროდუქტი წარმატებით დაემატა!';
    } else {
        $message = 'გთხოვთ შეავსოთ სავალდებულო ველები!';
    }
}

// პროდუქტის წაშლა
if (isset($_GET['delete'])) {
    $productId = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
    $stmt->execute([$productId]);
    $message = 'პროდუქტი წარმატებით წაიშალა!';
}

// პროდუქტების სიის მიღება
$stmt = $pdo->query("SELECT * FROM products ORDER BY created_at DESC");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

include 'includes/header.php';
?>

<div class="content">
    <h2>პროდუქტების მართვა</h2>
    
    <?php if (!empty($message)): ?>
        <div class="message"><?php echo $message; ?></div>
    <?php endif; ?>
    
    <div class="form-container">
        <h3>ახალი პროდუქტის დამატება</h3>
        <form action="products.php" method="post">
            <div class="form-group">
                <label for="name">სახელი:</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="description">აღწერა:</label>
                <textarea id="description" name="description"></textarea>
            </div>
            <div class="form-group">
                <label for="price">ფასი:</label>
                <input type="number" id="price" name="price" step="0.01" required>
            </div>
            <div class="form-group">
                <label for="quantity">რაოდენობა:</label>
                <input type="number" id="quantity" name="quantity" required>
            </div>
            <button type="submit" name="add_product">დამატება</button>
        </form>
    </div>
    
    <h3>პროდუქტების სია</h3>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>სახელი</th>
                <th>აღწერა</th>
                <th>ფასი</th>
                <th>რაოდენობა</th>
                <th>თარიღი</th>
                <th>მოქმედებები</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products as $product): ?>
            <tr>
                <td><?php echo $product['id']; ?></td>
                <td><?php echo htmlspecialchars($product['name']); ?></td>
                <td><?php echo htmlspecialchars($product['description']); ?></td>
                <td><?php echo $product['price']; ?>₾</td>
                <td><?php echo $product['quantity']; ?></td>
                <td><?php echo $product['created_at']; ?></td>
                <td>
                    <a href="?delete=<?php echo $product['id']; ?>" onclick="return confirm('დარწმუნებული ხართ?')">წაშლა</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include 'includes/footer.php'; ?>