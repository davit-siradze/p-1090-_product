<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ადმინ პანელი</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <header>
        <div class="container">
            <h1>ადმინისტრაციული პანელი</h1>
            <nav>
                <ul>
                    <li><a href="dashboard.php">დეშბორდი</a></li>
                    <?php if (isAdmin()): ?>
                        <li><a href="users.php">მომხმარებლები</a></li>
                        <li><a href="products.php">პროდუქტები</a></li>
                        <li><a href="orders.php">შეკვეთები</a></li>
                    <?php endif; ?>
                    <li><a href="logout.php">გამოსვლა</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main class="container">