<?php include 'header.php'; ?>

<div class="container py-5">
    <div class="row mb-4">
        <div class="col-12">
            <h1>Attribution des dons</h1>
        </div>
    </div>

    <?php if (!empty($message)): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>
    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <div class="row">
        <div class="col-lg-6 mb-4">
            <h3 class="mb-3">Besoins par categorie (restant)</h3>
            <div class="card p-3">
                <p class="text-muted">Selectionnez une ville a droite pour filtrer les besoins.</p>
                <div id="restantMateriaux" class="mb-4">
                    <h5>Materiaux</h5>
                    <ul class="list-group">
                        <?php foreach ($besoins_materiaux as $besoin): ?>
                            <li class="list-group-item" data-ville="<?php echo $besoin['id_ville']; ?>">
                                <?php echo htmlspecialchars($besoin['nom_besoin']); ?> - <?php echo htmlspecialchars($besoin['categorie']); ?>
                                (<?php echo $besoin['quantite']; ?> <?php echo htmlspecialchars($besoin['unite'] ?? ''); ?>)
                            </li>
                        <?php endforeach; ?>
                        <?php if (empty($besoins_materiaux)): ?>
                            <li class="list-group-item text-muted">Aucun besoin materiel.</li>
                        <?php endif; ?>
                    </ul>
                </div>

                <div id="restantArgent">
                    <h5>Argent</h5>
                    <ul class="list-group">
                        <?php foreach ($besoins_argent as $besoin): ?>
                            <li class="list-group-item" data-ville="<?php echo $besoin['id_ville']; ?>">
                                Besoin #<?php echo $besoin['id_besoin_argent']; ?> - <?php echo number_format($besoin['montant_necessaire'], 2, ',', ' '); ?> Ar
                            </li>
                        <?php endforeach; ?>
                        <?php if (empty($besoins_argent)): ?>
                            <li class="list-group-item text-muted">Aucun besoin en argent.</li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <h3 class="mb-3">Attribuer un don</h3>
            <form method="POST" action="/attribution" class="card p-4">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Region</label>
                        <select name="id_region" id="regionSelect" class="form-select" required>
                            <option value="">-- Choisir une region --</option>
                            <?php foreach ($regions as $region): ?>
                                <option value="<?php echo $region['id_region']; ?>"><?php echo htmlspecialchars($region['nom_region']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Ville</label>
                        <select name="id_ville" id="villeSelect" class="form-select" required>
                            <option value="">-- Choisir une ville --</option>
                            <?php foreach ($villes as $ville): ?>
                                <option value="<?php echo $ville['id_ville']; ?>" data-region="<?php echo $ville['id_region']; ?>">
                                    <?php echo htmlspecialchars($ville['nom_ville']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Type de don</label>
                        <select name="type_don" id="typeSelect" class="form-select" required>
                            <option value="">-- Choisir --</option>
                            <option value="materiaux">Materiaux</option>
                            <option value="argent">Argent</option>
                        </select>
                    </div>
                    <div class="col-md-6" id="besoinMateriauxBlock">
                        <label class="form-label">Besoin materiel</label>
                        <select name="id_besoin" id="besoinMateriaux" class="form-select">
                            <option value="">-- Choisir un besoin --</option>
                            <?php foreach ($besoins_materiaux as $besoin): ?>
                                <option value="<?php echo $besoin['id_besoin']; ?>" data-ville="<?php echo $besoin['id_ville']; ?>">
                                    <?php echo htmlspecialchars($besoin['nom_besoin']); ?> (<?php echo htmlspecialchars($besoin['categorie']); ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6 d-none" id="besoinArgentBlock">
                        <label class="form-label">Besoin en argent</label>
                        <select name="id_besoin_argent" id="besoinArgent" class="form-select">
                            <option value="">-- Choisir un besoin --</option>
                            <?php foreach ($besoins_argent as $besoin): ?>
                                <option value="<?php echo $besoin['id_besoin_argent']; ?>" data-ville="<?php echo $besoin['id_ville']; ?>">
                                    Besoin #<?php echo $besoin['id_besoin_argent']; ?> - <?php echo number_format($besoin['montant_necessaire'], 2, ',', ' '); ?> Ar
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Quantite</label>
                        <input type="number" step="0.01" min="0" name="quantite" class="form-control" required>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Attribuer</button>
            </form>
        </div>
    </div>
</div>

<script>
    const regionSelect = document.getElementById('regionSelect');
    const villeSelect = document.getElementById('villeSelect');
    const typeSelect = document.getElementById('typeSelect');
    const besoinMateriauxBlock = document.getElementById('besoinMateriauxBlock');
    const besoinArgentBlock = document.getElementById('besoinArgentBlock');
    const besoinMateriaux = document.getElementById('besoinMateriaux');
    const besoinArgent = document.getElementById('besoinArgent');

    function filterVilles() {
        const region = regionSelect.value;
        Array.from(villeSelect.options).forEach(option => {
            if (!option.value) return;
            option.hidden = region && option.dataset.region !== region;
        });
        villeSelect.value = '';
        filterBesoins();
        filterRestants();
    }

    function filterBesoins() {
        const ville = villeSelect.value;
        Array.from(besoinMateriaux.options).forEach(option => {
            if (!option.value) return;
            option.hidden = ville && option.dataset.ville !== ville;
        });
        Array.from(besoinArgent.options).forEach(option => {
            if (!option.value) return;
            option.hidden = ville && option.dataset.ville !== ville;
        });
    }

    function filterRestants() {
        const ville = villeSelect.value;
        document.querySelectorAll('#restantMateriaux .list-group-item').forEach(item => {
            if (!item.dataset.ville) return;
            item.style.display = ville && item.dataset.ville !== ville ? 'none' : '';
        });
        document.querySelectorAll('#restantArgent .list-group-item').forEach(item => {
            if (!item.dataset.ville) return;
            item.style.display = ville && item.dataset.ville !== ville ? 'none' : '';
        });
    }

    function toggleType() {
        if (typeSelect.value === 'argent') {
            besoinMateriauxBlock.classList.add('d-none');
            besoinArgentBlock.classList.remove('d-none');
        } else if (typeSelect.value === 'materiaux') {
            besoinArgentBlock.classList.add('d-none');
            besoinMateriauxBlock.classList.remove('d-none');
        } else {
            besoinArgentBlock.classList.add('d-none');
            besoinMateriauxBlock.classList.remove('d-none');
        }
    }

    regionSelect.addEventListener('change', filterVilles);
    villeSelect.addEventListener('change', () => {
        filterBesoins();
        filterRestants();
    });
    typeSelect.addEventListener('change', toggleType);
    filterVilles();
    toggleType();
</script>

<?php include 'footer.php'; ?>
