<?php

$expenseModel = new Expense();
$vehicleModel = new Vehicle();

$userId = Auth::userId();
$total = $expenseModel->getTotalByUser($userId);
$summary = $expenseModel->getSummaryByCategory($userId);
$vehicles = $vehicleModel->getAllByUser($userId);

?>
<section>
    <h2>Prehľad</h2>

    <div class="cards">
        <div class="card">
            <h3>Celkové výdavky</h3>
            <p class="big"><?= number_format($total, 2, ',', ' ') ?> €</p>
        </div>
        <div class="card">
            <h3>Počet vozidiel</h3>
            <p class="big"><?= count($vehicles) ?></p>
        </div>
    </div>

    <h3>Výdavky podľa kategórie</h3>
    <?php if (empty($summary)): ?>
        <p>Zatiaľ žiadne výdavky.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr><th>Kategória</th><th>Suma</th></tr>
            </thead>
            <tbody>
                <?php foreach ($summary as $row): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['category']) ?></td>
                        <td><?= number_format((float) $row['total'], 2, ',', ' ') ?> €</td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</section>
