<?php include 'header.php'; ?>

<style>
    .ville-hero-image {
        height: 400px;
        object-fit: cover;
        width: 100%;
    }
</style>

<div class="container py-5">
    <div class="row mb-4">
        <div class="col-12">
            <a href="/accueil" class="btn btn-secondary mb-3">← Retour a l'accueil</a>
            <h1 class="mb-1">Ville: <?php echo htmlspecialchars($ville['nom_ville'] ?? 'Inconnue'); ?></h1>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-12">
            <img src="<?php echo htmlspecialchars($ville['image_path'] ?? '/images/ville-default.jpg'); ?>" class="img-fluid rounded ville-hero-image" alt="Ville">
        </div>
    </div>

    <div class="row mb-5">
        <div class="col-12">
            <h2 class="mb-3">Besoins initiaux</h2>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Categorie</th>
                            <th>Besoin</th>
                            <th>Quantite</th>
                            <th>Unite</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($besoins_materiaux)): ?>
                            <?php foreach ($besoins_materiaux as $besoin): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($besoin['categorie']); ?></td>
                                    <td><?php echo htmlspecialchars($besoin['nom_besoin']); ?></td>
                                    <td><?php echo $besoin['quantite']; ?></td>
                                    <td><?php echo htmlspecialchars($besoin['unite'] ?? ''); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        <?php if (!empty($besoins_argent)): ?>
                            <?php foreach ($besoins_argent as $besoin_argent): ?>
                                <tr>
                                    <td>Argent</td>
                                    <td>Montant necessaire</td>
                                    <td><?php echo number_format($besoin_argent['montant_necessaire'], 2, ',', ' '); ?></td>
                                    <td>Ar</td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        <?php if (empty($besoins_materiaux) && empty($besoins_argent)): ?>
                            <tr>
                                <td colspan="4" class="text-muted">Aucun besoin enregistre.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="row mb-5">
        <div class="col-12">
            <h2 class="mb-3">Dons attribues</h2>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Categorie</th>
                            <th>Besoin</th>
                            <th>Quantite donnee</th>
                            <th>Unite</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($dons_materiaux)): ?>
                            <?php foreach ($dons_materiaux as $don): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($don['categorie']); ?></td>
                                    <td><?php echo htmlspecialchars($don['nom_besoin']); ?></td>
                                    <td><?php echo $don['quantite_donnee']; ?></td>
                                    <td><?php echo htmlspecialchars($don['unite'] ?? ''); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        <?php if (!empty($dons_argent)): ?>
                            <?php foreach ($dons_argent as $don_argent): ?>
                                <tr>
                                    <td>Argent</td>
                                    <td>Montant attribue</td>
                                    <td><?php echo number_format($don_argent['montant_donne'], 2, ',', ' '); ?></td>
                                    <td>Ar</td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        <?php if (empty($dons_materiaux) && empty($dons_argent)): ?>
                            <tr>
                                <td colspan="4" class="text-muted">Aucun don attribue.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="row mb-2">
        <div class="col-12">
            <h2 class="mb-3">Restant a attribuer</h2>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Categorie</th>
                            <th>Besoin</th>
                            <th>Quantite restante</th>
                            <th>Unite</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($restant_materiaux)): ?>
                            <?php foreach ($restant_materiaux as $reste): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($reste['categorie']); ?></td>
                                    <td><?php echo htmlspecialchars($reste['nom_besoin']); ?></td>
                                    <td><?php echo $reste['quantite_restante']; ?></td>
                                    <td><?php echo htmlspecialchars($reste['unite'] ?? ''); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        <?php if (!empty($restant_argent)): ?>
                            <?php foreach ($restant_argent as $reste_argent): ?>
                                <tr>
                                    <td>Argent</td>
                                    <td>Montant restant</td>
                                    <td><?php echo number_format($reste_argent['montant_restant'], 2, ',', ' '); ?></td>
                                    <td>Ar</td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        <?php if (empty($restant_materiaux) && empty($restant_argent)): ?>
                            <tr>
                                <td colspan="4" class="text-muted">Aucun restant a afficher.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
