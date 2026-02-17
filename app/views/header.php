<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BNGRC - Collecte et Distribution de Dons</title>
    <!-- Bootstrap 5.3.5 Local -->
    <link rel="stylesheet" href="/bootstrap-5.3.5-dist/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <header id="site-header">
        <nav class="navbar navbar-expand-lg navbar-dark">
            <div class="container-fluid">
                <a class="navbar-brand" href="<?php echo '/accueil'; ?>">
                    <img src="/images/logo.png" alt="BNGRC Logo" />
                    <span>BNGRC</span>
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="/accueil">Accueil</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/insertion_besoin">Besoins</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/insertion_don">Dons</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/achat"><i class="fas fa-shopping-cart me-1"></i>Achat</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/attribution">Attribution</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/recap"><i class="fas fa-chart-pie me-1"></i>Récap</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/vente"><i class="fas fa-store me-1"></i>Vente</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/config"><i class="fas fa-cogs me-1"></i>Admin</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>
