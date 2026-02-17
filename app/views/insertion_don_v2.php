<?php include 'header.php'; ?>

<main class="container my-5">
    <section class="row">
        <header class="col-12">
            <h1 class="mb-4">Faire un don</h1>
            <p class="lead">Contribuez à l'aide d'urgence en choisissant parmi les produits disponibles</p>
            
            <?php if (isset($_SESSION['message'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?php echo htmlspecialchars($_SESSION['message']); unset($_SESSION['message']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
        </header>
    </section>

    <section class="row">
        <article class="col-lg-8">
            <form id="donForm" method="POST" action="/insertion_don" class="needs-validation" novalidate>
                <!-- Panier de dons -->
                <fieldset class="card mb-4">
                    <header class="card-header">
                        <h2 class="mb-0">📦 Panier de dons</h2>
                    </header>
                    <div class="card-body">
                        <div id="panierContainer">
                            <p class="text-muted" id="panierVide">Votre panier est vide. Ajoutez des produits ci-dessous.</p>
                        </div>
                    </div>
                </fieldset>

                <!-- Formulaire d'ajout -->
                <fieldset class="card mb-4">
                    <header class="card-header">
                        <h2 class="mb-0">Ajouter un produit</h2>
                    </header>
                    <div class="card-body">
                        <div class="row">
                            <!-- Catégorie -->
                            <div class="col-md-4 mb-3">
                                <label for="categorie" class="form-label required">Catégorie</label>
                                <select class="form-select" id="categorie" required>
                                    <option value="">Choisir...</option>
                                    <?php foreach ($categories as $cat): ?>
                                        <option value="<?php echo $cat['id_categorie']; ?>">
                                            <?php echo htmlspecialchars($cat['nom']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <!-- Produit -->
                            <div class="col-md-3 mb-3">
                                <label for="produitInput" class="form-label required">Produit</label>
                                <input type="text" class="form-control" id="produitInput" list="produitDatalist"
                                       placeholder="Choisir une catégorie d'abord" disabled autocomplete="off">
                                <datalist id="produitDatalist"></datalist>
                                <small class="form-text" id="produitHint"></small>
                            </div>

                            <!-- Quantité -->
                            <div class="col-md-2 mb-3">
                                <label for="quantite" class="form-label required">Quantité</label>
                                <input type="number" class="form-control" id="quantite" 
                                       min="0.01" step="0.01" placeholder="0" required disabled>
                            </div>

                            <!-- Unité -->
                            <div class="col-md-2 mb-3">
                                <label for="uniteInput" class="form-label">Unité</label>
                                <input type="text" class="form-control" id="uniteInput" placeholder="kg, L, ..." disabled>
                            </div>

                            <!-- Prix unitaire -->
                            <div class="col-md-2 mb-3">
                                <label for="prixInput" class="form-label">Prix unit. (Ar)</label>
                                <input type="number" class="form-control" id="prixInput" min="0" step="1" placeholder="0" disabled>
                            </div>

                            <!-- Bouton Ajouter -->
                            <div class="col-md-1 mb-3 d-flex align-items-end">
                                <button type="button" class="btn btn-secondary w-100" id="btnAjouter" disabled>
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Don d'argent -->
                        <div class="row mt-3 pt-3 border-top">
                            <div class="col-12">
                                <h3 class="h5">Ou faire un don d'argent</h3>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="montantArgent" class="form-label">Montant en Ariary</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" id="montantArgent" 
                                           min="1000" step="1000" placeholder="10 000">
                                    <span class="input-group-text">Ar</span>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3 d-flex align-items-end">
                                <button type="button" class="btn btn-secondary" id="btnAjouterArgent">
                                    <i class="fas fa-money-bill-wave"></i> Ajouter au panier
                                </button>
                            </div>
                        </div>
                    </div>
                </fieldset>

                <!-- Champ caché pour le JSON -->
                <input type="hidden" name="dons_json" id="donsJson">

                <!-- Boutons d'action -->
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary" id="btnSubmit" disabled>
                        <i class="fas fa-check"></i> Valider le don
                    </button>
                    <a href="/accueil" class="btn btn-outline-secondary">
                        <i class="fas fa-times"></i> Annuler
                    </a>
                </div>
            </form>
        </article>

        <!-- Résumé -->
        <aside class="col-lg-4">
            <div class="card sticky-top" style="top: 100px;">
                <header class="card-header">
                    <h2 class="mb-0">📊 Résumé</h2>
                </header>
                <div class="card-body">
                    <dl>
                        <dt>Produits matériels:</dt>
                        <dd id="nbProduits">0</dd>
                        <dt>Don d'argent:</dt>
                        <dd id="totalArgent">0 Ar</dd>
                    </dl>
                    <hr>
                    <p class="text-muted small">
                        <i class="fas fa-info-circle"></i> 
                        Vous pouvez ajouter plusieurs produits avant de valider.
                    </p>
                </div>
            </div>
        </aside>
    </section>
</main>

<script>
// Panier
let panier = {
    materiaux: [],
    argent: 0
};

// Cache des produits chargés depuis l'API
let produitsCache = [];

// Éléments DOM
const selectCategorie = document.getElementById('categorie');
const produitInput = document.getElementById('produitInput');
const produitDatalist = document.getElementById('produitDatalist');
const produitHint = document.getElementById('produitHint');
const inputQuantite = document.getElementById('quantite');
const uniteInput = document.getElementById('uniteInput');
const prixInput = document.getElementById('prixInput');
const inputArgent = document.getElementById('montantArgent');
const btnAjouter = document.getElementById('btnAjouter');
const btnAjouterArgent = document.getElementById('btnAjouterArgent');
const btnSubmit = document.getElementById('btnSubmit');
const panierContainer = document.getElementById('panierContainer');

// Charger les produits depuis l'API selon la catégorie
selectCategorie.addEventListener('change', async function() {
    const idCategorie = this.value;
    produitDatalist.innerHTML = '';
    produitsCache = [];
    produitInput.value = '';
    uniteInput.value = '';
    prixInput.value = '';
    produitHint.textContent = '';

    if (idCategorie) {
        try {
            const resp = await fetch('/api/produits/' + idCategorie);
            if (resp.ok) {
                produitsCache = await resp.json();
                produitsCache.forEach(p => {
                    const opt = document.createElement('option');
                    opt.value = p.nom_produit;
                    opt.dataset.id = p.id_produit;
                    opt.dataset.unite = p.unite_standard || '';
                    opt.dataset.prix = p.prix_unitaire || 0;
                    produitDatalist.appendChild(opt);
                });
                produitHint.textContent = produitsCache.length > 0
                    ? 'Choisissez un produit existant ou tapez un nouveau nom'
                    : 'Aucun produit — tapez un nom pour en créer un';
                produitHint.className = 'form-text text-muted';
            }
        } catch(e) {
            console.warn('Erreur chargement produits:', e);
        }
        produitInput.disabled = false;
        inputQuantite.disabled = false;
        uniteInput.disabled = false;
        prixInput.disabled = false;
        produitInput.focus();
    } else {
        produitInput.disabled = true;
        inputQuantite.disabled = true;
        uniteInput.disabled = true;
        prixInput.disabled = true;
        btnAjouter.disabled = true;
    }
});

// Détecter produit existant ou nouveau quand l'utilisateur tape
produitInput.addEventListener('input', function() {
    const val = this.value.trim().toLowerCase();
    const match = produitsCache.find(p => p.nom_produit.toLowerCase() === val);

    if (match) {
        uniteInput.value = match.unite_standard || '';
        prixInput.value = match.prix_unitaire || '';
        produitHint.textContent = '✓ Produit existant';
        produitHint.className = 'form-text text-success';
        btnAjouter.disabled = false;
    } else if (val.length > 0) {
        produitHint.textContent = '⚠ Nouveau produit — renseignez unité et prix';
        produitHint.className = 'form-text text-warning';
        btnAjouter.disabled = false;
    } else {
        produitHint.textContent = '';
        btnAjouter.disabled = true;
    }
});

// Ajouter un produit au panier
btnAjouter.addEventListener('click', function() {
    const nomProduit = produitInput.value.trim();
    const quantite = parseFloat(inputQuantite.value);
    const unite = uniteInput.value.trim();
    const prix = parseFloat(prixInput.value) || 0;
    const idCategorie = parseInt(selectCategorie.value);

    if (!nomProduit || !quantite || quantite <= 0 || !idCategorie) {
        alert('Veuillez remplir tous les champs correctement.');
        return;
    }

    // Chercher si c'est un produit existant
    const match = produitsCache.find(p => p.nom_produit.toLowerCase() === nomProduit.toLowerCase());
    const idProduit = match ? match.id_produit : null;

    // Vérifier si le produit existe déjà dans le panier (par nom)
    const existingIndex = panier.materiaux.findIndex(p => p.nom_produit.toLowerCase() === nomProduit.toLowerCase());
    if (existingIndex >= 0) {
        panier.materiaux[existingIndex].quantite += quantite;
    } else {
        panier.materiaux.push({
            id_produit: idProduit,
            nom_produit: nomProduit,
            id_categorie: idCategorie,
            quantite: quantite,
            unite: unite,
            prix_unitaire: prix
        });
    }

    // Réinitialiser
    inputQuantite.value = '';
    produitInput.value = '';
    uniteInput.value = '';
    prixInput.value = '';
    produitHint.textContent = '';
    btnAjouter.disabled = true;

    updatePanier();
});

// Ajouter un don d'argent
btnAjouterArgent.addEventListener('click', function() {
    const montant = parseInt(inputArgent.value);
    if (!montant || montant < 1000) {
        alert('Le montant minimum est de 1000 Ar.');
        return;
    }
    panier.argent += montant;
    inputArgent.value = '';
    updatePanier();
});

// Supprimer un produit du panier
function supprimerProduit(index) {
    panier.materiaux.splice(index, 1);
    updatePanier();
}

// Mettre à jour l'affichage du panier
function updatePanier() {
    const panierVide = document.getElementById('panierVide');
    
    if (panier.materiaux.length === 0 && panier.argent === 0) {
        panierContainer.innerHTML = '<p class="text-muted" id="panierVide">Votre panier est vide. Ajoutez des produits ci-dessous.</p>';
        btnSubmit.disabled = true;
    } else {
        let html = '<div class="list-group">';
        
        // Afficher les produits matériels
        panier.materiaux.forEach((item, index) => {
            html += `
                <div class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                        <strong>${item.nom_produit}</strong><br>
                        <small class="text-muted">${item.quantite} ${item.unite}</small>
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="supprimerProduit(${index})">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            `;
        });
        
        // Afficher le don d'argent
        if (panier.argent > 0) {
            html += `
                <div class="list-group-item d-flex justify-content-between align-items-center bg-light">
                    <div>
                        <strong>Don d'argent</strong><br>
                        <small class="text-muted">${panier.argent.toLocaleString()} Ar</small>
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="supprimerArgent()">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            `;
        }
        
        html += '</div>';
        panierContainer.innerHTML = html;
        btnSubmit.disabled = false;
    }
    
    // Mettre à jour le résumé
    document.getElementById('nbProduits').textContent = panier.materiaux.length;
    document.getElementById('totalArgent').textContent = panier.argent.toLocaleString() + ' Ar';
    
    // Mettre à jour le champ caché JSON
    document.getElementById('donsJson').value = JSON.stringify(panier);
}

function supprimerArgent() {
    panier.argent = 0;
    updatePanier();
}

// Validation du formulaire
document.getElementById('donForm').addEventListener('submit', function(e) {
    if (panier.materiaux.length === 0 && panier.argent === 0) {
        e.preventDefault();
        alert('Veuillez ajouter au moins un produit ou un don d\'argent.');
    }
});
</script>

<?php include 'footer.php'; ?>
