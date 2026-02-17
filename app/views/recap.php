<?php include 'header.php'; ?>

<main class="container my-5">
    <section class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <h1 class="mb-1"><i class="fas fa-chart-pie me-2"></i>Récapitulation générale</h1>
                <p class="text-muted mb-0">Vue d'ensemble des besoins, dons et achats en montant (Ar)</p>
            </div>
            <div class="d-flex gap-2">
                <button id="refreshBtn" class="btn btn-secondary btn-lg" onclick="refreshData()">
                    <i class="fas fa-sync-alt me-1"></i>Actualiser
                </button>
            </div>
        </div>
    </section>

    <!-- Taux de satisfaction -->
    <section class="row mb-4" id="satisfactionSection">
        <article class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center py-4">
                    <h2 class="mb-3">Taux de satisfaction global</h2>
                    <div class="d-inline-block position-relative" style="width:160px; height:160px;">
                        <svg viewBox="0 0 36 36" style="width:160px; height:160px; transform:rotate(-90deg);">
                            <circle cx="18" cy="18" r="15.9" fill="none" stroke="#e9ecef" stroke-width="3"></circle>
                            <circle id="progressCircle" cx="18" cy="18" r="15.9" fill="none"
                                    stroke="var(--secondary-color, #ff6b35)" stroke-width="3"
                                    stroke-dasharray="0, 100" stroke-linecap="round"></circle>
                        </svg>
                        <span id="tauxText" class="position-absolute top-50 start-50 translate-middle fw-bold"
                              style="font-size:1.8rem; color:var(--secondary-color,#ff6b35);">
                            <?php echo $taux_satisfaction; ?>%
                        </span>
                    </div>
                    <p class="text-muted mt-2 mb-0">Besoins couverts par les dons et achats</p>
                </div>
            </div>
        </article>
    </section>

    <!-- Besoins -->
    <section class="row mb-4">
        <article class="col-md-6 mb-3">
            <div class="card h-100 border-start border-4" style="border-color:var(--primary-color,#0a1929) !important;">
                <div class="card-body">
                    <h3 class="card-title"><i class="fas fa-clipboard-list me-2 text-primary"></i>Besoins totaux</h3>
                    <div class="table-responsive">
                        <table class="table table-sm mb-0">
                            <tbody>
                                <tr>
                                    <td>Besoins matériaux</td>
                                    <td class="text-end fw-bold" id="besoinsMatMontant">
                                        <?php echo number_format($besoins_materiaux_montant, 0, ',', ' '); ?> Ar
                                    </td>
                                </tr>
                                <tr>
                                    <td>Besoins en argent</td>
                                    <td class="text-end fw-bold" id="besoinsArgMontant">
                                        <?php echo number_format($besoins_argent_montant, 0, ',', ' '); ?> Ar
                                    </td>
                                </tr>
                                <tr class="table-dark">
                                    <td class="fw-bold">TOTAL BESOINS</td>
                                    <td class="text-end fw-bold" id="besoinsTotal">
                                        <?php echo number_format($besoins_total, 0, ',', ' '); ?> Ar
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </article>

        <article class="col-md-6 mb-3">
            <div class="card h-100 border-start border-4" style="border-color:var(--success,#28a745) !important;">
                <div class="card-body">
                    <h3 class="card-title"><i class="fas fa-check-double me-2 text-success"></i>Besoins satisfaits</h3>
                    <div class="table-responsive">
                        <table class="table table-sm mb-0">
                            <tbody>
                                <tr>
                                    <td>Dons matériaux attribués</td>
                                    <td class="text-end fw-bold" id="satisfaitsDonsMat">
                                        <?php echo number_format($satisfaits_dons_mat, 0, ',', ' '); ?> Ar
                                    </td>
                                </tr>
                                <tr>
                                    <td>Achats matériaux</td>
                                    <td class="text-end fw-bold" id="satisfaitsAchatsMat">
                                        <?php echo number_format($satisfaits_achats_mat, 0, ',', ' '); ?> Ar
                                    </td>
                                </tr>
                                <tr>
                                    <td>Dons argent attribués</td>
                                    <td class="text-end fw-bold" id="satisfaitsArgent">
                                        <?php echo number_format($satisfaits_argent, 0, ',', ' '); ?> Ar
                                    </td>
                                </tr>
                                <tr class="table-success">
                                    <td class="fw-bold">TOTAL SATISFAITS</td>
                                    <td class="text-end fw-bold" id="satisfaitsTotal">
                                        <?php echo number_format($satisfaits_total, 0, ',', ' '); ?> Ar
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </article>
    </section>

    <!-- Dons -->
    <section class="row mb-4">
        <article class="col-md-6 mb-3">
            <div class="card h-100 border-start border-4" style="border-color:var(--info,#17a2b8) !important;">
                <div class="card-body">
                    <h3 class="card-title"><i class="fas fa-hand-holding-heart me-2 text-info"></i>Dons reçus</h3>
                    <div class="table-responsive">
                        <table class="table table-sm mb-0">
                            <tbody>
                                <tr>
                                    <td>Stock matériaux (valorisé)</td>
                                    <td class="text-end fw-bold" id="donsStockMat">
                                        <?php echo number_format($dons_stock_mat, 0, ',', ' '); ?> Ar
                                    </td>
                                </tr>
                                <tr>
                                    <td>Stock argent</td>
                                    <td class="text-end fw-bold" id="donsStockArgent">
                                        <?php echo number_format($dons_stock_argent, 0, ',', ' '); ?> Ar
                                    </td>
                                </tr>
                                <tr class="table-info">
                                    <td class="fw-bold">TOTAL DONS REÇUS</td>
                                    <td class="text-end fw-bold" id="donsRecusTotal">
                                        <?php echo number_format($dons_recus_total, 0, ',', ' '); ?> Ar
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </article>

        <article class="col-md-6 mb-3">
            <div class="card h-100 border-start border-4" style="border-color:var(--secondary-color,#ff6b35) !important;">
                <div class="card-body">
                    <h3 class="card-title"><i class="fas fa-share-alt me-2" style="color:var(--secondary-color);"></i>Dons dispatchés</h3>
                    <div class="table-responsive">
                        <table class="table table-sm mb-0">
                            <tbody>
                                <tr>
                                    <td>Matériaux attribués aux besoins</td>
                                    <td class="text-end fw-bold" id="dispatchesMat">
                                        <?php echo number_format($dispatches_mat, 0, ',', ' '); ?> Ar
                                    </td>
                                </tr>
                                <tr>
                                    <td>Argent attribué aux besoins</td>
                                    <td class="text-end fw-bold" id="dispatchesArgent">
                                        <?php echo number_format($dispatches_argent, 0, ',', ' '); ?> Ar
                                    </td>
                                </tr>
                                <tr>
                                    <td>Achats effectués</td>
                                    <td class="text-end fw-bold" id="dispatchesAchats">
                                        <?php echo number_format($dispatches_achats, 0, ',', ' '); ?> Ar
                                    </td>
                                </tr>
                                <tr class="table-warning">
                                    <td class="fw-bold">TOTAL DISPATCHÉS</td>
                                    <td class="text-end fw-bold" id="dispatchesTotal">
                                        <?php echo number_format($dispatches_total, 0, ',', ' '); ?> Ar
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </article>
    </section>

    <!-- Barre visuelle -->
    <section class="row mb-4">
        <article class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h3 class="card-title mb-3"><i class="fas fa-chart-bar me-2"></i>Comparaison visuelle</h3>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <small class="fw-bold">Besoins totaux</small>
                            <small id="barBesoinsLabel"><?php echo number_format($besoins_total, 0, ',', ' '); ?> Ar</small>
                        </div>
                        <div class="progress" style="height:24px;">
                            <div class="progress-bar" role="progressbar" style="width:100%; background:var(--primary-color);" id="barBesoins">100%</div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <small class="fw-bold">Satisfaits</small>
                            <small id="barSatisfaitsLabel"><?php echo number_format($satisfaits_total, 0, ',', ' '); ?> Ar</small>
                        </div>
                        <div class="progress" style="height:24px;">
                            <?php $pctSat = $besoins_total > 0 ? round(($satisfaits_total / $besoins_total) * 100) : 0; ?>
                            <div class="progress-bar bg-success" role="progressbar" style="width:<?php echo $pctSat; ?>%;" id="barSatisfaits"><?php echo $pctSat; ?>%</div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <small class="fw-bold">Dons reçus</small>
                            <small id="barDonsLabel"><?php echo number_format($dons_recus_total, 0, ',', ' '); ?> Ar</small>
                        </div>
                        <div class="progress" style="height:24px;">
                            <?php $pctDons = $besoins_total > 0 ? min(round(($dons_recus_total / $besoins_total) * 100), 100) : 0; ?>
                            <div class="progress-bar" role="progressbar" style="width:<?php echo $pctDons; ?>%; background:var(--info);" id="barDons"><?php echo $pctDons; ?>%</div>
                        </div>
                    </div>
                    <div>
                        <div class="d-flex justify-content-between mb-1">
                            <small class="fw-bold">Dispatchés</small>
                            <small id="barDispatchesLabel"><?php echo number_format($dispatches_total, 0, ',', ' '); ?> Ar</small>
                        </div>
                        <div class="progress" style="height:24px;">
                            <?php $pctDisp = $besoins_total > 0 ? min(round(($dispatches_total / $besoins_total) * 100), 100) : 0; ?>
                            <div class="progress-bar" role="progressbar" style="width:<?php echo $pctDisp; ?>%; background:var(--secondary-color);" id="barDispatches"><?php echo $pctDisp; ?>%</div>
                        </div>
                    </div>
                </div>
            </div>
        </article>
    </section>
