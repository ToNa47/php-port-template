<?php
$profile = [
    "name"       => "Nathan",
    "handle"     => "ToNa47 (Ikker)",
    "title"      => "Student Developer",
    "bio"        => "Sedang belajar web development, tertarik dengan PHP, sistem backend, dan proyek-proyek kecil.",
    "location"   => "Indonesia",
    "github"     => "https://github.com/ToNa47",
    "email"      => "awaslupaya21@gmail.com",
    "skills"     => ["PHP", "JavaScript", "MySQL", "Git", "HTML/CSS"],
    "about" => [
        "Student from Indonesia",
        "Currently learning Python, Java, Linux",
        "Interested in Cyber Security",
        "Building random projects for fun",
        "Learning something new every day",
    ],
    "social" => [
        "Instagram" => "https://instagram.com/sednzt",
        "YouTube"   => "https://youtube.com/@sedonzets",
    ],
    "tech_stack" => ["python", "java", "linux", "git", "github", "vscode", "bash", "androidstudio"],
];
?>
<div class="page-card">

    <div class="profile-header">
        <div class="avatar"><?= htmlspecialchars(strtoupper(substr($profile['name'], 0, 1))) ?></div>
        <div>
            <h1><?= htmlspecialchars($profile['name']) ?></h1>
            <p id="typing-text"></p>
        </div>
    </div>

    <p class="bio"><?= htmlspecialchars($profile['bio']) ?></p>

    <div class="info-row">
        <strong>Lokasi</strong>
        <span><?= htmlspecialchars($profile['location']) ?></span>
    </div>

    <div class="skills">
        <?php foreach ($profile['skills'] as $skill) : ?>
            <span class="skill-tag"><?= htmlspecialchars($skill) ?></span>
        <?php endforeach; ?>
    </div>

    <?php if (!empty($profile['about'])) : ?>
    <div class="section">
        <h3>About</h3>
        <ul class="about-list">
            <?php foreach ($profile['about'] as $point) : ?>
                <li><?= htmlspecialchars($point) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php endif; ?>

    <?php if (!empty($profile['social'])) : ?>
    <div class="section">
        <h3>Connect</h3>
        <div class="social-links">
            <?php foreach ($profile['social'] as $platform => $link) : ?>
                <a href="<?= htmlspecialchars($link) ?>" target="_blank" rel="noopener noreferrer"><?= htmlspecialchars($platform) ?></a>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <?php if (!empty($profile['tech_stack'])) : ?>
    <div class="section">
        <h3>Tech Stack</h3>
        <img src="https://skillicons.dev/icons?i=<?= implode(',', array_map('urlencode', $profile['tech_stack'])) ?>" alt="Tech stack icons">
    </div>
    <?php endif; ?>

    <div class="links">
        <a href="<?= htmlspecialchars($profile['github']) ?>" class="link-github" target="_blank" rel="noopener noreferrer">GitHub</a>
        <a href="mailto:<?= htmlspecialchars($profile['email']) ?>" class="link-email">Email</a>
        <a href="index.php?page=projects" class="link-projects">Projects</a>
    </div>

</div>

<script>
// Typing effect under the name — kept page-local since it's the only page that uses it.
(function () {
    const phrases = ["Student Developer", "Python | Java | Linux", "Cyber Security Enthusiast", "Always Learning Something New!"];
    const el = document.getElementById('typing-text');
    if (!el) return;

    let phraseIndex = 0;
    let charIndex = 0;
    let isDeleting = false;

    function typeLoop() {
        const currentPhrase = phrases[phraseIndex];
        charIndex = isDeleting ? charIndex - 1 : charIndex + 1;
        el.textContent = currentPhrase.substring(0, charIndex);

        let typeSpeed = isDeleting ? 40 : 80;

        if (!isDeleting && charIndex === currentPhrase.length) {
            typeSpeed = 1500;
            isDeleting = true;
        } else if (isDeleting && charIndex === 0) {
            isDeleting = false;
            phraseIndex = (phraseIndex + 1) % phrases.length;
            typeSpeed = 300;
        }

        setTimeout(typeLoop, typeSpeed);
    }

    typeLoop();
})();
</script>
