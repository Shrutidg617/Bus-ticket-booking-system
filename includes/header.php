<?php require_once __DIR__ . '/../config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Horizon Haul</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark custom-nav sticky-top">
    <div class="container">
        <a class="navbar-brand fw-bold brand-name" href="index.php">Horizon Haul</a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav" aria-controls="mainNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-2">
                <li class="nav-item"><a class="nav-link nav-animate" href="index.php">Home</a></li>
                <li class="nav-item"><a class="nav-link nav-animate" href="index.php#search-section">Search</a></li>
                <li class="nav-item"><a class="nav-link nav-animate" href="ticket.php">Tickets</a></li>
                <li class="nav-item"><a class="nav-link nav-animate" href="cancel.php">Cancel</a></li>

                <?php if (isLoggedIn()): ?>
                    <li class="nav-item">
                        <span class="nav-link text-warning fw-semibold">Hi, <?php echo h($_SESSION['user_name']); ?></span>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-theme-outline btn-sm mt-2 mt-lg-0 ms-lg-2" href="logout.php">Logout</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="btn btn-theme-outline btn-sm mt-2 mt-lg-0 ms-lg-2" href="login.php">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-theme btn-sm mt-2 mt-lg-0 ms-lg-2" href="signup.php">Signup</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>