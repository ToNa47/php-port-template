<?php
$username  = "ToNa47";
$cacheDir  = __DIR__ . '/../cache';
$cacheFile = $cacheDir . '/github_repos.json';
$cacheTtl  = 600; // 10 minutes

if (!is_dir($cacheDir)) {
    @mkdir($cacheDir, 0755, true);
}

$repos = [];
$usingStaleCache = false;

if (is_file($cacheFile) && (time() - filemtime($cacheFile) < $cacheTtl)) {
    $repos = json_decode((string) file_get_contents($cacheFile), true) ?: [];
} else {
    $url = "https://api.github.com/users/" . urlencode($username) . "/repos?sort=updated&per_page=100";
    $options = [
        "http" => [
            "header"  => "User-Agent: PHP\r\n",
            "timeout" => 5,
        ],
    ];
    $context  = stream_context_create($options);
    $response = @file_get_contents($url, false, $context);
    $decoded  = $response !== false ? json_decode($response, true) : null;

    if (is_array($decoded) && !isset($decoded['message'])) {
        // Good response (GitHub wraps errors, e.g. rate limits, in a "message" key)
        $repos = $decoded;
        @file_put_contents($cacheFile, json_encode($repos));
    } elseif (is_file($cacheFile)) {
        // API failed or was rate-limited — fall back to the last good copy
        $repos = json_decode((string) file_get_contents($cacheFile), true) ?: [];
        $usingStaleCache = true;
    }
}
?>
<div class="page-card">

    <div class="page-header">
        <h1>Repository Saya</h1>
        <div class="nav-links">
            <a href="index.php?page=profile" class="link-profile">Profile</a>
        </div>
    </div>

    <?php if ($usingStaleCache) : ?>
        <p class="repo-meta" style="margin-bottom:14px;">Menampilkan data cache terakhir (GitHub API sedang tidak tersedia).</p>
    <?php endif; ?>

    <?php if (empty($repos)) : ?>
        <p class="empty-state">Gagal memuat data repo, atau belum ada repo.</p>
    <?php else : ?>
        <?php foreach ($repos as $repo) : ?>
            <div class="repo-card">
                <h3><a href="<?= htmlspecialchars($repo['html_url']) ?>" target="_blank" rel="noopener noreferrer"><?= htmlspecialchars($repo['name']) ?></a></h3>
                <p><?= htmlspecialchars($repo['description'] ?? 'Tidak ada deskripsi') ?></p>
                <p class="repo-meta">
                    ⭐ <?= (int) $repo['stargazers_count'] ?>
                    &nbsp;|&nbsp;
                    <?= htmlspecialchars($repo['language'] ?? '-') ?>
                    &nbsp;|&nbsp;
                    Update terakhir: <?= htmlspecialchars(date("d M Y", strtotime($repo['updated_at']))) ?>
                </p>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

</div>
