<?php include 'header.php'; ?>

<main class="container my-5">
    <section class="row mb-4">
        <header class="col-12">
            <a href="/accueil" class="btn btn-outline-secondary mb-3">
                <i class="fas fa-arrow-left me-1"></i>Retour à l'accueil
            </a>
            <h1 class="mb-2"><i class="fas fa-shopping-cart me-2"></i>Acheter des matériaux</h1>
            <p class="lead">Sélectionnez les matériaux à acheter avec l'argent disponible dans l'inventaire</p>
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

    <form id="achatForm" method="POST" action="/achat">
        <!-- Sélection région / ville -->
        <section class="row mb-4">
            <article class="col-md-4">
                <label for="regionSelect" class="form-label fw-bold">Région</label>
                <select id="regionSelect" class="form-select" required>
                    <option value="">-- Choisir une région --</option>
                    <?php foreach ($regions as $region): ?>
                        <option value="<?php echo $region['id_region']; ?>">
                            <?php echo htmlspecialchars($region['nom_region']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </article>
            <article class="col-md-4">
                <label for="villeSelect" class="form-label fw-bold">Ville</label>
                <select id="villeSelect" name="id_ville" class="form-select" required disabled>
                    <option value="">-- Choisir une ville --</option>
                    <?php foreach ($villes as $ville): ?>
                        <option value="<?php echo $ville['id_ville']; ?>" data-region="<?php echo $ville['id_region']; ?>">
                            <?php echo htmlspecialchars($ville['nom_ville']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </article>
            <article class="col-md-4 align-self-end">
                <button type="button" id="loadInventaire" class="btn btn-info w-100">
                    <i class="fas fa-warehouse me-1"></i>Charger inventaire
                </button>
            </article>
        </section>

        <!-- Inventaire -->
        <section class="row mb-4" id="inventaireDisplay" style="display:none;">
            <article class="col-12">
                <div class="card border-success">
                    <header class="card-header bg-success bg-opacity-10">
                        <h2 class="mb-0 text-success"><i class="fas fa-wallet me-2"></i>Ressources disponibles</h2>
                    </header>
                    <div class="card-body" id="inventaireContent">
                        <span class="text-muted">Chargement...</span>
                    </div>
                </div>
            </article>
        </section>

        <!-- Tableau matériaux -->
        <section class="row mb-4">
            <article class="col-12">
                <fieldset class="card">
                    <header class="card-header">
                        <h2 class="mb-0"><i class="fas fa-boxes me-2"></i>Matériaux à acheter</h2>
                    </header>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th style="width:50px">Sel.</th>
                                        <th>Catégorie</th>
                                        <th>Matériau (besoin)</th>
                                        <th style="width:130px">Qté à acheter</th>
                                        <th>Prix unitaire</th>
                                        <th>Sous-total</th>
                                    </tr>
                                </thead>
                                <tbody id="materiauxTable">
                                    <tr><td colspan="6" class="text-muted text-center py-3">Sélectionnez une ville puis chargez l'inventaire.</td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </fieldset>
            </article>
        </section>

        <!-- Résumé paiement -->
        <section class="row mb-4">
            <article class="col-12">
                <div class="card border-primary">
                    <div class="card-body">
                        <h3 class="card-title"><i class="fas fa-calculator me-2"></i>Résumé du paiement</h3>
                        <div class="row">
                            <div class="col-md-4">
                                <strong>Articles sélectionnés :</strong>
                                <span id="countArticles" class="badge bg-secondary fs-6">0</span>
                            </div>
                            <div class="col-md-4">
                                <strong>Coût total :</strong>
                                <span id="totalCost" class="fw-bold fs-5" style="color:#ff6b35;">0 Ar</span>
                            </div>
                            <div class="col-md-4">
                                <strong>Argent disponible :</strong>
                                <span id="argentDispo" class="badge bg-success fs-6">0 Ar</span>
                            </div>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="mode_paiement" value="argent">
            </article>
        </section>

        <!-- Boutons -->
        <section class="row">
            <article class="col-12 d-flex gap-2">
                <button type="submit" class="btn btn-primary btn-lg" id="submitBtn" disabled>
                    <i class="fas fa-shopping-cart me-2"></i>Confirmer l'achat
                </button>
                <a href="/accueil" class="btn btn-outline-secondary btn-lg">Annuler</a>
            </article>
        </section>

        <div id="materiauxInputs"></div>
    </form>

    <!-- Modal succès -->
    <?php if ($show_success_modal): ?>
    <div class="modal fade show" id="successModal" tabindex="-1" style="display:block; background-color:rgba(0,0,0,0.5);">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="background:var(--secondary-color,#ff6b35); color:white;">
                    <h5 class="modal-title"><i class="fas fa-check-circle me-2"></i>Achat confirmé !</h5>
                </div>
                <div class="modal-body" style="max-height:400px; overflow-y:auto;">
                    <h6 class="mb-3">Détails de l'achat :</h6>
                    <div class="table-responsive">
                        <table class="table table-sm table-striped">
                            <thead class="table-light">
                                <tr>
                                    <th>Matériau</th>
                                    <th>Quantité achetée</th>
                                    <th>Besoin avant</th>
                                    <th>Besoin après</th>
                                    <th>Prix total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($achats_details as $achat): ?>
                                <tr>
                                    <td>
                                        <strong><?php echo htmlspecialchars($achat['nom_besoin']); ?></strong>
                                        <br><small class="text-muted"><?php echo htmlspecialchars($achat['categorie']); ?></small>
                                    </td>
                                    <td><?php echo $achat['quantite_achetee']; ?> <?php echo htmlspecialchars($achat['unite']); ?></td>
                                    <td><span class="badge bg-warning"><?php echo $achat['quantite_avant']; ?></span></td>
                                    <td><span class="badge bg-info"><?php echo max(0, $achat['quantite_apres']); ?></span></td>
                                    <td><?php echo number_format($achat['prix_total'], 0, ',', ' '); ?> Ar</td>
                                </tr>
                                <?php endforeach; ?>
                                <tr class="table-success fw-bold">
                                    <td colspan="4" class="text-end">Coût total :</td>
                                    <td><?php echo number_format($prix_total, 0, ',', ' '); ?> Ar</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="/achat" class="btn btn-primary"><i class="fas fa-shopping-cart me-1"></i>Continuer</a>
                    <a href="/ville-detail/<?php echo $id_ville; ?>" class="btn btn-success"><i class="fas fa-city me-1"></i>Voir la ville</a>
                    <a href="/accueil" class="btn btn-outline-secondary"><i class="fas fa-home me-1"></i>Accueil</a>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
</main>

<?php include 'footer.php'; ?>

<script>
const regionEl    = document.getElementById('regionSelect');
const villeEl     = document.getElementById('villeSelect');
const loadBtn     = document.getElementById('loadInventaire');
const matTable    = document.getElementById('materiauxTable');
const countEl     = document.getElementById('countArticles');
const totalEl     = document.getElementById('totalCost');
const argentEl    = document.getElementById('argentDispo');
const submitBtn   = document.getElementById('submitBtn');
const invDisplay  = document.getElementById('inventaireDisplay');
const invContent  = document.getElementById('inventaireContent');

let inventaireData = {};

// Cache villes
const villesData = Array.from(villeEl.options).filter(o => o.value).map(o => ({
    id: o.value, name: o.textContent.trim(), region: o.dataset.region
}));

// Filtrage villes
regionEl.addEventListener('change', () => {
    const rid = regionEl.value;
    villeEl.innerHTML = '<option value="">-- Choisir une ville --</option>';
    villeEl.disabled = !rid;
    if (rid) {
        villesData.filter(v => v.region === rid).forEach(v => {
            const o = document.createElement('option');
            o.value = v.id; o.textContent = v.name;
            villeEl.appendChild(o);
        });
    }
    matTable.innerHTML = '<tr><td colspan="6" class="text-muted text-center py-3">Sélectionnez une ville.</td></tr>';
    invDisplay.style.display = 'none';
    submitBtn.disabled = true;
});

// Charger inventaire + besoins
loadBtn.addEventListener('click', () => {
    const villeId = villeEl.value;
    if (!villeId) { alert('Veuillez sélectionner une ville.'); return; }

    invContent.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Chargement...';
    invDisplay.style.display = 'block';
    matTable.innerHTML = '<tr><td colspan="6" class="text-center py-3"><i class="fas fa-spinner fa-spin me-1"></i>Chargement...</td></tr>';

    fetch('/api/inventaire/' + encodeURIComponent(villeId))
        .then(r => r.json())
        .then(data => {
            inventaireData = data;
            updateInventaireDisplay();
            loadBesoins(villeId);
        })
        .catch(err => {
            console.error(err);
            invContent.innerHTML = '<span class="text-danger">Erreur de chargement.</span>';
        });
});

function updateInventaireDisplay() {
    const argent = inventaireData.argent;
    let html = '';
    if (argent) {
        const m = Number(argent.montant_disponible).toLocaleString('fr-FR');
        html += `<div class="mb-2"><i class="fas fa-coins me-2 text-warning"></i><strong>Argent disponible :</strong> <span class="badge bg-success fs-6">${m} Ar</span></div>`;
        argentEl.textContent = m + ' Ar';
    } else {
        html += '<div class="text-warning"><i class="fas fa-exclamation-triangle me-1"></i>Aucun inventaire argent trouvé.</div>';
        argentEl.textContent = '0 Ar';
    }
    const mats = inventaireData.materiaux || [];
    if (mats.length > 0) {
        html += '<div class="mt-2"><strong>Matériaux en stock :</strong></div><div class="mt-1">';
        mats.forEach(m => {
            html += `<span class="badge bg-info me-1 mb-1">${m.nom_besoin}: ${m.quantite_disponible} ${m.unite || ''}</span>`;
        });
        html += '</div>';
    }
    invContent.innerHTML = html;
}

function loadBesoins(villeId) {
    fetch('/api/besoins-by-ville/' + encodeURIComponent(villeId))
        .then(r => r.json())
        .then(data => {
            const besoins = data.materiaux || [];
            if (besoins.length === 0) {
                matTable.innerHTML = '<tr><td colspan="6" class="text-muted text-center py-3">Aucun besoin matériel pour cette ville.</td></tr>';
                return;
            }

            let html = '';
            besoins.forEach(b => {
                const prix = Number(b.prix_unitaire || 0);
                html += `
                <tr>
                    <td>
                        <input type="checkbox" class="form-check-input mat-check"
                               data-id="${b.id_besoin}" data-prix="${prix}">
                    </td>
                    <td><span class="badge bg-secondary">${b.categorie || ''}</span></td>
                    <td>
                        <strong>${b.nom_besoin || ''}</strong>
                        <br><small class="text-muted">Besoin: ${b.quantite} ${b.unite || ''}</small>
                    </td>
                    <td>
                        <input type="number" class="form-control form-control-sm qte-input"
                               data-id="${b.id_besoin}" min="0" step="0.1" value="0"
                               style="width:110px;" disabled>
                    </td>
                    <td>${prix.toLocaleString('fr-FR')} Ar</td>
                    <td class="sous-total" data-id="${b.id_besoin}">0 Ar</td>
                </tr>`;
            });

            matTable.innerHTML = html;

            // Events checkboxes
            document.querySelectorAll('.mat-check').forEach(cb => {
                cb.addEventListener('change', e => {
                    const inp = document.querySelector('.qte-input[data-id="' + e.target.dataset.id + '"]');
                    inp.disabled = !e.target.checked;
                    if (!e.target.checked) inp.value = 0;
                    updateTotals();
                });
            });

            // Events quantités
            document.querySelectorAll('.qte-input').forEach(inp => {
                inp.addEventListener('input', updateTotals);
            });
        });
}

function updateTotals() {
    let count = 0, total = 0;

    document.querySelectorAll('.mat-check:checked').forEach(cb => {
        const id = cb.dataset.id;
        const inp = document.querySelector('.qte-input[data-id="' + id + '"]');
        const qte = parseFloat(inp.value) || 0;
        const prix = parseFloat(cb.dataset.prix) || 0;
        const st = qte * prix;

        if (qte > 0) { count++; total += st; }
        document.querySelector('.sous-total[data-id="' + id + '"]').textContent =
            st > 0 ? st.toLocaleString('fr-FR') + ' Ar' : '0 Ar';
    });

    // Reset unchecked
    document.querySelectorAll('.mat-check:not(:checked)').forEach(cb => {
        document.querySelector('.sous-total[data-id="' + cb.dataset.id + '"]').textContent = '0 Ar';
    });

    countEl.textContent = count;
    totalEl.textContent = total.toLocaleString('fr-FR') + ' Ar';

    const argentDisp = inventaireData.argent ? parseFloat(inventaireData.argent.montant_disponible) : 0;
    const canSubmit = count > 0 && total > 0 && total <= argentDisp;
    submitBtn.disabled = !canSubmit;

    if (total > argentDisp && count > 0) {
        totalEl.style.color = '#dc3545';
    } else {
        totalEl.style.color = '#ff6b35';
    }

    updateHiddenInputs();
}

function updateHiddenInputs() {
    const container = document.getElementById('materiauxInputs');
    container.innerHTML = '';
    document.querySelectorAll('.mat-check:checked').forEach(cb => {
        const id = cb.dataset.id;
        const inp = document.querySelector('.qte-input[data-id="' + id + '"]');
        const qte = parseFloat(inp.value) || 0;
        if (qte > 0) {
            const h = document.createElement('input');
            h.type = 'hidden'; h.name = 'materiaux[' + id + ']'; h.value = qte;
            container.appendChild(h);
        }
    });
}
</script>
