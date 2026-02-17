<?php include 'header.php'; ?>

<main class="container my-5">
    <section class="row mb-4">
        <header class="col-12">
            <a href="/accueil" class="btn btn-outline-secondary mb-3">
                <i class="fas fa-arrow-left me-1"></i>Retour à l'accueil
            </a>
            <h1 class="mb-2"><i class="fas fa-cogs me-2"></i>Configuration & Administration</h1>
            <p class="lead">Paramètres système et réinitialisation de la base de données</p>
        </header>
    </section>

    <?php if (!empty($message)): ?>
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

    <div class="row g-4">
        <!-- Paramètres -->
        <section class="col-md-7">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-sliders-h me-2"></i>Paramètres</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Paramètre</th>
                                    <th>Valeur</th>
                                    <th>Description</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($configs as $cfg): ?>
                                <tr>
                                    <td class="fw-bold small"><?php echo htmlspecialchars($cfg['cle']); ?></td>
                                    <td>
                                        <input type="text" class="form-control form-control-sm config-input"
                                               data-cle="<?php echo htmlspecialchars($cfg['cle']); ?>"
                                               value="<?php echo htmlspecialchars($cfg['valeur']); ?>"
                                               style="width: 100px;">
                                    </td>
                                    <td class="text-muted small"><?php echo htmlspecialchars($cfg['description'] ?? ''); ?></td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-outline-primary btn-save-config"
                                                data-cle="<?php echo htmlspecialchars($cfg['cle']); ?>">
                                            <i class="fas fa-save"></i>
                                        </button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>

        <!-- Réinitialisation -->
        <section class="col-md-5">
            <div class="card border-danger shadow-sm">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0"><i class="fas fa-exclamation-triangle me-2"></i>Zone dangereuse</h5>
                </div>
                <div class="card-body">
                    <h6 class="fw-bold">Réinitialiser la base de données</h6>
                    <p class="text-muted small">
                        Cette action va <strong>supprimer toutes les données</strong> (dons, achats, attributions, 
                        inventaires, historiques, ventes) et restaurer les <strong>données initiales</strong> 
                        fournies par les professeurs.
                    </p>
                    <ul class="small text-muted mb-3">
                        <li>6 régions, 6 villes, 6 sinistres</li>
                        <li>19 produits avec prix unitaires</li>
                        <li>15 besoins matériaux + 6 besoins argent</li>
                        <li>5 dons matériaux + 4 dons argent initiaux</li>
                        <li>Stock matériel initial (3 produits)</li>
                        <li>Stock argent initial (12 entrées)</li>
                        <li>Inventaire argent initial par sinistre</li>
                    </ul>
                    
                    <div class="d-grid">
                        <button type="button" id="btnReset" class="btn btn-outline-danger">
                            <i class="fas fa-undo me-2"></i>Réinitialiser
                        </button>
                    </div>
                </div>
            </div>
        </section>
    </div>
</main>

<!-- Modal confirmation reset -->
<div class="modal fade" id="resetConfirmModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title"><i class="fas fa-exclamation-triangle me-2"></i>Confirmer la réinitialisation</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <strong>Attention !</strong> Toutes les données seront supprimées et remplacées par les données initiales.
                    Cette action est <strong>irréversible</strong>.
                </div>
                <p>Tapez <strong class="text-danger">RESET</strong> pour confirmer :</p>
                <input type="text" id="resetConfirmInput" class="form-control" placeholder="Tapez RESET ici...">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" id="btnConfirmReset" class="btn btn-danger" disabled>
                    <i class="fas fa-undo me-2"></i>Réinitialiser maintenant
                </button>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>

<script>
// ─── Save config ─────────────────────────────
document.querySelectorAll('.btn-save-config').forEach(btn => {
    btn.addEventListener('click', function() {
        const cle = this.dataset.cle;
        const input = document.querySelector(`.config-input[data-cle="${cle}"]`);
        const valeur = input.value;

        fetch('/api/config', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: 'cle=' + encodeURIComponent(cle) + '&valeur=' + encodeURIComponent(valeur)
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                showToast('Configuration "' + cle + '" mise à jour.', 'success');
                input.classList.add('border-success');
                setTimeout(() => input.classList.remove('border-success'), 2000);
            } else {
                showToast('Erreur : ' + (data.error || 'Échec'), 'danger');
            }
        });
    });
});

// ─── Reset ───────────────────────────────────
const resetModal = document.getElementById('resetConfirmModal');
const resetInput = document.getElementById('resetConfirmInput');
const btnConfirm = document.getElementById('btnConfirmReset');

document.getElementById('btnReset')?.addEventListener('click', function() {
    resetInput.value = '';
    btnConfirm.disabled = true;
    new bootstrap.Modal(resetModal).show();
});

resetInput?.addEventListener('input', function() {
    btnConfirm.disabled = (this.value !== 'RESET');
});

btnConfirm?.addEventListener('click', function() {
    this.disabled = true;
    this.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Réinitialisation...';

    fetch('/api/reset', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'}
    })
    .then(r => r.json())
    .then(data => {
        bootstrap.Modal.getInstance(resetModal).hide();
        if (data.success) {
            showToast('Base de données réinitialisée avec succès !', 'success');
            setTimeout(() => window.location.href = '/accueil', 1500);
        } else {
            showToast('Erreur : ' + (data.error || 'Échec de la réinitialisation'), 'danger');
            btnConfirm.disabled = false;
            btnConfirm.innerHTML = '<i class="fas fa-undo me-2"></i>Réinitialiser maintenant';
        }
    })
    .catch(err => {
        showToast('Erreur réseau : ' + err.message, 'danger');
        btnConfirm.disabled = false;
        btnConfirm.innerHTML = '<i class="fas fa-undo me-2"></i>Réinitialiser maintenant';
    });
});

// ─── Toast helper ────────────────────────────
function showToast(message, type) {
    const toast = document.createElement('div');
    toast.className = `alert alert-${type} position-fixed bottom-0 end-0 m-3 shadow`;
    toast.style.zIndex = '9999';
    toast.innerHTML = '<i class="fas fa-' + (type === 'success' ? 'check-circle' : 'exclamation-circle') + ' me-2"></i>' + message;
    document.body.appendChild(toast);
    setTimeout(() => toast.remove(), 4000);
}
</script>
