<?php
require_once 'includes/config.php';

if (isLoggedIn()) {
    header("Location: dashboard.php");
    exit();
}

$error = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (!empty($username) && !empty($password)) {
        $sql = "SELECT id, username, password, role FROM users WHERE username = :username";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->execute();
        
        if ($stmt->rowCount() == 1) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];
                
                header("Location: dashboard.php");
                exit();
            } else {
                $error = 'არასწორი პაროლი.';
            }
        } else {
            $error = 'მომხმარებელი არ მოიძებნა.';
        }
    } else {
        $error = 'გთხოვთ შეავსოთ ორივე ველი.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ავტორიზაცია</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="login-container">
        <h2>ადმინ პანელი - ავტორიზაცია</h2>
        <?php if (!empty($error)): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        <form action="login.php" method="post">
            <div class="form-group">
                <label for="username">მომხმარებელი:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">პაროლი:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit">შესვლა</button>
        </form>
    </div>
</body>
</html>