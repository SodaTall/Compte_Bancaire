<?php
include_once '../config/database.php';
include_once '../classes/OperationBancaire.php';

// Initialisation de la connexion à la base de données
$database = new Database();
$db = $database->getConnection();

// Initialisation de la classe OperationBancaire
$operation = new OperationBancaire($db);

// Récupération du numéro de compte
$account_number = isset($_GET['account_number']) ? $_GET['account_number'] : die('Numéro de compte non spécifié.');

// Récupération des opérations bancaires pour ce compte
$stmt = $operation->readByAccount($account_number);

// Vérification s'il y a des opérations pour ce compte
$operations = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Relevé de Compte</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h2>Relevé de Compte pour le Compte #<?php echo htmlspecialchars($account_number); ?></h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Numéro de Compte</th>
                    <th>Type</th>
                    <th>Montant</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($operations) > 0) { ?>
                    <?php foreach ($operations as $operation) { ?>
                        <tr>
                            <td><?php echo htmlspecialchars($operation['id']); ?></td>
                            <td><?php echo htmlspecialchars($operation['account_number']); ?></td>
                            <td><?php echo htmlspecialchars($operation['type']); ?></td>
                            <td><?php echo htmlspecialchars($operation['amount']); ?></td>
                            <td><?php echo htmlspecialchars($operation['created_at']); ?></td>
                        </tr>
                    <?php } ?>
                <?php } else { ?>
                    <tr>
                        <td colspan="5" class="text-center">Aucun compte trouvé.</td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        <a href="manage_accounts.php" class="btn btn-primary">Retour</a>
    </div>
</body>
</html>
