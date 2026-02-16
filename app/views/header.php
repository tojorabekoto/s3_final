<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BNGRC - Collecte et Distribution de Dons</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        header {
            background-color: #2c3e50;
            padding: 1rem 0;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        header .navbar-brand {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        header .navbar-brand img {
            height: 50px;
            width: auto;
        }
        header .navbar-brand span {
            color: white;
            font-weight: bold;
            font-size: 1.2rem;
        }
        header .nav-link {
            color: #ecf0f1 !important;
            font-weight: 500;
            margin: 0 0.5rem;
            transition: color 0.3s;
        }
        header .nav-link:hover {
            color: #3498db !important;
        }
    </style>
</head>
<body>
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark">
            <div class="container-fluid">
                <a class="navbar-brand" href="<?php echo '/accueil'; ?>">
                    <img src="/images/bngrc-logo.png" alt="BNGRC Logo" />
                    <span>BNGRC</span>
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo '/accueil'; ?>">Accueil</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo '/insertion_besoin'; ?>">Besoins</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo '/insertion_don'; ?>">Dons</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo '/attribution'; ?>">Attribution</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>
