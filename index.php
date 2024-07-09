<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion Bancaire</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <style>
        body{
            background-color: aqua;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Bienvenue dans le système de gestion bancaire</h1>
        <div class="list-group">
            <a href="pages/create_account.php" class="list-group-item list-group-item-action">Créer un Compte Bancaire</a>
            <a href="pages/manage_accounts.php" class="list-group-item list-group-item-action">Gérer les Comptes Bancaires</a>
            <a href="pages/account_statement.php?account_number=1" class="list-group-item list-group-item-action">Relevé de Compte</a>
        </div>
    </div>
</body>
</html>
