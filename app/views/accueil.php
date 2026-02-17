<?php include 'header.php'; ?>

<style>
    .hero-section {
        background: linear-gradient(rgba(10, 25, 41, 0.7), rgba(26, 47, 69, 0.7)), 
                    url('/images/accueil.jpg') center/cover no-repeat;
        height: 500px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        text-align: center;
    }
    .hero-section header {
        background: none;
        padding: 0;
    }
    .hero-section h1 {
        font-size: 3rem;
        font-weight: bold;
        margin-bottom: 1rem;
        color: #ff6b35;
        text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.8);
        background: none;
    }
    .hero-section p {
        text-shadow: 1px 1px 4px rgba(0, 0, 0, 0.8);
        background: none;
    }
    .dashboard {
        margin: 3rem 0;
    }
    .dashboard h2 {
        background: none !important;
        border: none !important;
        padding-left: 0 !important;
    }
    .dashboard header {
        background: none !important;
    }
    .dashboard-card {
        background: white;
        border-radius: 12px;
        padding: 2rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        text-align: center;
        border-left: 5px solid #ff6b35;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .dashboard-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(255, 107, 53, 0.3);
    }
    .dashboard-card h5 {
        color: #0a1929;
        font-size: 0.9rem;
        margin-bottom: 0.5rem;
        font-weight: 600;
    }
    .dashboard-card .number {
        font-size: 2.5rem;
        font-weight: bold;
        color: #ff6b35;
        display: block;
    }
    .card-img-top {
        height: 250px;
        object-fit: cover;
        width: 100%;
    }
    figure.card {
        margin: 0;
    }
</style>

<section class="hero-section">
    <header>
        <h1>Bienvenue sur le site de collecte et distribution de dons du BNGRC</h1>
        <p class="fs-5">Ensemble pour aider les sinistrés de la région</p>
    </header>
</section>

<main class="container dashboard">
    <section class="row">
        <article class="col-md-3 mb-4">
            <div class="dashboard-card">
                <h5>Villes</h5>
                <data class="number" value="<?php echo isset($nb_villes) ? $nb_villes : 0; ?>"><?php echo isset($nb_villes) ? $nb_villes : 0; ?></data>
                <p>villes couvertes</p>
            </div>
        </article>
        <article class="col-md-3 mb-4">
            <div class="dashboard-card">
                <h5>Sinistres</h5>
                <data class="number" value="<?php echo isset($nb_sinistres) ? $nb_sinistres : 0; ?>"><?php echo isset($nb_sinistres) ? $nb_sinistres : 0; ?></data>
                <p>personnes sinistrées</p>
            </div>
        </article>
        <article class="col-md-3 mb-4">
            <div class="dashboard-card">
                <h5>Besoins Matériaux</h5>
                <data class="number" value="<?php echo isset($nb_besoins_mat) ? $nb_besoins_mat : 0; ?>"><?php echo isset($nb_besoins_mat) ? $nb_besoins_mat : 0; ?></data>
                <p>besoins enregistrés</p>
            </div>
        </article>
        <article class="col-md-3 mb-4">
            <div class="dashboard-card">
                <h5>Besoins en Argent</h5>
                <data class="number" value="<?php echo isset($nb_besoins_argent) ? $nb_besoins_argent : 0; ?>"><?php echo isset($nb_besoins_argent) ? $nb_besoins_argent : 0; ?></data>
                <p>demandes financières</p>
            </div>
        </article>
    </section>

    <?php if (!empty($achats_par_ville)): ?>
    <section class="row mt-5">
        <header class="col-12">
            <h2 class="mb-4"><i class="fas fa-shopping-cart me-2" style="color:#ff6b35;"></i>Achats par ville</h2>
        </header>
        <article class="col-12">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Ville</th>
                            <th class="text-end">Total des achats</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $grand_total = 0; ?>
                        <?php foreach ($achats_par_ville as $achat): ?>
                            <tr>
                                <td><i class="fas fa-city me-1 text-muted"></i><?php echo htmlspecialchars($achat['nom_ville']); ?></td>
                                <td class="text-end fw-bold" style="color:#ff6b35;">
                                    <?php echo number_format($achat['total_achats'], 0, ',', ' '); ?> Ar
                                </td>
                            </tr>
                            <?php $grand_total += $achat['total_achats']; ?>
                        <?php endforeach; ?>
                        <tr class="table-success">
                            <td class="fw-bold">Total général</td>
                            <td class="text-end fw-bold"><?php echo number_format($grand_total, 0, ',', ' '); ?> Ar</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </article>
    </section>
    <?php endif; ?>

    <section class="row mt-5">
        <header class="col-12">
            <h2 class="mb-4">Villes concernées</h2>
        </header>
        <?php if (!empty($villes)): ?>
            <?php foreach ($villes as $ville): ?>
                <article class="col-md-4 mb-4">
                    <a class="text-decoration-none" href="/ville-detail/<?php echo $ville['id_ville']; ?>">
                        <figure class="card h-100">
                            <img src="<?php echo htmlspecialchars($ville['image_path'] ?? '/images/ville-default.jpg'); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($ville['nom_ville']); ?>">
                            <figcaption class="card-body">
                                <h3 class="card-title mb-0"><?php echo htmlspecialchars($ville['nom_ville']); ?></h3>
                            </figcaption>
                        </figure>
                    </a>
                </article>
            <?php endforeach; ?>
        <?php else: ?>
            <aside class="col-12">
                <p class="text-muted">Aucune ville disponible pour le moment.</p>
            </aside>
        <?php endif; ?>
    </section>
</main>

<?php include 'footer.php'; ?>