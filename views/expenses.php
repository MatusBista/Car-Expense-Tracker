<?php

$expenseModel = new Expense();
$vehicleModel = new Vehicle();
$userId = Auth::userId();
$message = '';
$error = '';

$vehicles = $vehicleModel->getAllByUser($userId);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'delete' && isset($_POST['id'])) {
        $id = (int) $_POST['id'];
        $existing = $expenseModel->getById($id, $userId);
        if ($existing) {
            $expenseModel->delete($id);
            $message = 'Výdavok bol zmazaný.';
        }
    }

    if ($action === 'create' || $action === 'update') {
        $vehicleId = (int) ($_POST['vehicle_id'] ?? 0);
        $category = $_POST['category'] ?? '';
        $amount = (float) str_replace(',', '.', $_POST['amount'] ?? '0');
        $date = $_POST['expense_date'] ?? '';
        $description = trim($_POST['description'] ?? '');

        $vehicle = $vehicleModel->getById($vehicleId, $userId);

        if (!$vehicle) {
            $error = 'Neplatné vozidlo.';
        } elseif (!in_array($category, Expense::CATEGORIES, true)) {
            $error = 'Neplatná kategória.';
        } elseif ($amount <= 0) {
            $error = 'Suma musí byť kladná.';
        } elseif ($date === '') {
            $error = 'Vyber dátum.';
        } else {
            if ($action === 'create') {
                $expenseModel->create($vehicleId, $category, $amount, $date, $description ?: null);
                $message = 'Výdavok bol pridaný.';
            } else {
                $id = (int) ($_POST['id'] ?? 0);
                $existing = $expenseModel->getById($id, $userId);
                if ($existing) {
                    $expenseModel->update($id, $vehicleId, $category, $amount, $date, $description ?: null);
                    $message = 'Výdavok bol upravený.';
                }
            }
        }
    }
}

$editId = isset($_GET['edit']) ? (int) $_GET['edit'] : 0;
$editing = $editId > 0 ? $expenseModel->getById($editId, $userId) : null;

$filterVehicle = isset($_GET['vehicle']) && $_GET['vehicle'] !== '' ? (int) $_GET['vehicle'] : null;
$expenses = $expenseModel->getAllByUser($userId, $filterVehicle);

?>
<section>
    <h2>Výdavky</h2>

    <?php if ($message): ?>
        <p class="alert alert-success"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <?php if ($error): ?>
        <p class="alert alert-error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <?php if (empty($vehicles)): ?>
        <p>Najprv si pridaj <a href="index.php?page=vehicles">vozidlo</a>.</p>
    <?php else: ?>

        <h3><?= $editing ? 'Upraviť výdavok' : 'Pridať výdavok' ?></h3>
        <form method="post" class="form-grid">
            <input type="hidden" name="action" value="<?= $editing ? 'update' : 'create' ?>">
            <?php if ($editing): ?>
                <input type="hidden" name="id" value="<?= (int) $editing['id'] ?>">
            <?php endif; ?>

            <label>Vozidlo
                <select name="vehicle_id" required>
                    <?php foreach ($vehicles as $v): ?>
                        <option value="<?= (int) $v['id'] ?>" <?= $editing && $editing['vehicle_id'] == $v['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($v['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </label>

            <label>Kategória
                <select name="category" required>
                    <?php foreach (Expense::CATEGORIES as $cat): ?>
                        <option value="<?= htmlspecialchars($cat) ?>" <?= $editing && $editing['category'] === $cat ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </label>

            <label>Suma (€)
                <input type="number" step="0.01" name="amount" required
                       value="<?= $editing ? htmlspecialchars($editing['amount']) : '' ?>">
            </label>

            <label>Dátum
                <input type="date" name="expense_date" required
                       value="<?= $editing ? htmlspecialchars($editing['expense_date']) : date('Y-m-d') ?>">
            </label>

            <label class="full">Popis
                <input type="text" name="description"
                       value="<?= $editing ? htmlspecialchars($editing['description'] ?? '') : '' ?>">
            </label>

            <div class="form-actions">
                <button class="btn" type="submit"><?= $editing ? 'Uložiť' : 'Pridať' ?></button>
                <?php if ($editing): ?>
                    <a class="btn btn-secondary" href="index.php?page=expenses">Zrušiť</a>
                <?php endif; ?>
            </div>
        </form>

        <h3>Zoznam výdavkov</h3>
        <form method="get" class="inline-form">
            <input type="hidden" name="page" value="expenses">
            <label>Filter podľa vozidla
                <select name="vehicle" onchange="this.form.submit()">
                    <option value="">Všetky</option>
                    <?php foreach ($vehicles as $v): ?>
                        <option value="<?= (int) $v['id'] ?>" <?= $filterVehicle === (int) $v['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($v['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </label>
        </form>

        <?php if (empty($expenses)): ?>
            <p>Žiadne výdavky.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Dátum</th>
                        <th>Vozidlo</th>
                        <th>Kategória</th>
                        <th>Suma</th>
                        <th>Popis</th>
                        <th>Akcia</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($expenses as $e): ?>
                        <tr>
                            <td><?= htmlspecialchars($e['expense_date']) ?></td>
                            <td><?= htmlspecialchars($e['vehicle_name']) ?></td>
                            <td><?= htmlspecialchars($e['category']) ?></td>
                            <td><?= number_format((float) $e['amount'], 2, ',', ' ') ?> €</td>
                            <td><?= htmlspecialchars($e['description'] ?? '') ?></td>
                            <td>
                                <a class="btn" href="index.php?page=expenses&edit=<?= (int) $e['id'] ?>">Upraviť</a>
                                <form method="post" onsubmit="return confirm('Naozaj zmazať?');" style="display:inline">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?= (int) $e['id'] ?>">
                                    <button class="btn btn-danger" type="submit">Zmazať</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>

    <?php endif; ?>
</section>
