<?php

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $passwordConfirm = $_POST['password_confirm'] ?? '';

    if ($username === '' || $password === '') {
        $error = 'Vyplň meno aj heslo.';
    } elseif (strlen($password) < 6) {
        $error = 'Heslo musí mať aspoň 6 znakov.';
    } elseif ($password !== $passwordConfirm) {
        $error = 'Heslá sa nezhodujú.';
    } else {
        $user = new User();
        if ($user->register($username, $password)) {
            $success = 'Registrácia bola úspešná. Môžeš sa prihlásiť.';
        } else {
            $error = 'Toto meno už existuje.';
        }
    }
}

?>
<section class="form-box">
    <h2>Registrácia</h2>

    <?php if ($error): ?>
        <p class="alert alert-error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <?php if ($success): ?>
        <p class="alert alert-success"><?= htmlspecialchars($success) ?></p>
    <?php endif; ?>

    <form method="post">
        <label>Používateľské meno
            <input type="text" name="username" required>
        </label>
        <label>Heslo
            <input type="password" name="password" required>
        </label>
        <label>Heslo znova
            <input type="password" name="password_confirm" required>
        </label>
        <button class="btn" type="submit">Registrovať</button>
    </form>

    <p>Máš účet? <a href="index.php?page=login">Prihlás sa</a></p>
</section>
