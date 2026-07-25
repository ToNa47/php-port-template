<?php
declare(strict_types=1);

define('INDEX', true);

header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header('Referrer-Policy: no-referrer-when-downgrade');

/**
 * Only pages that actually have a matching file in /pages are routable.
 * The original list also contained "login" and "settings", but no such
 * files exist in the project, so visiting ?page=login threw a fatal
 * "failed to open stream" error. They're left out until those pages
 * are actually built.
 */
$halaman = [
    'home',
    'dashboard',
    'profile',
    'ticket',
    'projects',
];

$page = $_GET['page'] ?? 'home';

if (!in_array($page, $halaman, true)) {
    $page = '404';
}

$pageFile = __DIR__ . "/pages/{$page}.php";
$showBackground = in_array($page, ['home', 'dashboard', 'profile', 'projects', '404'], true);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Website</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php if ($showBackground) : ?>
    <canvas id="bg-canvas"></canvas>
<?php endif; ?>

<div class="container">
<?php
if (is_file($pageFile)) {
    include $pageFile;
} else {
    include __DIR__ . '/pages/404.php';
}
?>
</div>

<?php if ($showBackground) : ?>
    <script src="assets/particles.js"></script>
<?php endif; ?>

</body>
</html>
