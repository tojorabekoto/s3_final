<?php include 'header.php'; ?>

<style>
    .hero-section {
        background: linear-gradient(rgba(44, 62, 80, 0.7), rgba(44, 62, 80, 0.7)), 
                    url('/images/hero-background.jpg') center/cover no-repeat;
        height: 500px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        text-align: center;
    }
    .hero-section h1 {
        font-size: 3rem;
        font-weight: bold;
        margin-bottom: 1rem;
    }
    .dashboard {
        margin: 3rem 0;
    }
    .dashboard-card {
        background: white;
        border-radius: 8px;
        padding: 2rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        text-align: center;
        border-left: 5px solid #3498db;
    }
    .dashboard-card h5 {
        color: #7f8c8d;
        font-size: 0.9rem;
        margin-bottom: 0.5rem;
    }
    .dashboard-card .number {
        font-size: 2.5rem;
        font-weight: bold;
        color: #2c3e50;
    }
</style>

<section class="hero-section">
    <div>
        <h1>Bienvenue sur le site de collecte et distribution de dons du BNGRC</h1>
        <p class="fs-5">Ensemble pour aider les sinistrés de la région</p>
    </div>
</section>

<main class="container dashboard">
    <div class="row">
        <div class="col-md-3 mb-4">
            <div class="dashboard-card">
                <h5>Villes</h5>
                <div class="number"><?php echo $nb_villes ?? 0; ?></div>
                <p>villes couvertes</p>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="dashboard-card">
                <h5>Besoins Initiaux</h5>
                <div class="number"><?php echo $nb_sinistres ?? 0; ?></div>
                <p>personnes sinistrées</p>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="dashboard-card">
                <h5>Dons Actuels</h5>
                <div class="number"><?php echo $nb_besoins_mat ?? 0; ?></div>
                <p>besoins matériaux</p>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="dashboard-card">
                <h5>Aide Monétaire</h5>
                <div class="number"><?php echo $nb_besoins_argent ?? 0; ?></div>
                <p>besoins en argent</p>
            </div>
        </div>
    </div>

    <div class="row mt-5">
        <div class="col-12">
            <div class="alert alert-info" role="alert">
                <h4 class="alert-heading">Aide d'urgence disponible</h4>
                <p>Les sinistrés des régions affectées peuvent recevoir une assistance immédiate. 
                   Consultez la section <strong>Besoins</strong> pour les demandes spécifiques par ville.</p>
            </div>
        </div>
    </div>

    <div class="row mt-5">
        <div class="col-12">
            <h3>Catégories de Besoins</h3>
            <div class="row">
                <?php if(!empty($categories)): ?>
                    <?php foreach($categories as $cat): ?>
                    <div class="col-md-6 mb-3">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($cat['nom_categorie'] ?? $cat['name'] ?? 'N/A'); ?></h5>
                                <p class="card-text" style="color: #7f8c8d;">
                                    <?php echo htmlspecialchars($cat['description'] ?? ''); ?>
                                </p>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12">
                        <p class="text-muted">Aucune catégorie disponible</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</main>

<?php include 'footer.php'; ?>
