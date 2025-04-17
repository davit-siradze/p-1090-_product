<?php
require_once 'includes/config.php';
redirectIfNotLoggedIn();
redirectIfNotAdmin();

$message = '';
$users = [];

// მომხმარებლების წაშლა
if (isset($_GET['delete']) {
    $userId = $_GET['delete'];
    if ($userId != $_SESSION['user_id']) { // არ შეიძლება საკუთარი თავის წაშლა
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$userId]);
        $message = 'მომხმარებელი წარმატებით წაიშალა!';
    } else {
        $message = 'თქვენ არ შეგიძლიათ საკუთარი თავის წაშლა!';
    }
}

// მომხმარებლების სიის მიღება
$stmt = $pdo->query("SELECT * FROM users ORDER BY created_at DESC");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

include 'includes/header.php';
?>

<div class="content">
    <h2>მომხმარებელთა მართვა</h2>
    
    <?php if (!empty($message)): ?>
        <div class="message"><?php echo $message; ?></div>
    <?php endif; ?>
    
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>მომხმარებელი</th>
                <th>Email</th>
                <th>როლი</th>
                <th>რეგისტრაციის თარიღი</th>
                <th>მოქმედებები</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
            <tr>
                <td><?php echo $user['id']; ?></td>
                <td><?php echo htmlspecialchars($user['username']); ?></td>
                <td><?php echo htmlspecialchars($user['email']); ?></td>
                <td><?php echo $user['role']; ?></td>
                <td><?php echo $user['created_at']; ?></td>
                <td>
                    <a href="?delete=<?php echo $user['id']; ?>" onclick="return confirm('დარწმუნებული ხართ?')">წაშლა</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include 'includes/footer.php'; ?>