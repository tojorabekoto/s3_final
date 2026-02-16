<?php include 'header.php'; ?>

<style>
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
        </div>
    </div>

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
