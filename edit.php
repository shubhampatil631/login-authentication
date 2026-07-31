<?php
require_once 'config.php';

// Redirect to login if user is not authenticated
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$error = '';
$contact = null;
$id = $_GET['id'] ?? '';

if (empty($id)) {
    header("Location: dashboard.php");
    exit;
}

// Fetch existing contact details
try {
    $stmt = $pdo->prepare("SELECT * FROM contacts WHERE id = ?");
    $stmt->execute([$id]);
    $contact = $stmt->fetch();

    if (!$contact) {
        header("Location: dashboard.php");
        exit;
    }
} catch (\PDOException $e) {
    $error = 'Failed to load contact data: ' . $e->getMessage();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $address = trim($_POST['address'] ?? '');

    if (empty($name) || empty($email) || empty($phone)) {
        $error = 'Name, Email, and Phone fields are required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } else {
        try {
            $stmt = $pdo->prepare("UPDATE contacts SET name = ?, email = ?, phone = ?, address = ? WHERE id = ?");
            $stmt->execute([$name, $email, $phone, $address, $id]);

            header("Location: dashboard.php?msg=updated");
            exit;
        } catch (\PDOException $e) {
            $error = 'Failed to update contact: ' . $e->getMessage();
        }
    }
} else {
    // Populate form variables from fetched contact database details
    $name = $contact['name'] ?? '';
    $email = $contact['email'] ?? '';
    $phone = $contact['phone'] ?? '';
    $address = $contact['address'] ?? '';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Contact - Authenticated CRUD App</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header class="header">
        <div class="container header-content">
            <div class="logo">CRUD Manager</div>
            <div class="user-nav">
                <span class="user-badge">Logged in as: <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong></span>
                <a href="logout.php" class="btn btn-secondary btn-sm">Log Out</a>
            </div>
        </div>
    </header>

    <main class="container">
        <div class="form-panel">
            <h2 class="form-panel-title">Edit Contact</h2>

            <?php if (!empty($error)): ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <form action="edit.php?id=<?php echo htmlspecialchars($id); ?>" method="POST">
                <div class="form-group">
                    <label for="name" class="form-label">Full Name</label>
                    <input type="text" name="name" id="name" class="form-control" placeholder="e.g. John Doe" required value="<?php echo htmlspecialchars($name); ?>">
                </div>

                <div class="form-group">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" name="email" id="email" class="form-control" placeholder="e.g. john.doe@example.com" required value="<?php echo htmlspecialchars($email); ?>">
                </div>

                <div class="form-group">
                    <label for="phone" class="form-label">Phone Number</label>
                    <input type="text" name="phone" id="phone" class="form-control" placeholder="e.g. +1-555-0199" required value="<?php echo htmlspecialchars($phone); ?>">
                </div>

                <div class="form-group">
                    <label for="address" class="form-label">Address (Optional)</label>
                    <textarea name="address" id="address" class="form-control" placeholder="Enter physical address..."><?php echo htmlspecialchars($address); ?></textarea>
                </div>

                <div class="form-actions">
                    <a href="dashboard.php" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Update Contact</button>
                </div>
            </form>
        </div>
    </main>
</body>
</html>
