<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start([
        'cookie_httponly' => true,
        'cookie_samesite' => 'Lax',
    ]);
}

function isLoggedIn(): bool
{
    return !empty($_SESSION['user_id']);
}

/**
 * Call at the top of any page that should not be publicly accessible.
 *
 * NOT wired into dashboard.php by default: index.php previously linked to
 * "login" and "settings" pages that don't exist anywhere in this project,
 * so there is currently no way for a real user to ever get $_SESSION['user_id']
 * set. Turning this on right now would just lock everyone out of the
 * dashboard link that's on the homepage. Build a login page (with
 * password_hash()/password_verify() and a users table) first, have it set
 * $_SESSION['user_id'], then call requireLogin() at the top of dashboard.php.
 */
function requireLogin(): void
{
    if (!isLoggedIn()) {
        header('Location: index.php?page=home&error=login_required');
        exit;
    }
}
