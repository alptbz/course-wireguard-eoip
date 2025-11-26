<?php
require_once __DIR__ . '/functions.php';

$requiredPassword = '1234';

// Basic protection via GET parameter (?password=1234)
if (!isset($_GET['password']) || $_GET['password'] !== $requiredPassword) {
    header('HTTP/1.1 401 Unauthorized');
    echo '401 Unauthorized';
    exit;
}

$infoMessage  = '';
$errorMessage = '';

$action = $_GET['action'] ?? '';
$id     = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($action === 'delete' && $id > 0) {
    delete_participant($id);
    $infoMessage = 'Participant deleted.';
} elseif ($action === 'delete_all') {
    delete_all_participants();
    $infoMessage = 'All participants deleted.';
}

$participants = get_participants();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Participants</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <h3 class="mb-4">Reset Participants</h3>

    <?php if ($infoMessage): ?>
        <div class="alert alert-success"><?php echo h($infoMessage); ?></div>
    <?php endif; ?>

    <?php if ($errorMessage): ?>
        <div class="alert alert-danger"><?php echo h($errorMessage); ?></div>
    <?php endif; ?>

    <div class="mb-3">
        <a href="reset.php?password=<?php echo urlencode($requiredPassword); ?>&action=delete_all"
           class="btn btn-danger"
           onclick="return confirm('Delete all participants?');">
            Delete all participants
        </a>
        <a href="index.php" class="btn btn-secondary ms-2">Back to registration</a>
    </div>

    <?php if (empty($participants)): ?>
        <div class="alert alert-info">No participants to show.</div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-striped table-bordered align-middle">
                <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>IP</th>
                    <th>Registered at</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($participants as $index => $p): ?>
                    <tr>
                        <td><?php echo h((string)($index + 1)); ?></td>
                        <td><?php echo h($p['name']); ?></td>
                        <td><?php echo h($p['ip']); ?></td>
                        <td><?php echo h($p['created_at']); ?></td>
                        <td>
                            <a href="reset.php?password=<?php echo urlencode($requiredPassword); ?>&action=delete&id=<?php echo (int)$p['id']; ?>"
                               class="btn btn-sm btn-outline-danger"
                               onclick="return confirm('Delete this participant?');">
                                Delete
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
