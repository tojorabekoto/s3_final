<?php include 'header.php'; ?>

<div class="container py-5">
    <div class="row mb-4">
        <div class="col-12">
            <h1>Attribution des dons</h1>
            <p class="text-muted">Attribuez les dons du stock global aux besoins spécifiques des villes.</p>
        </div>
    </div>

    <?php if (!empty($message)): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>
    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <div class="row">
        <!-- Stock disponible -->
        <div class="col-lg-5 mb-4">
            <div class="card p-3">
                <h5 class="mb-3">📦 Stock disponible</h5>
                
                <div class="mb-4">
                    <h6 class="text-primary">Matériels</h6>
                    <ul class="list-group">
                        <?php if (!empty($stock_materiel)): ?>
                            <?php foreach ($stock_materiel as $stock): ?>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>
                                        <strong><?php echo htmlspecialchars($stock['nom_produit']); ?></strong>
                                        <small class="text-muted">(<?php echo htmlspecialchars($stock['categorie']); ?>)</small>
                                    </span>
                                    <span class="badge bg-success"><?php echo $stock['quantite_disponible']; ?> <?php echo htmlspecialchars($stock['unite']); ?></span>
                                </li>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <li class="list-group-item text-muted">Aucun stock matériel disponible</li>
                        <?php endif; ?>
                    </ul>
                </div>

                <div>
                    <h6 class="text-success">Argent</h6>
                    <?php if ($total_stock_argent > 0): ?>
                        <div class="list-group">
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <span><strong>Stock argent global</strong></span>
                                <span class="badge bg-success fs-6"><?php echo number_format($total_stock_argent, 0, ',', ' '); ?> Ar</span>
                            </div>
                        </div>
                    <?php else: ?>
                        <ul class="list-group">
                            <li class="list-group-item text-muted">Aucun stock argent disponible</li>
                        </ul>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Formulaire d'attribution -->
        <div class="col-lg-7">
            <div class="card p-4">
                <h5 class="mb-4">Faire une attribution</h5>

                <form method="POST" action="/attribution">
                    <!-- Étape 1: Type de don -->
                    <div class="row mb-3">
                        <div class="col-12">
                            <label class="form-label fw-bold">1. Type d'attribution</label>
                            <select name="type_don" id="typeSelect" class="form-select" required>
                                <option value="">-- Choisir le type --</option>
                                <option value="naturels">Naturels</option>
                                <option value="materiaux">Matériaux</option>
                                <option value="argent">Argent</option>
                            </select>
                        </div>
                    </div>

                    <!-- Étape 2: Sélection du stock -->
                    <div id="stockBlock" style="display:none;">
                        <div class="row mb-3">
                            <div class="col-12">
                                <label class="form-label fw-bold">2. Choisir depuis le stock</label>
                                
                                <!-- Stock matériel -->
                                <select name="id_stock" id="stockMaterielSelect" class="form-select d-none">
                                    <option value="">-- Choisir un stock --</option>
                                    <?php foreach ($stock_materiel as $stock): ?>
                                        <option value="<?php echo $stock['id_stock']; ?>" 
                                                data-categorie="<?php echo htmlspecialchars($stock['categorie']); ?>"
                                                data-dispo="<?php echo $stock['quantite_disponible']; ?>"
                                                data-unite="<?php echo htmlspecialchars($stock['unite']); ?>">
                                            <?php echo htmlspecialchars($stock['nom_produit']); ?> 
                                            (<?php echo $stock['quantite_disponible']; ?> <?php echo htmlspecialchars($stock['unite']); ?> disponibles)
                                        </option>
                                    <?php endforeach; ?>
                                </select>

                                <!-- Stock argent : montant total (pas de sélection individuelle) -->
                                <div id="stockArgentDisplay" class="d-none">
                                    <input type="hidden" name="id_stock_argent" value="global">
                                    <div class="alert alert-success mb-0">
                                        <i class="fas fa-wallet me-2"></i>
                                        Stock argent disponible : <strong><?php echo number_format($total_stock_argent, 0, ',', ' '); ?> Ar</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Étape 3: Destination -->
                    <div id="destinationBlock" style="display:none;">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">3. Région</label>
                                <select id="regionSelect" class="form-select" required>
                                    <option value="">-- Choisir --</option>
                                    <?php foreach ($regions as $region): ?>
                                        <option value="<?php echo $region['id_region']; ?>"><?php echo htmlspecialchars($region['nom_region']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Ville</label>
                                <select id="villeSelect" class="form-select" required>
                                    <option value="">-- Choisir --</option>
                                    <?php foreach ($villes as $ville): ?>
                                        <option value="<?php echo $ville['id_ville']; ?>" data-region="<?php echo (int)$ville['id_region']; ?>">
                                            <?php echo htmlspecialchars($ville['nom_ville']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-12">
                                <label class="form-label fw-bold">Besoin à satisfaire</label>
                                
                                <!-- Besoins matériels -->
                                <select name="id_besoin" id="besoinMaterielSelect" class="form-select d-none">
                                    <option value="">-- Choisir un besoin --</option>
                                    <?php foreach ($besoins_materiaux as $besoin): ?>
                                        <option value="<?php echo $besoin['id_besoin']; ?>" 
                                                data-ville="<?php echo $besoin['id_ville']; ?>"
                                                data-categorie="<?php echo htmlspecialchars($besoin['categorie']); ?>">
                                            <?php echo htmlspecialchars($besoin['nom_besoin']); ?> 
                                            (Besoin: <?php echo $besoin['quantite']; ?> <?php echo htmlspecialchars($besoin['unite'] ?? ''); ?>)
                                        </option>
                                    <?php endforeach; ?>
                                </select>

                                <!-- Besoins argent -->
                                <select name="id_besoin_argent" id="besoinArgentSelect" class="form-select d-none">
                                    <option value="">-- Choisir un besoin --</option>
                                    <?php foreach ($besoins_argent as $besoin): ?>
                                        <option value="<?php echo $besoin['id_besoin_argent']; ?>" 
                                                data-ville="<?php echo $besoin['id_ville']; ?>">
                                            Besoin: <?php echo number_format($besoin['montant_necessaire'], 0, ',', ' '); ?> Ar
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Étape 4: Quantité -->
                    <div id="quantiteBlock" style="display:none;">
                        <div class="row mb-3">
                            <div class="col-md-8">
                                <label class="form-label fw-bold">4. Quantité à attribuer</label>
                                <input type="number" step="0.01" min="0" name="quantite" id="quantiteInput" class="form-control" required>
                                <small id="maxDispo" class="form-text text-muted"></small>
                            </div>
                        </div>
                    </div>

                    <button type="submit" id="submitBtn" class="btn btn-primary w-100" style="display:none;">
                        <i class="fas fa-check"></i> Valider l'attribution
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    const typeSelect = document.getElementById('typeSelect');
    const stockBlock = document.getElementById('stockBlock');
    const stockMaterielSelect = document.getElementById('stockMaterielSelect');
    const stockArgentDisplay = document.getElementById('stockArgentDisplay');
    const totalStockArgent = <?php echo $total_stock_argent; ?>;
    const destinationBlock = document.getElementById('destinationBlock');
    const regionSelect = document.getElementById('regionSelect');
    const villeSelect = document.getElementById('villeSelect');
    const besoinMaterielSelect = document.getElementById('besoinMaterielSelect');
    const besoinArgentSelect = document.getElementById('besoinArgentSelect');
    const quantiteBlock = document.getElementById('quantiteBlock');
    const quantiteInput = document.getElementById('quantiteInput');
    const maxDispo = document.getElementById('maxDispo');
    const submitBtn = document.getElementById('submitBtn');

    const villesData = Array.from(villeSelect.options)
        .filter(option => option.value)
        .map(option => ({
            id: option.value,
            name: option.textContent.trim(),
            region: option.dataset.region
        }));

    function renderVilles(regionId) {
        villeSelect.innerHTML = '<option value="">-- Choisir --</option>';
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

    // Étape 1: Type
    typeSelect.addEventListener('change', () => {
        const type = typeSelect.value;
        stockBlock.style.display = type ? 'block' : 'none';
        destinationBlock.style.display = 'none';
        quantiteBlock.style.display = 'none';
        submitBtn.style.display = 'none';

        stockMaterielSelect.classList.add('d-none');
        stockArgentDisplay.classList.add('d-none');
        stockMaterielSelect.value = '';

        if (type === 'naturels' || type === 'materiaux') {
            stockMaterielSelect.classList.remove('d-none');
            filterStockByType(type);
        } else if (type === 'argent') {
            stockArgentDisplay.classList.remove('d-none');
            // Passer directement à la destination
            destinationBlock.style.display = 'block';
            besoinArgentSelect.classList.remove('d-none');
            besoinMaterielSelect.classList.add('d-none');
            updateMaxDispo();
        }
    });

    function filterStockByType(type) {
        Array.from(stockMaterielSelect.options).forEach(option => {
            if (!option.value) return;
            const categorie = String(option.dataset.categorie || '').toLowerCase();
            const match = (type === 'naturels' && (categorie === 'nature' || categorie === 'naturel')) ||
                         (type === 'materiaux' && (categorie === 'materiaux' || categorie === 'materiel'));
            option.hidden = !match;
        });
    }

    // Étape 2: Stock
    stockMaterielSelect.addEventListener('change', () => {
        destinationBlock.style.display = stockMaterielSelect.value ? 'block' : 'none';
        besoinMaterielSelect.classList.remove('d-none');
        besoinArgentSelect.classList.add('d-none');
        updateMaxDispo();
    });

    // Plus besoin d'event listener pour stockArgentSelect (c'est automatique)

    // Étape 3: Destination
    regionSelect.addEventListener('change', () => {
        renderVilles(regionSelect.value);
        filterBesoins();
    });

    villeSelect.addEventListener('change', () => {
        filterBesoins();
        checkComplete();
    });

    besoinMaterielSelect.addEventListener('change', checkComplete);
    besoinArgentSelect.addEventListener('change', checkComplete);

    function filterBesoins() {
        const ville = villeSelect.value;
        const type = typeSelect.value;

        Array.from(besoinMaterielSelect.options).forEach(option => {
            if (!option.value) return;
            const categorie = String(option.dataset.categorie || '').toLowerCase();
            const villeMatch = !ville || option.dataset.ville === ville;
            const typeMatch = (type === 'naturels' && (categorie === 'nature' || categorie === 'naturel')) ||
                             (type === 'materiaux' && (categorie === 'materiaux' || categorie === 'materiel'));
            option.hidden = !(villeMatch && typeMatch);
        });

        Array.from(besoinArgentSelect.options).forEach(option => {
            if (!option.value) return;
            option.hidden = ville && option.dataset.ville !== ville;
        });
    }

    function checkComplete() {
        const type = typeSelect.value;
        const hasStock = (type === 'argent' && totalStockArgent > 0) || 
                        ((type === 'naturels' || type === 'materiaux') && stockMaterielSelect.value);
        const hasBesoin = (type === 'argent' && besoinArgentSelect.value) || 
                         ((type === 'naturels' || type === 'materiaux') && besoinMaterielSelect.value);

        if (hasStock && hasBesoin) {
            quantiteBlock.style.display = 'block';
            submitBtn.style.display = 'block';
        }
    }

    function updateMaxDispo() {
        const type = typeSelect.value;
        let dispo = 0;
        let unite = '';

        if (type === 'argent') {
            dispo = totalStockArgent;
            unite = 'Ar';
        } else if ((type === 'naturels' || type === 'materiaux') && stockMaterielSelect.value) {
            const selected = stockMaterielSelect.options[stockMaterielSelect.selectedIndex];
            dispo = selected.dataset.dispo;
            unite = selected.dataset.unite;
        }

        maxDispo.textContent = `Maximum disponible: ${dispo} ${unite}`;
        quantiteInput.max = dispo;
    }

    renderVilles('');
</script>

<?php include 'footer.php'; ?>
