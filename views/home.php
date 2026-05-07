<section class="hero">
    <h1>Sleduj výdavky na svoje auto</h1>
    <p>Jednoduchá aplikácia na evidenciu paliva, servisu, poistenia a ďalších nákladov.</p>
    <?php if (!Auth::check()): ?>
        <a class="btn" href="index.php?page=register">Začať</a>
        <a class="btn btn-secondary" href="index.php?page=login">Prihlásiť sa</a>
    <?php else: ?>
        <a class="btn" href="index.php?page=dashboard">Otvoriť prehľad</a>
    <?php endif; ?>
</section>
