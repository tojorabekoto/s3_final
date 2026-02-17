<?php include 'header.php'; ?>

<main class="container my-5">
    <section class="row mb-4">
        <header class="col-12">
            <?php
                $retourUrl   = (!empty($id_ville)) ? '/ville-detail/' . (int)$id_ville : '/accueil';
                $retourLabel = (!empty($ville_nom)) ? '← Retour à ' . htmlspecialchars($ville_nom) : '← Retour à l\'accueil';
            ?>
            <a href="<?php echo $retourUrl; ?>" class="btn btn-outline-secondary mb-3">
                <i class="fas fa-arrow-left me-1"></i>
                <?php echo $retourLabel; ?>
            </a>
            <h1 class="mb-2">Insertion des besoins
                <?php if (!empty($ville_nom)): ?>
                    <small class="text-muted fs-5">— <?php echo htmlspecialchars($ville_nom); ?></small>
                <?php endif; ?>
            </h1>
            <p class="lead">Enregistrez les besoins en matériaux ou en argent pour une ville sinistrée</p>
        </header>
    </section>

    <?php if (!empty($message)): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            <?php echo htmlspecialchars($message); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    <?php if (!empty($error)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>
            <?php echo htmlspecialchars($error); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <section class="row justify-content-center">
        <article class="col-lg-8">
            <fieldset class="card shadow-sm">
                <header class="card-header">
                    <h2 class="mb-0"><i class="fas fa-clipboard-list me-2"></i>Enregistrer un nouveau besoin</h2>
                </header>
                <div class="card-body p-4">
                    <form method="POST" action="/insertion_besoin" class="needs-validation" novalidate>

                        <!-- id_ville caché si on vient d'une page ville -->
                        <?php if (!empty($id_ville)): ?>
                            <input type="hidden" name="id_ville" value="<?php echo (int)$id_ville; ?>">
                        <?php endif; ?>

                        <!-- Région / Ville -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="regionSelect" class="form-label">Région</label>
                                <select name="id_region" id="regionSelect" class="form-select" <?php echo !empty($id_ville) ? 'disabled' : ''; ?>>
                                    <option value="">-- Choisir une région --</option>
                                    <?php foreach ($regions as $region): ?>
                                        <option value="<?php echo $region['id_region']; ?>"
                                            <?php echo (isset($id_region_selected) && $id_region_selected == $region['id_region']) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($region['nom_region']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="villeSelect" class="form-label">Ville <span class="text-danger">*</span></label>
                                <?php if (!empty($id_ville) && !empty($ville_nom)): ?>
                                    <input type="text" class="form-control bg-light" value="<?php echo htmlspecialchars($ville_nom); ?>" readonly>
                                <?php else: ?>
                                    <select name="id_ville" id="villeSelect" class="form-select" required>
                                        <option value="">-- Choisir une ville --</option>
                                        <?php foreach ($villes as $ville): ?>
                                            <option value="<?php echo $ville['id_ville']; ?>" data-region="<?php echo $ville['id_region']; ?>">
                                                <?php echo htmlspecialchars($ville['nom_ville']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Type de besoin -->
                        <div class="mb-3">
                            <label for="typeBesoinSelect" class="form-label">Type de besoin <span class="text-danger">*</span></label>
                            <select name="type_besoin" id="typeBesoinSelect" class="form-select" required>
                                <option value="">-- Choisir un type --</option>
                                <option value="naturels"  <?php echo (($type_besoin_sel ?? '') === 'naturels')  ? 'selected' : ''; ?>>Naturels (riz, huile, sucre, ...)</option>
                                <option value="materiaux" <?php echo (($type_besoin_sel ?? '') === 'materiaux') ? 'selected' : ''; ?>>Matériaux (tôle, clou, ciment, ...)</option>
                                <option value="argent"    <?php echo (($type_besoin_sel ?? '') === 'argent')    ? 'selected' : ''; ?>>Argent</option>
                            </select>
                        </div>

                        <!-- Bloc matériel / naturels -->
                        <div id="besoinMaterielBlock" class="<?php echo in_array($type_besoin_sel ?? '', ['naturels','materiaux']) ? '' : 'd-none'; ?>">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="categorieSelect" class="form-label">Catégorie <span class="text-danger">*</span></label>
                                    <select name="id_categorie" id="categorieSelect" class="form-select">
                                        <option value="">-- Choisir une catégorie --</option>
                                        <?php foreach ($categories as $categorie): ?>
                                            <?php
                                                $nom_cat = $categorie['nom_categorie'] ?? ($categorie['nom'] ?? ($categorie['categorie'] ?? ''));
                                                $id_cat  = $categorie['id_categorie']  ?? ($categorie['id']  ?? 0);
                                            ?>
                                            <?php if ($nom_cat && $id_cat): ?>
                                                <option value="<?php echo $id_cat; ?>"
                                                        data-nom="<?php echo strtolower($nom_cat); ?>"
                                                        <?php echo (($id_cat_sel ?? 0) == $id_cat) ? 'selected' : ''; ?>>
                                                    <?php echo htmlspecialchars($nom_cat); ?>
                                                </option>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="nomBesoinInput" class="form-label">Nom du besoin <span class="text-danger">*</span></label>
                                    <input type="text" name="nom_besoin" id="nomBesoinInput" class="form-control" list="produitsDatalist"
                                           placeholder="Ex: Riz, Pastèque, Huile..."
                                           value="<?php echo htmlspecialchars($nom_besoin_sel ?? ''); ?>">
                                    <datalist id="produitsDatalist"></datalist>
                                    <small class="text-muted" id="nomBesoinHint">Tapez un nom existant ou créez-en un nouveau</small>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label class="form-label">Quantité nécessaire <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" min="0.01" name="quantite" class="form-control"
                                           placeholder="Ex: 100"
                                           value="<?php echo htmlspecialchars($quantite_sel ?? ''); ?>">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Unité</label>
                                    <input type="text" name="unite" id="uniteInput" class="form-control"
                                           placeholder="Ex: kg, sac, pièce..."
                                           value="<?php echo htmlspecialchars($unite_sel ?? ''); ?>">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Prix unitaire (Ar)</label>
                                    <input type="number" step="0.01" min="0" name="prix_unitaire" id="prixUnitaireInput" class="form-control"
                                           placeholder="Ex: 3000">
                                    <small class="text-muted" id="prixHint"></small>
                                </div>
                            </div>
                        </div>

                        <!-- Bloc argent -->
                        <div id="besoinArgentBlock" class="<?php echo (($type_besoin_sel ?? '') === 'argent') ? '' : 'd-none'; ?>">
                            <div id="sinistreContainer">
                                <?php if (!empty($sinistres)): ?>
                                    <div class="mb-3">
                                        <label for="sinistreSelect" class="form-label">Sinistre concerné <span class="text-danger">*</span></label>
                                        <select name="id_sinistre" id="sinistreSelect" class="form-select">
                                            <option value="">-- Choisir un sinistre --</option>
                                            <?php foreach ($sinistres as $sinistre): ?>
                                                <option value="<?php echo $sinistre['id_sinistre']; ?>"
                                                    <?php echo (($id_sinistre_sel ?? 0) == $sinistre['id_sinistre']) ? 'selected' : ''; ?>>
                                                    <?php
                                                        $label = $sinistre['type_sinistre'] ?? ($sinistre['nom_sinistre'] ?? ('Sinistre #' . $sinistre['id_sinistre']));
                                                        if (!empty($sinistre['date_sinistre'])) $label .= ' — ' . $sinistre['date_sinistre'];
                                                        echo htmlspecialchars($label);
                                                    ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                <?php elseif (($type_besoin_sel ?? '') === 'argent'): ?>
                                    <div class="alert alert-warning py-2">
                                        <i class="fas fa-exclamation-triangle me-1"></i>
                                        Aucun sinistre trouvé pour cette ville. Veuillez d'abord créer un sinistre.
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Montant nécessaire (Ar) <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" min="0.01" name="montant" class="form-control"
                                       placeholder="Ex: 500000"
                                       value="<?php echo htmlspecialchars($montant_sel ?? ''); ?>">
                            </div>
                        </div>

                        <!-- Boutons -->
                        <div class="d-grid gap-2 mt-4">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-save me-2"></i>Enregistrer le besoin
                            </button>
                            <a href="<?php echo $retourUrl; ?>" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i><?php echo $retourLabel; ?>
                            </a>
                        </div>
                    </form>
                </div>
            </fieldset>
        </article>
    </section>
</main>

<script>
    const regionSelect        = document.getElementById('regionSelect');
    const villeSelect         = document.getElementById('villeSelect');
    const typeBesoinSelect    = document.getElementById('typeBesoinSelect');
    const categorieSelect     = document.getElementById('categorieSelect');
    const besoinMaterielBlock = document.getElementById('besoinMaterielBlock');
    const besoinArgentBlock   = document.getElementById('besoinArgentBlock');

    // ── Filtrage villes par région ───────────────────────────────
    const villesData = villeSelect
        ? Array.from(villeSelect.options)
              .filter(o => o.value)
              .map(o => ({ id: o.value, name: o.textContent.trim(), region: o.dataset.region }))
        : [];

    function filterVilles() {
        if (!villeSelect) return;
        const regionId = regionSelect ? regionSelect.value : '';
        villeSelect.innerHTML = '<option value="">-- Choisir une ville --</option>';
        villesData
            .filter(v => !regionId || v.region === regionId)
            .forEach(v => {
                const o = document.createElement('option');
                o.value = v.id; o.textContent = v.name; o.dataset.region = v.region;
                villeSelect.appendChild(o);
            });
    }

    // ── Toggle des blocs selon le type de besoin ─────────────────
    function toggleBesoinBlocks() {
        const type = typeBesoinSelect.value;
        const nomInput      = document.querySelector('input[name="nom_besoin"]');
        const qteInput      = besoinMaterielBlock ? besoinMaterielBlock.querySelector('input[name="quantite"]') : null;
        const montantInput  = besoinArgentBlock   ? besoinArgentBlock.querySelector('input[name="montant"]')   : null;
        const categorieInp  = document.getElementById('categorieSelect');
        const uniteInput    = document.querySelector('input[name="unite"]');
        const sinistreInp   = document.getElementById('sinistreSelect');

        if (type === 'argent') {
            besoinMaterielBlock.classList.add('d-none');
            besoinArgentBlock.classList.remove('d-none');
            if (categorieInp) { categorieInp.removeAttribute('required'); categorieInp.setAttribute('disabled', 'disabled'); }
            if (nomInput)     { nomInput.removeAttribute('required');     nomInput.setAttribute('disabled', 'disabled'); }
            if (qteInput)     { qteInput.removeAttribute('required');     qteInput.setAttribute('disabled', 'disabled'); }
            if (uniteInput)   { uniteInput.setAttribute('disabled', 'disabled'); }
            if (montantInput) { montantInput.setAttribute('required', 'required'); montantInput.removeAttribute('disabled'); }
            if (sinistreInp)  { sinistreInp.removeAttribute('disabled'); }
        } else if (type === 'naturels' || type === 'materiaux') {
            besoinArgentBlock.classList.add('d-none');
            besoinMaterielBlock.classList.remove('d-none');
            if (categorieInp) { categorieInp.setAttribute('required', 'required'); categorieInp.removeAttribute('disabled'); }
            if (nomInput)     { nomInput.setAttribute('required', 'required');     nomInput.removeAttribute('disabled'); }
            if (qteInput)     { qteInput.setAttribute('required', 'required');     qteInput.removeAttribute('disabled'); }
            if (uniteInput)   { uniteInput.removeAttribute('disabled'); }
            if (montantInput) { montantInput.removeAttribute('required'); montantInput.setAttribute('disabled', 'disabled'); }
            if (sinistreInp)  { sinistreInp.setAttribute('disabled', 'disabled'); }
            if (nomInput) {
                nomInput.placeholder = type === 'naturels'
                    ? 'Ex: Riz, Pastèque, Huile, Sucre...'
                    : 'Ex: Tôle, Clou, Ciment, Bois...';
            }
            // Auto-sélectionner la catégorie correspondante
            if (categorieInp) {
                const targetCat = type === 'naturels' ? 'nature' : 'materiaux';
                Array.from(categorieInp.options).forEach(opt => {
                    if (opt.dataset.nom && opt.dataset.nom.toLowerCase() === targetCat) {
                        categorieInp.value = opt.value;
                    }
                });
                // Charger les produits de cette catégorie
                loadProduits(categorieInp.value);
            }
        } else {
            besoinMaterielBlock.classList.add('d-none');
            besoinArgentBlock.classList.add('d-none');
        }
    }

    // ── Chargement dynamique des sinistres quand la ville change ─
    async function loadSinistres(idVille) {
        const container = document.getElementById('sinistreContainer');
        if (!container) return;
        if (!idVille) {
            container.innerHTML = '';
            return;
        }
        try {
            const resp = await fetch('/api/sinistres-ville/' + idVille);
            if (!resp.ok) return;
            const data = await resp.json();
            if (data.length === 0) {
                container.innerHTML = '<div class="alert alert-warning py-2"><i class="fas fa-exclamation-triangle me-1"></i>Aucun sinistre trouvé pour cette ville.</div>';
                return;
            }
            let html = '<div class="mb-3"><label for="sinistreSelect" class="form-label">Sinistre concerné <span class="text-danger">*</span></label>';
            html += '<select name="id_sinistre" id="sinistreSelect" class="form-select"><option value="">-- Choisir un sinistre --</option>';
            data.forEach(s => {
                const label = (s.type_sinistre || s.nom_sinistre || ('Sinistre #' + s.id_sinistre))
                            + (s.date_sinistre ? ' — ' + s.date_sinistre : '');
                html += `<option value="${s.id_sinistre}">${label}</option>`;
            });
            html += '</select></div>';
            container.innerHTML = html;
        } catch(e) {
            console.warn('Impossible de charger les sinistres:', e);
        }
    }

    // ── Events ───────────────────────────────────────────────────
    if (regionSelect) regionSelect.addEventListener('change', filterVilles);
    if (villeSelect)  villeSelect.addEventListener('change', () => loadSinistres(villeSelect.value));
    typeBesoinSelect.addEventListener('change', toggleBesoinBlocks);

    filterVilles();
    toggleBesoinBlocks();

    // ── Chargement des produits existants selon la catégorie ─────
    let produitsCache = [];

    async function loadProduits(idCategorie) {
        const datalist = document.getElementById('produitsDatalist');
        const hint = document.getElementById('nomBesoinHint');
        const prixInput = document.getElementById('prixUnitaireInput');
        const prixHint = document.getElementById('prixHint');
        const uniteInput = document.getElementById('uniteInput');
        
        datalist.innerHTML = '';
        produitsCache = [];
        if (prixHint) prixHint.textContent = '';

        if (!idCategorie) return;

        try {
            const resp = await fetch('/api/produits/' + idCategorie);
            if (!resp.ok) return;
            produitsCache = await resp.json();

            produitsCache.forEach(p => {
                const opt = document.createElement('option');
                opt.value = p.nom_produit;
                opt.dataset.unite = p.unite_standard || '';
                opt.dataset.prix = p.prix_unitaire || 0;
                datalist.appendChild(opt);
            });

            if (hint) {
                hint.textContent = produitsCache.length > 0
                    ? 'Choisissez un produit existant ou tapez un nouveau nom'
                    : 'Aucun produit existant — un nouveau sera créé';
            }
        } catch(e) {
            console.warn('Erreur chargement produits:', e);
        }
    }

    // Quand la catégorie change → charger les produits
    if (categorieSelect) {
        categorieSelect.addEventListener('change', function() {
            loadProduits(this.value);
            // Reset les champs
            const nomInput = document.getElementById('nomBesoinInput');
            const prixInput = document.getElementById('prixUnitaireInput');
            const prixHint = document.getElementById('prixHint');
            if (nomInput) nomInput.value = '';
            if (prixInput) prixInput.value = '';
            if (prixHint) prixHint.textContent = '';
        });
    }

    // Quand on sélectionne/tape un nom → auto-remplir unité et prix
    const nomBesoinInput = document.getElementById('nomBesoinInput');
    if (nomBesoinInput) {
        nomBesoinInput.addEventListener('input', function() {
            const val = this.value.trim().toLowerCase();
            const match = produitsCache.find(p => p.nom_produit.toLowerCase() === val);
            const prixInput = document.getElementById('prixUnitaireInput');
            const prixHint = document.getElementById('prixHint');
            const uniteInput = document.getElementById('uniteInput');

            if (match) {
                // Produit existant trouvé → auto-remplir
                if (uniteInput && match.unite_standard) uniteInput.value = match.unite_standard;
                if (prixInput) prixInput.value = match.prix_unitaire || '';
                if (prixHint) {
                    prixHint.textContent = '✓ Produit existant — prix et unité pré-remplis';
                    prixHint.className = 'text-success small';
                }
            } else if (val.length > 0) {
                // Nouveau produit
                if (prixHint) {
                    prixHint.textContent = '⚠ Nouveau produit — renseignez le prix unitaire';
                    prixHint.className = 'text-warning small';
                }
            } else {
                if (prixHint) { prixHint.textContent = ''; }
            }
        });
    }
</script>

<?php include 'footer.php'; ?>
