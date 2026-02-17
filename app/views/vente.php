<?php include 'header.php'; ?>

<main class="container my-5">
    <section class="row mb-4">
        <header class="col-12">
            <a href="/accueil" class="btn btn-outline-secondary mb-3">
                <i class="fas fa-arrow-left me-1"></i>Retour à l'accueil
            </a>
            <h1 class="mb-2"><i class="fas fa-store me-2"></i>Vente de dons</h1>
            <p class="lead">Vendre les dons matériels qui ne figurent pas dans la liste des besoins</p>
        </header>
    </section>

    <?php if (!empty($message) && !$show_success_modal): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i><?php echo htmlspecialchars($message); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    <?php if (!empty($error)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i><?php echo htmlspecialchars($error); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Configuration du pourcentage -->
    <section class="row mb-4">
        <article class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-cog me-2 text-secondary"></i>Pourcentage de réduction</h5>
                    <div class="input-group">
                        <input type="number" id="pourcentageInput" class="form-control" 
                               value="<?php echo $pourcentage; ?>" min="0" max="100" step="1">
                        <span class="input-group-text">%</span>
                        <button type="button" id="btnSavePourcentage" class="btn btn-outline-primary">
                            <i class="fas fa-save me-1"></i>Enregistrer
                        </button>
                    </div>
                    <small class="text-muted mt-1 d-block">
                        Le prix de vente sera réduit de ce pourcentage par rapport au prix original.
                    </small>
                </div>
            </div>
        </article>
        <article class="col-md-6">
            <div class="card border-0 shadow-sm bg-light">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-info-circle me-2 text-info"></i>Comment ça marche ?</h5>
                    <ul class="mb-0 small">
                        <li>Seuls les produits en stock <strong>absents de la liste des besoins</strong> peuvent être vendus</li>
                        <li>Le prix est réduit de <strong><span id="pourcentageDisplay"><?php echo $pourcentage; ?></span>%</strong> par rapport au prix catalogue</li>
                        <li>L'argent de la vente est ajouté au <strong>stock global d'argent</strong> de la société</li>
                    </ul>
                </div>
            </div>
        </article>
    </section>

    <!-- Formulaire de vente -->
    <?php if (empty($stock_vendable)): ?>
        <div class="alert alert-info">
            <i class="fas fa-info-circle me-2"></i>Aucun produit vendable pour le moment. 
            Tous les produits en stock correspondent à des besoins existants.
        </div>
    <?php else: ?>
        <section class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-tags me-2"></i>Produits vendables</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th>Produit</th>
                                <th>Stock dispo</th>
                                <th>Unité</th>
                                <th>Prix original</th>
                                <th>Réduction</th>
                                <th>Prix vente</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($stock_vendable as $item): 
                                $prix_original = (float)$item['prix_unitaire'];
                                $prix_vente = round($prix_original * (1 - $pourcentage / 100), 2);
                            ?>
                            <tr data-id-produit="<?php echo $item['id_produit']; ?>"
                                data-prix-original="<?php echo $prix_original; ?>"
                                data-stock="<?php echo $item['quantite_disponible']; ?>">
                                <td class="fw-bold"><?php echo htmlspecialchars($item['nom_produit']); ?></td>
                                <td><?php echo number_format($item['quantite_disponible'], 0, ',', ' '); ?></td>
                                <td><?php echo htmlspecialchars($item['unite_standard']); ?></td>
                                <td><?php echo number_format($prix_original, 0, ',', ' '); ?> Ar</td>
                                <td><span class="badge bg-warning text-dark"><?php echo $pourcentage; ?>%</span></td>
                                <td class="text-success fw-bold prix-vente-cell">
                                    <?php echo $prix_original > 0 ? number_format($prix_vente, 0, ',', ' ') . ' Ar' : '<span class="text-muted">N/A</span>'; ?>
                                </td>
                                <td>
                                    <?php if ($prix_original > 0): ?>
                                        <button type="button" class="btn btn-sm btn-outline-success btn-vendre"
                                                data-id="<?php echo $item['id_produit']; ?>"
                                                data-nom="<?php echo htmlspecialchars($item['nom_produit']); ?>"
                                                data-stock="<?php echo $item['quantite_disponible']; ?>"
                                                data-prix="<?php echo $prix_vente; ?>">
                                            <i class="fas fa-hand-holding-usd me-1"></i>Vendre
                                        </button>
                                    <?php else: ?>
                                        <span class="text-muted small">Prix non défini</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <!-- Historique des ventes -->
    <?php if (!empty($historique_ventes)): ?>
    <section class="card border-0 shadow-sm mb-4">
        <div class="card-header" style="background: var(--primary-color); color: white;">
            <h5 class="mb-0"><i class="fas fa-history me-2"></i>Historique des ventes</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-sm mb-0">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Produit</th>
                            <th>Qté</th>
                            <th>Prix orig.</th>
                            <th>Réduction</th>
                            <th>Prix vente</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($historique_ventes as $v): ?>
                        <tr>
                            <td class="small"><?php echo date('d/m/Y H:i', strtotime($v['date_vente'])); ?></td>
                            <td><?php echo htmlspecialchars($v['nom_produit']); ?></td>
                            <td><?php echo number_format($v['quantite_vendue'], 0, ',', ' '); ?> <?php echo $v['unite_standard']; ?></td>
                            <td><?php echo number_format($v['prix_unitaire_original'], 0, ',', ' '); ?> Ar</td>
                            <td><span class="badge bg-warning text-dark"><?php echo $v['pourcentage_reduction']; ?>%</span></td>
                            <td><?php echo number_format($v['prix_unitaire_vente'], 0, ',', ' '); ?> Ar</td>
                            <td class="fw-bold text-success"><?php echo number_format($v['montant_total'], 0, ',', ' '); ?> Ar</td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
    <?php endif; ?>
</main>

<!-- Modal de vente -->
<div class="modal fade" id="venteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title"><i class="fas fa-store me-2"></i>Vendre un produit</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="venteForm" method="POST" action="/vente">
                <div class="modal-body">
                    <input type="hidden" name="id_produit" id="venteIdProduit">
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Produit</label>
                        <input type="text" id="venteNomProduit" class="form-control" readonly>
                    </div>

                    <div class="mb-3">
                        <label for="venteQuantite" class="form-label fw-bold">Quantité à vendre</label>
                        <input type="number" name="quantite" id="venteQuantite" class="form-control" 
                               min="1" step="1" required>
                        <small class="text-muted">Stock disponible : <span id="venteStockDispo">0</span></small>
                    </div>

                    <div class="card bg-light">
                        <div class="card-body py-2">
                            <div class="d-flex justify-content-between">
                                <span>Prix unitaire vente :</span>
                                <strong id="ventePrixUnitaire">0 Ar</strong>
                            </div>
                            <div class="d-flex justify-content-between mt-1">
                                <span>Montant total estimé :</span>
                                <strong class="text-success fs-5" id="venteMontantTotal">0 Ar</strong>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check me-1"></i>Confirmer la vente
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal succès -->
<?php if ($show_success_modal && $vente_result): ?>
<div class="modal fade" id="successModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title"><i class="fas fa-check-circle me-2"></i>Vente réussie !</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <table class="table table-sm">
                    <tr>
                        <td class="fw-bold">Produit</td>
                        <td><?php echo htmlspecialchars($vente_result['nom_produit']); ?></td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Quantité vendue</td>
                        <td><?php echo number_format($vente_result['quantite_vendue'], 0, ',', ' '); ?></td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Prix original</td>
                        <td><?php echo number_format($vente_result['prix_original'], 0, ',', ' '); ?> Ar</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Réduction</td>
                        <td><span class="badge bg-warning text-dark"><?php echo $vente_result['pourcentage_reduction']; ?>%</span></td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Prix vente unitaire</td>
                        <td><?php echo number_format($vente_result['prix_vente'], 0, ',', ' '); ?> Ar</td>
                    </tr>
                    <tr class="table-success">
                        <td class="fw-bold fs-5">Montant total</td>
                        <td class="fw-bold fs-5 text-success"><?php echo number_format($vente_result['montant_total'], 0, ',', ' '); ?> Ar</td>
                    </tr>
                </table>
                <div class="alert alert-info mb-0">
                    <i class="fas fa-info-circle me-2"></i>Ce montant a été ajouté au stock global d'argent de la société.
                </div>
            </div>
            <div class="modal-footer">
                <a href="/vente" class="btn btn-primary">
                    <i class="fas fa-redo me-1"></i>Nouvelle vente
                </a>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<?php include 'footer.php'; ?>

<script>
// ─── Pourcentage save ────────────────────────
document.getElementById('btnSavePourcentage')?.addEventListener('click', function() {
    const val = document.getElementById('pourcentageInput').value;
    fetch('/api/config', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: 'cle=pourcentage_reduction_vente&valeur=' + encodeURIComponent(val)
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            document.getElementById('pourcentageDisplay').textContent = val;
            // Recalculer les prix affichés
            document.querySelectorAll('tbody tr[data-id-produit]').forEach(row => {
                const prixOrig = parseFloat(row.dataset.prixOriginal);
                const prixVente = Math.round(prixOrig * (1 - val / 100));
                const cell = row.querySelector('.prix-vente-cell');
                if (prixOrig > 0) {
                    cell.innerHTML = new Intl.NumberFormat('fr-FR').format(prixVente) + ' Ar';
                }
                // Mettre à jour le data-prix du bouton
                const btn = row.querySelector('.btn-vendre');
                if (btn) btn.dataset.prix = prixVente;
            });
            // Toast de confirmation
            showToast('Pourcentage mis à jour : ' + val + '%', 'success');
        } else {
            showToast('Erreur : ' + (data.error || 'Échec'), 'danger');
        }
    });
});

