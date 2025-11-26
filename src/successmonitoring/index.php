<?php
require_once __DIR__ . '/functions.php';

$ip = get_client_ip();
$successMessage = '';
$errorMessage = '';
$hasIpRegistered = has_ip_registered($ip);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');

    if ($name === '') {
        $errorMessage = 'Please enter your name.';
    } elseif (strlen($name) > 16) {
        $errorMessage = 'Name too long. Maximum length is 16 characters.';
    } elseif ($hasIpRegistered) {
        $errorMessage = 'Your IP address has already registered a success.';
    } else {
        if (add_participant($name, $ip)) {
            $successMessage = 'Congratulations! Your success has been recorded. Click on the link below to see the current status.';
            $hasIpRegistered = true;
        } else {
            $errorMessage = 'Unable to save your success. Your IP might already be registered.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Register Success</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container py-5">

        <!-- Enlarged emoji + increased spacing -->
        <div class="text-center mb-5 mt-4">
            <div style="font-size: 10rem; line-height: 1;">🥳</div>
            <div class="fs-4 fw-semibold mt-4 mb-4">
                Congratulation! You successfully completed the lab!
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="card shadow-sm">
                    <div class="card-header text-center">
                        <h4 class="mb-0">Success Registration</h4>
                    </div>
                    <div class="card-body">

                        <?php if ($successMessage): ?>
                            <div class="alert alert-success" role="alert">
                                <?php echo h($successMessage); ?>
                            </div>
                        <?php endif; ?>

                        <?php if ($errorMessage): ?>
                            <div class="alert alert-danger" role="alert">
                                <?php echo h($errorMessage); ?>
                            </div>
                        <?php endif; ?>
                        <?php if (!$hasIpRegistered): ?>
                            <p class="mb-3">
                                Log your name to share your success. <br />
                                Your IP: <?php echo h($ip); ?>
                            </p>

                            <form method="post" action="index.php">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Your name</label>
                                    <input type="text" name="name" id="name" class="form-control" maxlength="16"
                                        value="<?php echo isset($_POST['name']) ? h($_POST['name']) : ''; ?>" required>
                                </div>
                                <button type="submit" class="btn btn-primary w-100">
                                    Register My Success
                                </button>
                            </form>
                        <?php else: ?>
                            <?php if (empty($successMessage) && empty($errorMessage)): ?>
                            <div class="alert alert-info" role="alert">
                                A success for <?php echo h($ip); ?> has already been registered. Click on the link below to see the current status. 
                            </div>
                            <?php endif; ?>
                        <?php endif; ?>

                    </div>
                    <div class="card-footer text-center small">
                        <a href="status.php">View all participants</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>