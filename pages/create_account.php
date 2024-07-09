<?php
include_once '../config/database.php';
include_once '../classes/Client.php';
include_once '../classes/CompteBancaire.php';

$database = new Database();
$db = $database->getConnection();

$client = new Client($db);
$compte = new CompteBancaire($db);

if ($_POST) {
    $client->first_name = $_POST['first_name'];
    $client->last_name = $_POST['last_name'];
    $client->address = $_POST['address'];
    $client->phone_number = $_POST['phone_number'];

    if ($client->create()) {
        $compte->balance = 0;
        $compte->client_id = $db->lastInsertId();

        if ($compte->create()) {
            echo "Compte créé avec succès.";
        } else {
            echo "Impossible de créer le compte.";
        }
    } else {
        echo "Impossible de créer le client.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Créer un Compte</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h2>Créer un Compte Bancaire</h2>
        <form method="post">
            <div class="mb-3">
                <label for="first_name" class="form-label">Prénom</label>
                <input type="text" class="form-control" id="first_name" name="first_name" required>
            </div>
            <div class="mb-3">
                <label for="last_name" class="form-label">Nom</label>
                <input type="text" class="form-control" id="last_name" name="last_name" required>
            </div>
            <div class="mb-3">
                <label for="address" class="form-label">Adresse</label>
                <input type="text" class="form-control" id="address" name="address" required>
            </div>
            <div class="mb-3">
                <label for="phone_number" class="form-label">Numéro de téléphone</label>
                <input type="text" class="form-control" id="phone_number" name="phone_number" required>
            </div>
            <button type="submit" class="btn btn-primary">Créer</button>
        </form>
    </div>
</body>
</html>
