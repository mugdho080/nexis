<?php
session_start();

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if ($email === 'admin@admin.com' && $password === '1234567') {
        $_SESSION['is_admin'] = true;
        header('Location: index.php');
        exit;
    }

    $error = 'Invalid credentials. Try admin@admin.com / 1234567.';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/styles.css" rel="stylesheet">
</head>
<body class="login-body">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card">
                    <div class="card-body p-4">
                        <h3 class="mb-2">Admin Login</h3>
                        <p class="text-muted">Use the demo admin credentials to access the dashboard.</p>
                        <?php if ($error): ?>
                            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                        <?php endif; ?>
                        <form method="post">
                            <div class="mb-3">
                                <label class="form-label" for="email">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required placeholder="admin@admin.com">
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="password">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required placeholder="1234567">
                            </div>
                            <button class="btn btn-primary w-100" type="submit">Sign in</button>
                        </form>
                        <div class="mt-3 small text-muted">
                            Demo credentials: admin@admin.com / 1234567
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
