<?php
require_once 'config.php';

// Redirect to login if user is not authenticated
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$id = $_GET['id'] ?? '';

if (!empty($id)) {
    try {
        // Delete contact by id using prepared statement
        $stmt = $pdo->prepare("DELETE FROM contacts WHERE id = ?");
        $stmt->execute([$id]);

        header("Location: dashboard.php?msg=deleted");
        exit;
    } catch (\PDOException $e) {
        // Display database deletion error on dashboard
        header("Location: dashboard.php?msg=error");
        exit;
    }
} else {
    header("Location: dashboard.php");
    exit;
}
?>
