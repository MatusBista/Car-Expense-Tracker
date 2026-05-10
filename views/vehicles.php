<?php

$vehicleModel = new Vehicle();
$userId = Auth::userId();
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'create') {
        $name = trim($_POST['name'] ?? '');
        $plate = trim($_POST['license_plate'] ?? '');
        $year = $_POST['year'] !== '' ? (int) $_POST['year'] : null;

        if ($name !== '') {
            $vehicleModel->create($userId, $name, $plate ?: null, $year);
            $message = 'Vozidlo bolo pridané.';
        }
    }

    if ($action === 'delete' && isset($_POST['id'])) {
        $vehicleModel->delete((int) $_POST['id'], $userId);
        $message = 'Vozidlo bolo zmazané.';
    }
}

$vehicles = $vehicleModel->getAllByUser($userId);

?>
<section>
    <h2>Moje vozidlá</h2>

    <?php if ($message): ?>
        <p class="alert alert-success"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <h3>Pridať vozidlo</h3>
    <form method="post" class="inline-form">
        <input type="hidden" name="action" value="create">
        <input type="text" name="name" placeholder="Názov (napr. Škoda Octavia)" required>
        <input type="text" name="license_plate" placeholder="ŠPZ">
        <input type="number" name="year" placeholder="Rok" min="1900" max="2100">
        <button class="btn" type="submit">Pridať</button>
    </form>

    <h3>Zoznam</h3>
    <?php if (empty($vehicles)): ?>
        <p>Zatiaľ nemáš pridané žiadne vozidlo.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Názov</th>
                    <th>ŠPZ</th>
                    <th>Rok</th>
                    <th>Akcia</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($vehicles as $v): ?>
                    <tr>
                        <td><?= htmlspecialchars($v['name']) ?></td>
                        <td><?= htmlspecialchars($v['license_plate'] ?? '-') ?></td>
                        <td><?= htmlspecialchars((string) ($v['year'] ?? '-')) ?></td>
                        <td>
                            <form method="post" onsubmit="return confirm('Naozaj zmazať?');">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?= (int) $v['id'] ?>">
                                <button class="btn btn-danger" type="submit">Zmazať</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</section>