// ─── Modal vente ─────────────────────────────
document.querySelectorAll('.btn-vendre').forEach(btn => {
    btn.addEventListener('click', function() {
        const id = this.dataset.id;
        const nom = this.dataset.nom;
        const stock = parseFloat(this.dataset.stock);
        const prixVente = parseFloat(this.dataset.prix);

        document.getElementById('venteIdProduit').value = id;
        document.getElementById('venteNomProduit').value = nom;
        document.getElementById('venteStockDispo').textContent = stock;
        document.getElementById('venteQuantite').max = stock;
        document.getElementById('venteQuantite').value = '';
        document.getElementById('ventePrixUnitaire').textContent = 
            new Intl.NumberFormat('fr-FR').format(prixVente) + ' Ar';
        document.getElementById('venteMontantTotal').textContent = '0 Ar';

        new bootstrap.Modal(document.getElementById('venteModal')).show();
    });
});

// Calcul montant en temps réel
document.getElementById('venteQuantite')?.addEventListener('input', function() {
    const qte = parseFloat(this.value) || 0;
    const prixText = document.getElementById('ventePrixUnitaire').textContent;
    const prix = parseFloat(prixText.replace(/[^\d]/g, '')) || 0;
    const total = qte * prix;
    document.getElementById('venteMontantTotal').textContent = 
        new Intl.NumberFormat('fr-FR').format(total) + ' Ar';
});

// ─── Success modal auto-show ─────────────────
<?php if ($show_success_modal && $vente_result): ?>
document.addEventListener('DOMContentLoaded', function() {
    new bootstrap.Modal(document.getElementById('successModal')).show();
});
<?php endif; ?>

// ─── Toast helper ────────────────────────────
function showToast(message, type) {
    const toast = document.createElement('div');
    toast.className = `alert alert-${type} position-fixed bottom-0 end-0 m-3 shadow`;
    toast.style.zIndex = '9999';
    toast.innerHTML = message;
    document.body.appendChild(toast);
    setTimeout(() => toast.remove(), 3000);
}
</script>
