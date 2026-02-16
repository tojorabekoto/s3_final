<?php include 'header.php'; ?>

<style>
<<<<<<< HEAD
    .besoin-item {
        background-color: #f8f9fa;
        padding: 15px;
        margin-bottom: 10px;
        border-left: 4px solid #3498db;
        border-radius: 4px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .besoin-item-info {
        flex: 1;
    }
    .besoin-item-info strong {
        display: block;
        color: #2c3e50;
    }
    .besoin-item-info small {
        color: #7f8c8d;
    }
    .btn-delete-don {
        margin-left: 15px;
    }
</style>

<div class="container py-5">
    <div class="row mb-4">
        <div class="col-12">
            <h1>Faites des dons</h1>
            <p class="text-muted">Ajoutez plusieurs dons et validez en une seule fois.</p>
        </div>
    </div>

    <?php if (!empty($message)): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>
    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <div class="row">
        <!-- Formulaire d'ajout de don -->
        <div class="col-lg-6">
            <div class="card p-4 mb-4">
                <h5 class="card-title mb-4">Ajouter un don</h5>

                <!-- Région / Ville -->
                <div class="row mb-3">
                    <div class="col-12">
                        <label class="form-label">Région</label>
                        <select id="regionSelect" class="form-select" required>
                            <option value="">-- Choisir une région --</option>
                            <?php foreach ($regions as $region): ?>
                                <option value="<?php echo $region['id_region']; ?>"><?php echo htmlspecialchars($region['nom_region']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-12">
                        <label class="form-label">Ville</label>
                        <select id="villeSelect" class="form-select" required>
                            <option value="">-- Choisir une ville --</option>
                            <?php foreach ($villes as $ville): ?>
                                <option value="<?php echo $ville['id_ville']; ?>" data-region="<?php echo (int)$ville['id_region']; ?>">
                                    <?php echo htmlspecialchars($ville['nom_ville']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <!-- Type de don -->
                <div class="row mb-3">
                    <div class="col-12">
                        <label class="form-label">Catégorie de don</label>
                        <select id="typeSelect" class="form-select" required>
                            <option value="">-- Choisir une catégorie --</option>
                            <option value="naturels">Naturels (riz, huile, sucre, ...)</option>
                            <option value="materiaux">Matériaux (tôle, clou, ...)</option>
                            <option value="argent">Argent</option>
                        </select>
                    </div>
                </div>

                <!-- Besoin spécifique -->
                <div id="besoinContainer" class="row mb-3" style="display:none;">
                    <div class="col-12">
                        <label class="form-label" id="besoinLabel">Besoin matériel</label>
                        <select id="besoinSelect" class="form-select" required>
                            <option value="">-- Choisir un besoin --</option>
                        </select>
                        <small id="uniteInfo" class="d-block mt-2 text-muted"></small>
                    </div>
                </div>

                <!-- Quantité -->
                <div class="row mb-4">
                    <div class="col-12">
                        <label class="form-label">Quantité</label>
                        <div class="input-group">
                            <input type="number" step="0.01" min="0" id="quantiteInput" class="form-control" required>
                            <span class="input-group-text" id="uniteUnit">-</span>
                        </div>
                    </div>
                </div>

                <button type="button" id="addDonBtn" class="btn btn-success w-100">
                    <i class="fas fa-plus"></i> Ajouter ce don
                </button>
            </div>
=======
    .don-card { margin-top: 2rem; }
    .besoin-link { cursor: pointer; }
    .selected-besoin { margin-top: 1rem; }
</style>

<main class="container don-card">
    <h2 class="mt-4">Donner pour un besoin</h2>

    <?php // Regions/villes and besoins are loaded via AJAX from controller APIs ?>

    <form id="donForm" class="row g-3">
        <div class="col-md-4">
            <label class="form-label">Région</label>
            <select id="region" class="form-select">
                <option value="">-- Choisir une région --</option>
            </select>
        </div>

        <div class="col-md-4">
            <label class="form-label">Ville</label>
            <select id="ville" class="form-select" disabled>
                <option value="">-- Choisir une ville --</option>
            </select>
        </div>

        <div class="col-md-4 align-self-end">
            <button type="button" id="refreshTable" class="btn btn-primary">Afficher besoins</button>
        </div>
    </form>

    <div class="mt-4">
        <h5>Besoins disponibles</h5>
        <div class="table-responsive">
            <table class="table table-striped" id="besoinsTable">
                <thead>
                    <tr>
                        <th style="width:48px">Sel</th>
                        <th>Catégorie</th>
                        <th>Détail</th>
                        <th>Voir</th>
                    </tr>
                </thead>
                <tbody>
                    <tr><td colspan="4" class="text-muted">Sélectionnez une région et une ville.</td></tr>
                </tbody>
            </table>
        </div>
    </div>

    <div id="donArea" class="selected-besoin">
        <h5>Saisir détails pour besoins sélectionnés</h5>
        <div id="dynamicFields" class="mt-3"></div>
        <div class="mt-3">
            <button type="button" id="validateBtn" class="btn btn-primary">Valider (affichage seulement)</button>
>>>>>>> 1373c5e36260b53407f49f22e14bb9cf77be62a1
        </div>
    </div>

<<<<<<< HEAD
        <!-- Liste des dons -->
        <div class="col-lg-6">
            <div class="card p-4">
                <h5 class="card-title mb-3">Dons à enregistrer</h5>
                <div id="donsList" class="mb-4">
                    <p class="text-muted" id="emptyMessage">Aucun don ajouté pour le moment</p>
                </div>
                <form id="submitForm" method="POST" action="/insertion_don" style="display:none;">
                    <input type="hidden" id="donsData" name="dons_json">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-check"></i> Valider et enregistrer tous les dons
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    const regionSelect = document.getElementById('regionSelect');
    const villeSelect = document.getElementById('villeSelect');
    const typeSelect = document.getElementById('typeSelect');
    const besoinSelect = document.getElementById('besoinSelect');
    const besoinContainer = document.getElementById('besoinContainer');
    const besoinLabel = document.getElementById('besoinLabel');
    const quantiteInput = document.getElementById('quantiteInput');
    const uniteInfo = document.getElementById('uniteInfo');
    const uniteUnit = document.getElementById('uniteUnit');
    const addDonBtn = document.getElementById('addDonBtn');
    const donsList = document.getElementById('donsList');
    const emptyMessage = document.getElementById('emptyMessage');
    const submitForm = document.getElementById('submitForm');
    const donsData = document.getElementById('donsData');

    const villesData = Array.from(villeSelect.options)
        .filter(option => option.value)
        .map(option => ({
            id: option.value,
            name: option.textContent.trim(),
            region: option.dataset.region
        }));

    // Données brutes
    const besoinsMateriaux = <?php echo json_encode($besoins_materiaux); ?>;
    const besoinsArgent = <?php echo json_encode($besoins_argent); ?>;

    // Stockage des dons
    let donsArray = [];

    function renderVilles(regionId) {
        villeSelect.innerHTML = '<option value="">-- Choisir une ville --</option>';
        villesData
            .filter(ville => !regionId || ville.region === regionId)
            .forEach(ville => {
                const option = document.createElement('option');
                option.value = ville.id;
                option.textContent = ville.name;
                option.dataset.region = ville.region;
                villeSelect.appendChild(option);
            });
    }

    // Filtrer villes par région
    regionSelect.addEventListener('change', () => {
        const regionId = regionSelect.value;
        renderVilles(regionId);
        besoinSelect.innerHTML = '<option value="">-- Choisir un besoin --</option>';
        quantiteInput.value = '';
        updateBesoins();
    });

    // Filtrer besoins par ville et type
    villeSelect.addEventListener('change', updateBesoins);
    typeSelect.addEventListener('change', updateBesoins);

    function updateBesoins() {
        const ville = villeSelect.value;
        const type = typeSelect.value;
        
        besoinSelect.innerHTML = '<option value="">-- Choisir un besoin --</option>';
        quantiteInput.value = '';
        uniteUnit.textContent = '-';
        uniteInfo.textContent = '';
        
        if (!ville || !type) {
            besoinContainer.style.display = 'none';
            return;
        }
        
        besoinContainer.style.display = 'block';

        if (type === 'naturels' || type === 'materiaux') {
            besoinLabel.textContent = 'Besoin matériel';
            const filtered = besoinsMateriaux.filter(b => {
                const villeMatch = b.id_ville == ville;
                const categorie = String(b.categorie || '').toLowerCase();
                const typeMatch = (type === 'naturels' && (categorie === 'nature' || categorie === 'naturel' || categorie === 'naturels')) ||
                                 (type === 'materiaux' && (categorie === 'materiaux' || categorie === 'materiel' || categorie === 'materiaux'));
                return villeMatch && typeMatch;
            });
            
            if (filtered.length === 0) {
                besoinSelect.innerHTML = '<option value="">Aucun besoin matériel trouvé</option>';
                return;
            }
            
            filtered.forEach(b => {
                const option = document.createElement('option');
                option.value = JSON.stringify({
                    id_besoin: b.id_besoin,
                    nom: b.nom_besoin,
                    categorie: b.categorie,
                    unite: b.unite || '-'
                });
                option.textContent = `${b.nom_besoin} (${b.categorie})`;
                besoinSelect.appendChild(option);
            });
        } else if (type === 'argent') {
            besoinLabel.textContent = 'Montant nécessaire';
            const filtered = besoinsArgent.filter(b => b.id_ville == ville);
            
            if (filtered.length === 0) {
                besoinSelect.innerHTML = '<option value="">Aucun besoin en argent trouvé</option>';
                return;
            }
            
            filtered.forEach(b => {
                const option = document.createElement('option');
                option.value = JSON.stringify({
                    id_besoin_argent: b.id_besoin_argent,
                    montant: b.montant_necessaire
                });
                option.textContent = `${numberFormat(b.montant_necessaire)} Ar`;
                besoinSelect.appendChild(option);
            });
            
            uniteUnit.textContent = 'Ar';
        }
    }

    // Mettre à jour l'unité quand on change le besoin
    besoinSelect.addEventListener('change', () => {
        if (!besoinSelect.value) return;
        
        try {
            const besoin = JSON.parse(besoinSelect.value);
            if (besoin.unite) {
                uniteUnit.textContent = besoin.unite;
                uniteInfo.textContent = `Unité: ${besoin.unite}`;
            }
        } catch (e) {}
    });

    // Ajouter un don
    addDonBtn.addEventListener('click', () => {
        const region = regionSelect.value;
        const regionText = regionSelect.options[regionSelect.selectedIndex].text;
        const ville = villeSelect.value;
        const villeText = villeSelect.options[villeSelect.selectedIndex].text;
        const type = typeSelect.value;
        const besoin = besoinSelect.value;
        const quantite = parseFloat(quantiteInput.value);

        if (!region || !ville || !type || !besoin || !quantite) {
            alert('Veuillez remplir tous les champs');
            return;
        }

        try {
            const besoinObj = JSON.parse(besoin);
            const id = Date.now();
            
            const don = {
                id: id,
                region: regionText,
                ville: villeText,
                type: type,
                besoin: besoinObj,
                quantite: quantite,
                unite: uniteUnit.textContent
            };

            donsArray.push(don);
            renderDonsList();
            
            // Reset formulaire
            regionSelect.value = '';
            villeSelect.value = '';
            typeSelect.value = '';
            besoinSelect.innerHTML = '<option value="">-- Choisir un besoin --</option>';
            quantiteInput.value = '';
            besoinContainer.style.display = 'none';
        } catch (e) {
            alert('Erreur lors de l\'ajout du don');
        }
    });

    // Afficher la liste des dons
    function renderDonsList() {
        if (donsArray.length === 0) {
            donsList.innerHTML = '<p class="text-muted">Aucun don ajouté pour le moment</p>';
            submitForm.style.display = 'none';
            return;
        }

        donsList.innerHTML = donsArray.map(don => `
            <div class="besoin-item">
                <div class="besoin-item-info">
                    <strong>${htmlEscape(don.besoin.nom || don.besoin.id_besoin_argent)}</strong>
                    <small>${htmlEscape(don.ville)} - ${htmlEscape(don.region)}</small>
                    <br>
                    <small><strong>${don.quantite} ${don.unite}</strong></small>
                </div>
                <button type="button" class="btn btn-sm btn-danger btn-delete-don" data-id="${don.id}">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        `).join('');

        // Event listeners pour les boutons supprimer
        donsList.querySelectorAll('.btn-delete-don').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                const id = parseInt(btn.dataset.id);
                donsArray = donsArray.filter(d => d.id !== id);
                renderDonsList();
            });
        });

        submitForm.style.display = 'block';
        donsData.value = JSON.stringify(donsArray);
    }

    // Helpers
    function htmlEscape(str) {
        const map = {'&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;'};
        return str.replace(/[&<>"']/g, m => map[m]);
    }

    function numberFormat(num) {
        return new Intl.NumberFormat('fr-FR', { minimumFractionDigits: 0, maximumFractionDigits: 2 }).format(num);
    }

    // Submit du formulaire
    submitForm.addEventListener('submit', (e) => {
        if (donsArray.length === 0) {
            e.preventDefault();
            alert('Aucun don à enregistrer');
        }
    });

    renderVilles('');
</script>

<?php include 'footer.php'; ?>

=======
</main>

<?php include 'footer.php'; ?>

<script>
    const regionEl = document.getElementById('region');
    const villeEl = document.getElementById('ville');
    const tableBody = document.querySelector('#besoinsTable tbody');
    const refreshBtn = document.getElementById('refreshTable');
    const donArea = document.getElementById('donArea');
    const dynamicFields = document.getElementById('dynamicFields');
    const validateBtn = document.getElementById('validateBtn');

    // track selected besoins by an index key
    const selected = {};

    regionEl.addEventListener('change', () => {
        const id = regionEl.value;
        villeEl.innerHTML = '<option value="">-- Choisir une ville --</option>';
        villeEl.disabled = true;
        if (!id) { clearTableMessage(); return; }
        fetch('/api/villes/' + encodeURIComponent(id))
            .then(r => r.json())
            .then(villes => {
                villes.forEach(v => {
                    const opt = document.createElement('option');
                    opt.value = v.id_ville; opt.textContent = v.nom_ville;
                    villeEl.appendChild(opt);
                });
                villeEl.disabled = false;
                clearTableMessage();
            }).catch(err => { console.error(err); clearTableMessage(); });
    });

    function fetchRegions() {
        fetch('/api/regions')
            .then(r => r.json())
            .then(regions => {
                regions.forEach(reg => {
                    const opt = document.createElement('option');
                    opt.value = reg.id_region; opt.textContent = reg.nom_region;
                    regionEl.appendChild(opt);
                });
            })
            .catch(err => console.error('Erreur chargement régions', err));
    }

    // load regions on page load
    fetchRegions();

    refreshBtn.addEventListener('click', () => {
        const r = regionEl.value; const v = villeEl.value;
        tableBody.innerHTML = '';
        if (!r || !v) {
            tableBody.innerHTML = '<tr><td colspan="4" class="text-muted">Veuillez sélectionner une région et une ville.</td></tr>';
            return;
        }
        // fetch besoins from API
        fetch('/api/besoins-by-ville/' + encodeURIComponent(v))
            .then(rsp => rsp.json())
            .then(payload => {
                const materiaux = payload.materiaux || [];
                const argent = payload.argent || [];
                const merged = [];
                materiaux.forEach(m => merged.push({ type: 'materiaux', id: m.id_besoin, cat: m.categorie || 'Matériaux', detail: m.nom_besoin }));
                argent.forEach(a => merged.push({ type: 'argent', id: a.id_besoin_argent, cat: 'Argent', detail: 'Montant nécessaire: ' + a.montant_necessaire }));
                if (merged.length === 0) {
                    tableBody.innerHTML = '<tr><td colspan="4" class="text-muted">Aucun besoin déclaré pour cette ville.</td></tr>';
                    return;
                }
                // populate table
                tableBody.innerHTML = '';
                merged.forEach((b, idx) => {
                    const tr = document.createElement('tr');
                    const tdSel = document.createElement('td');
                    const cb = document.createElement('input');
                    const key = b.type + '-' + b.id;
                    cb.type = 'checkbox'; cb.className = 'besoin-cb'; cb.dataset.idx = key;
                    cb.addEventListener('change', (e) => { toggleSelection(key, b, e.target.checked); });
                    tdSel.appendChild(cb);

                    const tdCat = document.createElement('td'); tdCat.textContent = b.cat;
                    const tdDetail = document.createElement('td'); tdDetail.textContent = b.detail;
                    const tdVoir = document.createElement('td');
                    const a = document.createElement('a');
                    a.href = '#'; a.className = 'besoin-link'; a.textContent = 'Voir / compléter';
                    a.addEventListener('click', (e) => { e.preventDefault();
                        tdSel.querySelector('input').checked = true;
                        toggleSelection(key, b, true);
                        const f = document.getElementById('field-' + key);
                        if (f) f.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    });
                    tdVoir.appendChild(a);

                    tr.appendChild(tdSel);
                    tr.appendChild(tdCat); tr.appendChild(tdDetail); tr.appendChild(tdVoir);
                    tableBody.appendChild(tr);
                });
            })
            .catch(err => {
                tableBody.innerHTML = '<tr><td colspan="4" class="text-muted">Erreur lors du chargement des besoins.</td></tr>';
                console.error(err);
            });
    });

    function toggleSelection(idx, besoin, checked) {
        if (checked) {
            if (selected[idx]) return; // already added
            selected[idx] = besoin;
            addField(idx, besoin);
        } else {
            delete selected[idx];
            removeField(idx);
        }
        // show/hide donArea depending on selections
        donArea.style.display = Object.keys(selected).length ? 'block' : 'none';
    }

    function addField(idx, besoin) {
        // create container for inputs for this besoin
        const wrapper = document.createElement('div');
        wrapper.className = 'card p-3 mb-3';
        wrapper.id = 'field-' + idx;

        const title = document.createElement('div');
        title.innerHTML = '<strong>' + besoin.cat + '</strong> — ' + besoin.detail;

        const formRow = document.createElement('div');
        formRow.className = 'row g-3 mt-2';

        const col1 = document.createElement('div');
        col1.className = 'col-md-6';

        if (besoin.cat.toLowerCase() === 'argent') {
            const lbl = document.createElement('label'); lbl.className = 'form-label'; lbl.textContent = 'Montant (MGA)';
            const inp = document.createElement('input'); inp.type = 'number'; inp.className = 'form-control'; inp.min = 1; inp.dataset.idx = idx; inp.name = 'montant-' + idx;
            col1.appendChild(lbl); col1.appendChild(inp);
        } else {
            const lbl = document.createElement('label'); lbl.className = 'form-label'; lbl.textContent = 'Quantité / Remarques';
            const inp = document.createElement('input'); inp.type = 'text'; inp.className = 'form-control'; inp.placeholder = 'Ex: 10 unités, 5 lots...'; inp.dataset.idx = idx; inp.name = 'detail-' + idx;
            col1.appendChild(lbl); col1.appendChild(inp);
        }

        const col2 = document.createElement('div');
        col2.className = 'col-md-6';
        const lbl2 = document.createElement('label'); lbl2.className = 'form-label'; lbl2.textContent = 'Remarques (optionnel)';
        const ta = document.createElement('textarea'); ta.className = 'form-control'; ta.rows = 2; ta.name = 'remarks-' + idx; ta.dataset.idx = idx;
        col2.appendChild(lbl2); col2.appendChild(ta);

        const removeBtnCol = document.createElement('div'); removeBtnCol.className = 'col-12 text-end';
        const removeBtn = document.createElement('button'); removeBtn.type = 'button'; removeBtn.className = 'btn btn-sm btn-outline-danger'; removeBtn.textContent = 'Retirer';
        removeBtn.addEventListener('click', () => {
            // uncheck the corresponding checkbox in the table
            const cb = document.querySelector('input.besoin-cb[data-idx="' + idx + '"]');
            if (cb) cb.checked = false;
            toggleSelection(idx, besoin, false);
        });
        removeBtnCol.appendChild(removeBtn);

        formRow.appendChild(col1); formRow.appendChild(col2); formRow.appendChild(removeBtnCol);

        wrapper.appendChild(title); wrapper.appendChild(formRow);
        dynamicFields.appendChild(wrapper);
    }

    function removeField(idx) {
        const el = document.getElementById('field-' + idx);
        if (el) el.remove();
    }

    validateBtn.addEventListener('click', () => {
        const region = regionEl.value; const ville = villeEl.value;
        if (!region || !ville) { alert('Veuillez sélectionner région et ville.'); return; }
        const result = { region, ville, selections: [] };
        Object.keys(selected).forEach(idx => {
            const b = selected[idx];
            const entry = { cat: b.cat, detail: b.detail };
            const montoEl = document.querySelector('[name="montant-' + idx + '"]');
            const detailEl = document.querySelector('[name="detail-' + idx + '"]');
            const remarksEl = document.querySelector('[name="remarks-' + idx + '"]');
            if (montoEl) entry.montant = montoEl.value || null;
            if (detailEl) entry.quantite = detailEl.value || null;
            entry.remarks = remarksEl ? remarksEl.value || null : null;
            result.selections.push(entry);
        });

        // display-only: show JSON result
        alert('Données à valider (affichage seulement):\n' + JSON.stringify(result, null, 2));
    });

    function clearTableMessage() {
        tableBody.innerHTML = '<tr><td colspan="4" class="text-muted">Sélectionnez une région et une ville.</td></tr>';
        donArea.style.display = 'none';
    }
</script>
>>>>>>> 1373c5e36260b53407f49f22e14bb9cf77be62a1
