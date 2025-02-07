<?php
session_start();
include('includes/db_connection.php');

// Vérification de la connexion de l'utilisateur
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

// Récupérer la liste des clients
$query = "SELECT * FROM clients";
$stmt = $pdo->prepare($query);
$stmt->execute();
$clients = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Gestion de la création d'un client
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['create_client'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    $insertQuery = "INSERT INTO clients (name, email, phone) VALUES (:name, :email, :phone)";
    $stmt = $pdo->prepare($insertQuery);
    $stmt->execute(['name' => $name, 'email' => $email, 'phone' => $phone]);

    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Gestion de la suppression d'un client
if (isset($_GET['delete_id'])) {
    $client_id = $_GET['delete_id'];
    $deleteQuery = "DELETE FROM clients WHERE id = :id";
    $stmt = $pdo->prepare($deleteQuery);
    $stmt->execute(['id' => $client_id]);

    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Accueil - Gestion d'événements</title>
    <!-- Favicon-->
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
    <!-- Bootstrap Icons-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
    <!-- Core theme CSS (includes Bootstrap)-->
    <link href="css/styles.css" rel="stylesheet" />
    <style>
        .btn-custom {
            background-color: #D9B99B;
            color: white; /* Texte en blanc */
            border: none; /* Suppression de la bordure */
        }

        .btn-custom:hover {
            background-color: #c7a58b; /* Un peu plus foncé pour l'effet hover */
        }
    </style>
</head>
<body id="page-top">
<!-- Navigation-->
<nav class="navbar navbar-expand-lg navbar-light fixed-top py-3" id="mainNav">
    <div class="container px-4 px-lg-5">
        <a class="navbar-brand" href="#page-top"><img src="http://localhost/Events/images/logo.png" alt="Logo" style="height: 130px;" /></a>
        <button class="navbar-toggler navbar-toggler-right" type="button" data-bs-toggle="collapse" data-bs-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
            <ul class="navbar-nav ms-auto my-2 my-lg-0">
                <li class="nav-item"><a class="nav-link" href="deconnexion.php">Se déconnecter</a></li>
                <li class="nav-item"><a class="nav-link" href="#clients">Liste des clients</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- Masthead-->
<header class="masthead">
    <div class="container px-4 px-lg-5 h-100">
        <div class="row gx-4 gx-lg-5 h-100 align-items-center justify-content-center text-center">
            <div class="col-lg-8 align-self-end">
                <h1 class="text-white font-weight-bold">Bienvenue sur l'interface Events.</h1>
                <hr class="divider" />
            </div>
            <div class="col-lg-8 align-self-baseline">
                <p class="text-white-75 mb-5">Simplifions ensemble la modification et l'ajout de données.</p>
            </div>
        </div>
    </div>
</header>

<!-- Liste des clients -->
<section class="page-section" id="clients">
    <div class="container px-4 px-lg-5">
        <h2 class="text-center mt-0">Liste des Clients</h2>
        <hr class="divider" />
        <a href="#createClient" class="btn btn-custom mb-4" data-bs-toggle="collapse">Créer un Client</a>
        <div id="createClient" class="collapse">
            <form method="POST" class="mt-4">
                <div class="mb-3">
                    <label for="name" class="form-label">Nom</label>
                    <input type="text" class="form-control" name="name" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" name="email" required>
                </div>
                <div class="mb-3">
                    <label for="phone" class="form-label">Téléphone</label>
                    <input type="text" class="form-control" name="phone" required>
                </div>
                <button type="submit" name="create_client" class="btn btn-primary">Créer le client</button>
            </form>
        </div>
        <div class="row gx-4 gx-lg-5">
            <?php if (count($clients) > 0): ?>
                <?php foreach ($clients as $client): ?>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($client['name']) ?></h5>
                                <p class="card-text">
                                    <strong>Email:</strong> <?= htmlspecialchars($client['email']) ?><br>
                                    <strong>Téléphone:</strong> <?= htmlspecialchars($client['phone']) ?>
                                </p>
                                <a href="client_details.php?id=<?= $client['id'] ?>" class="btn btn-primary">Voir détails</a>
                                <a href="?delete_id=<?= $client['id'] ?>" class="btn btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce client ?')">Supprimer</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Aucun client trouvé.</p>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Footer-->
<footer class="bg-light py-5">
    <div class="container px-4 px-lg-5">
        <div class="small text-center text-muted">Copyright &copy; 2023 - Events</div>
    </div>
</footer>

<!-- Bootstrap core JS-->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- Core theme JS-->
<script src="js/scripts.js"></script>
</body>
</html>
