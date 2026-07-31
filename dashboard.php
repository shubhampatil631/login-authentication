<?php
require_once 'config.php';

// Redirect to login if user is not authenticated
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$contacts = [];
$error = '';

try {
    // Fetch all contacts ordered by creation time
    $stmt = $pdo->query("SELECT * FROM contacts ORDER BY created_at DESC");
    $contacts = $stmt->fetchAll();
} catch (\PDOException $e) {
    $error = 'Failed to load contacts: ' . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Authenticated CRUD App</title>
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
        <div class="dashboard-title-area">
            <h1 class="dashboard-title">Contacts Directory</h1>
            <a href="create.php" class="btn btn-primary">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                Add New Contact
            </a>
        </div>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['msg']) && $_GET['msg'] === 'created'): ?>
            <div class="alert alert-success" role="alert">
                Contact successfully created!
            </div>
        <?php elseif (isset($_GET['msg']) && $_GET['msg'] === 'updated'): ?>
            <div class="alert alert-success" role="alert">
                Contact successfully updated!
            </div>
        <?php elseif (isset($_GET['msg']) && $_GET['msg'] === 'deleted'): ?>
            <div class="alert alert-success" role="alert">
                Contact successfully deleted.
            </div>
        <?php endif; ?>

        <?php if (empty($contacts)): ?>
            <div class="table-responsive">
                <div class="empty-state">
                    <div class="empty-state-icon">📂</div>
                    <h2 class="empty-state-title">No contacts found</h2>
                    <p>Get started by creating your first directory entry.</p>
                    <div style="margin-top: 1.5rem;">
                        <a href="create.php" class="btn btn-primary">Add Contact</a>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Address</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($contacts as $contact): ?>
                            <tr>
                                <td>
                                    <strong style="color: #fff;"><?php echo htmlspecialchars($contact['name']); ?></strong>
                                </td>
                                <td><?php echo htmlspecialchars($contact['email']); ?></td>
                                <td><?php echo htmlspecialchars($contact['phone']); ?></td>
                                <td><?php echo htmlspecialchars($contact['address'] ?: 'N/A'); ?></td>
                                <td><span class="badge badge-info">Active</span></td>
                                <td>
                                    <div class="actions">
                                        <a href="edit.php?id=<?php echo $contact['id']; ?>" class="btn btn-secondary btn-sm">Edit</a>
                                        <a href="delete.php?id=<?php echo $contact['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this contact?');">Delete</a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </main>
</body>
</html>