</main>

<?php include 'footer.php'; ?>

<script>
function fmt(n) {
    return Number(n).toLocaleString('fr-FR') + ' Ar';
}

function refreshData() {
    const btn = document.getElementById('refreshBtn');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Chargement...';

    fetch('/api/recap')
        .then(r => r.json())
        .then(d => {
            // Besoins
            document.getElementById('besoinsMatMontant').textContent   = fmt(d.besoins_materiaux_montant);
            document.getElementById('besoinsArgMontant').textContent   = fmt(d.besoins_argent_montant);
            document.getElementById('besoinsTotal').textContent        = fmt(d.besoins_total);

            // Satisfaits
            document.getElementById('satisfaitsDonsMat').textContent   = fmt(d.satisfaits_dons_mat);
            document.getElementById('satisfaitsAchatsMat').textContent = fmt(d.satisfaits_achats_mat);
            document.getElementById('satisfaitsArgent').textContent    = fmt(d.satisfaits_argent);
            document.getElementById('satisfaitsTotal').textContent     = fmt(d.satisfaits_total);

            // Dons reçus
            document.getElementById('donsStockMat').textContent        = fmt(d.dons_stock_mat);
            document.getElementById('donsStockArgent').textContent     = fmt(d.dons_stock_argent);
            document.getElementById('donsRecusTotal').textContent      = fmt(d.dons_recus_total);

            // Dispatchés
            document.getElementById('dispatchesMat').textContent       = fmt(d.dispatches_mat);
            document.getElementById('dispatchesArgent').textContent    = fmt(d.dispatches_argent);
            document.getElementById('dispatchesAchats').textContent    = fmt(d.dispatches_achats);
            document.getElementById('dispatchesTotal').textContent     = fmt(d.dispatches_total);

            // Taux + cercle
            const taux = d.taux_satisfaction;
            document.getElementById('tauxText').textContent = taux + '%';
            document.getElementById('progressCircle').setAttribute('stroke-dasharray', taux + ', 100');

            // Barres
            const bt = d.besoins_total || 1;
            const pctSat  = Math.round((d.satisfaits_total / bt) * 100);
            const pctDons = Math.min(Math.round((d.dons_recus_total / bt) * 100), 100);
            const pctDisp = Math.min(Math.round((d.dispatches_total / bt) * 100), 100);

            document.getElementById('barBesoinsLabel').textContent    = fmt(d.besoins_total);
            document.getElementById('barSatisfaitsLabel').textContent = fmt(d.satisfaits_total);
            document.getElementById('barDonsLabel').textContent       = fmt(d.dons_recus_total);
            document.getElementById('barDispatchesLabel').textContent = fmt(d.dispatches_total);

            document.getElementById('barSatisfaits').style.width  = pctSat + '%';
            document.getElementById('barSatisfaits').textContent  = pctSat + '%';
            document.getElementById('barDons').style.width        = pctDons + '%';
            document.getElementById('barDons').textContent        = pctDons + '%';
            document.getElementById('barDispatches').style.width  = pctDisp + '%';
            document.getElementById('barDispatches').textContent  = pctDisp + '%';

            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-sync-alt me-1"></i>Actualiser';
        })
        .catch(err => {
            console.error(err);
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-sync-alt me-1"></i>Actualiser';
            alert('Erreur lors du chargement des données.');
        });
}

// Initialiser le cercle SVG
document.addEventListener('DOMContentLoaded', () => {
    const taux = <?php echo $taux_satisfaction; ?>;
    document.getElementById('progressCircle').setAttribute('stroke-dasharray', taux + ', 100');
});

// ─── Reset ───────────────────────────────────
document.getElementById('btnReset')?.addEventListener('click', function() {
    const confirmText = prompt('⚠️ Cette action va supprimer toutes les données et restaurer l\'état initial.\n\nTapez RESET pour confirmer :');
    if (confirmText !== 'RESET') return;

    const btn = this;
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Réinitialisation...';

    fetch('/api/reset', { method: 'POST', headers: {'Content-Type': 'application/json'} })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                alert('✅ Base de données réinitialisée avec succès !');
                window.location.reload();
            } else {
                alert('❌ Erreur : ' + (data.error || 'Échec'));
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-undo me-1"></i>Réinitialiser';
            }
        })
        .catch(err => {
            alert('❌ Erreur réseau : ' + err.message);
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-undo me-1"></i>Réinitialiser';
        });
});
</script>
