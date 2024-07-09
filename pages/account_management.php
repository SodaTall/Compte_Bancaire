<?php
include_once '../config/database.php';
include_once '../classes/CompteBancaire.php';
include_once '../classes/OperationBancaire.php';

$database = new Database();
$db = $database->getConnection();

$compte = new CompteBancaire($db);
$operation = new OperationBancaire($db);

$message = '';

if ($_POST) {
    $account_number = $_POST['account_number'];
    $amount = $_POST['amount'];
    $type = $_POST['type'];

    if ($type == 'depot') {
        if ($compte->deposit($account_number, $amount)) {
            $operation->create($account_number, 'dépôt', $amount);
            $message = 'Opération réussie.';
        } else {
            $message = 'Échec de l\'opération.';
        }
    } elseif ($type == 'retrait') {
        if ($compte->withdraw($account_number, $amount)) {
            $operation->create($account_number, 'retrait', $amount);
            $message = 'Opération réussie.';
        } else {
            $message = 'Échec de l\'opération.';
        }
    } elseif ($type == 'virement') {
        $recipient_account_number = $_POST['recipient_account_number'];
        if ($compte->transfer($account_number, $recipient_account_number, $amount)) {
            $operation->create($account_number, 'virement', $amount);
            $operation->create($recipient_account_number, 'virement', $amount);
            $message = 'Opération réussie.';
        } else {
            $message = 'Échec de l\'opération.';
        }
    }
}

$accounts = $compte->readAll();

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des Comptes Bancaires</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h2>Gestion des Comptes Bancaires</h2>
        <?php if ($message) { ?>
            <div class="alert alert-info"><?php echo $message; ?></div>
        <?php } ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Numéro de Compte</th>
                    <th>Solde</th>
                    <th>Client</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $accounts->fetch(PDO::FETCH_ASSOC)) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['account_number']); ?></td>
                        <td><?php echo htmlspecialchars($row['balance']); ?></td>
                        <td><?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?></td>
                        <td>
                            <form method="post" action="account_management.php">
                                <input type="hidden" name="account_number" value="<?php echo htmlspecialchars($row['account_number']); ?>">
                                <select name="type" class="form-select">
                                    <option value="depot">Dépôt</option>
                                    <option value="retrait">Retrait</option>
                                    <option value="virement">Virement</option>
                                </select>
                                <input type="number" name="amount" class="form-control" placeholder="Montant" required>
                                <input type="text" name="recipient_account_number" class="form-control" placeholder="Numéro de Compte Bénéficiaire (pour virement)">
                                <button type="submit" class="btn btn-primary mt-2">Valider</button>
                            </form>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>
</html>
