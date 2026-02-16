<?php include 'header.php'; ?>

<style>
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
            <p class="text-muted">Enregistrez vos dons dans le stock global. Ils seront attribués ultérieurement aux villes dans le besoin.</p>
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
                <h5 class="card-title mb-4">Ajouter un don au stock</h5>

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

                <!-- Nom du produit (pour matériels uniquement) -->
                <div id="nomProduitBlock" class="row mb-3" style="display:none;">
                    <div class="col-12">
                        <label class="form-label">Nom du produit</label>
                        <input type="text" id="nomProduitInput" class="form-control" placeholder="Ex: Riz, Huile, Tôle, Clou...">
                    </div>
                </div>

                <!-- Quantité -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Quantité</label>
                        <input type="number" step="0.01" min="0" id="quantiteInput" class="form-control" required>
                    </div>
                    <div class="col-md-6" id="uniteBlock" style="display:none;">
                        <label class="form-label">Unité</label>
                        <input type="text" id="uniteInput" class="form-control" placeholder="Ex: kg, litres, pièces...">
                    </div>
                </div>

                <button type="button" id="addDonBtn" class="btn btn-success w-100">
                    <i class="fas fa-plus"></i> Ajouter ce don
                </button>
            </div>
        </div>

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
    const typeSelect = document.getElementById('typeSelect');
    const nomProduitBlock = document.getElementById('nomProduitBlock');
    const nomProduitInput = document.getElementById('nomProduitInput');
    const uniteBlock = document.getElementById('uniteBlock');
    const uniteInput = document.getElementById('uniteInput');
    const quantiteInput = document.getElementById('quantiteInput');
    const addDonBtn = document.getElementById('addDonBtn');
    const donsList = document.getElementById('donsList');
    const submitForm = document.getElementById('submitForm');
    const donsData = document.getElementById('donsData');

    // Données catégories
    const categories = <?php echo json_encode($categories ?? []); ?>;

    // Stockage des dons
    let donsArray = [];

    // Afficher/masquer champs selon type
    typeSelect.addEventListener('change', () => {
        const type = typeSelect.value;
        
        if (type === 'naturels' || type === 'materiaux') {
            nomProduitBlock.style.display = 'block';
            uniteBlock.style.display = 'block';
            nomProduitInput.required = true;
            uniteInput.required = true;
        } else if (type === 'argent') {
            nomProduitBlock.style.display = 'none';
            uniteBlock.style.display = 'none';
            nomProduitInput.required = false;
            uniteInput.required = false;
            uniteInput.value = 'Ar';
        } else {
            nomProduitBlock.style.display = 'none';
            uniteBlock.style.display = 'none';
        }
    });

    // Ajouter un don
    addDonBtn.addEventListener('click', () => {
        const type = typeSelect.value;
        const nomProduit = nomProduitInput.value.trim();
        const quantite = parseFloat(quantiteInput.value);
        const unite = uniteInput.value.trim();

        if (!type || !quantite || quantite <= 0) {
            alert('Veuillez remplir tous les champs obligatoires');
            return;
        }

        if (type !== 'argent' && (!nomProduit || !unite)) {
            alert('Veuillez remplir le nom du produit et l\'unité');
            return;
        }

        const id = Date.now();
        const don = {
            id: id,
            type: type,
            nom_produit: type === 'argent' ? 'Don en argent' : nomProduit,
            quantite: quantite,
            unite: type === 'argent' ? 'Ar' : unite
        };

        donsArray.push(don);
        renderDonsList();
        
        // Reset formulaire
        typeSelect.value = '';
        nomProduitInput.value = '';
        quantiteInput.value = '';
        uniteInput.value = '';
        nomProduitBlock.style.display = 'none';
        uniteBlock.style.display = 'none';
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
                    <strong>${htmlEscape(don.nom_produit)}</strong>
                    <small>Catégorie: ${htmlEscape(don.type)}</small>
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
        return String(str).replace(/[&<>"']/g, m => map[m]);
    }

    // Submit du formulaire
    submitForm.addEventListener('submit', (e) => {
        if (donsArray.length === 0) {
            e.preventDefault();
            alert('Aucun don à enregistrer');
        }
    });
</script>

<?php include 'footer.php'; ?>
