<?php

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    $userModel = new User();
    $user = $userModel->login($username, $password);

    if ($user !== null) {
        Auth::login($user);
        header('Location: index.php?page=dashboard');
        exit;
    }

    $error = 'Nesprávne meno alebo heslo.';
}

?>
<section class="form-box">
    <h2>Prihlásenie</h2>

    <?php if ($error): ?>
        <p class="alert alert-error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="post">
        <label>Používateľské meno
            <input type="text" name="username" required>
        </label>
        <label>Heslo
            <input type="password" name="password" required>
        </label>
        <button class="btn" type="submit">Prihlásiť</button>
    </form>

    <p>Nemáš účet? <a href="index.php?page=register">Zaregistruj sa</a></p>
</section>
