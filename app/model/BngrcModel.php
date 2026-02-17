<?php

namespace app\model;

use PDO;

class BngrcModel {

    private $db;

    public function __construct($db){
        $this->db = $db;
    }

    public function getRegions() {
        $stmt = $this->db->query("SELECT * FROM region");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getVilles() {
        $stmt = $this->db->query("SELECT * FROM ville");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getVillesByRegion($id_region) {
        $stmt = $this->db->prepare("SELECT * FROM ville WHERE id_region = ?");
        $stmt->execute([$id_region]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getSinistres() {
        $stmt = $this->db->query("SELECT * FROM sinistre");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getSinistresByVille($id_ville) {
        $stmt = $this->db->prepare("SELECT * FROM sinistre WHERE id_ville = ?");
        $stmt->execute([$id_ville]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getBesoinMateriaux() {
        $stmt = $this->db->query("SELECT * FROM besoin_materiaux");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getBesoinArgent() {
        $stmt = $this->db->query("SELECT * FROM besoin_argent");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getDonMateriauxByBesoin($id_besoin) {
        $stmt = $this->db->prepare("SELECT * FROM don_materiaux WHERE id_besoin = ?");
        $stmt->execute([$id_besoin]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getDonArgentByBesoin($id_besoin_argent) {
        $stmt = $this->db->prepare("SELECT * FROM don_argent WHERE id_besoin_argent = ?");
        $stmt->execute([$id_besoin_argent]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCategoriesBesoin() {
        $stmt = $this->db->query("SELECT * FROM categorie_besoin");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getVilleById($id_ville) {
        $stmt = $this->db->prepare("SELECT * FROM ville WHERE id_ville = ?");
        $stmt->execute([$id_ville]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getBesoinsMateriauxByVille($id_ville) {
        $stmt = $this->db->prepare(
            "SELECT bm.id_besoin, cb.nom AS categorie, bm.nom_besoin, bm.quantite, bm.unite
             FROM besoin_materiaux bm
             JOIN sinistre s ON s.id_sinistre = bm.id_sinistre
             JOIN categorie_besoin cb ON cb.id_categorie = bm.id_categorie
             WHERE s.id_ville = ?
             ORDER BY bm.id_besoin"
        );
        $stmt->execute([$id_ville]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getDonsMateriauxByVille($id_ville) {
        $stmt = $this->db->prepare(
            "SELECT bm.id_besoin, cb.nom AS categorie, bm.nom_besoin,
                    SUM(dm.quantite_donnee) AS quantite_donnee, bm.unite
             FROM don_materiaux dm
             JOIN besoin_materiaux bm ON dm.id_besoin = bm.id_besoin
             JOIN sinistre s ON s.id_sinistre = bm.id_sinistre
             JOIN categorie_besoin cb ON cb.id_categorie = bm.id_categorie
             WHERE s.id_ville = ?
             GROUP BY bm.id_besoin, cb.nom, bm.nom_besoin, bm.unite
             ORDER BY bm.id_besoin"
        );
        $stmt->execute([$id_ville]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getRestantMateriauxByVille($id_ville) {
        $stmt = $this->db->prepare(
            "SELECT bm.id_besoin, cb.nom AS categorie, bm.nom_besoin,
                    (bm.quantite - COALESCE(SUM(dm.quantite_donnee), 0)) AS quantite_restante, bm.unite
             FROM besoin_materiaux bm
             JOIN sinistre s ON s.id_sinistre = bm.id_sinistre
             JOIN categorie_besoin cb ON cb.id_categorie = bm.id_categorie
             LEFT JOIN don_materiaux dm ON dm.id_besoin = bm.id_besoin
             WHERE s.id_ville = ?
             GROUP BY bm.id_besoin, cb.nom, bm.nom_besoin, bm.quantite, bm.unite
             ORDER BY bm.id_besoin"
        );
        $stmt->execute([$id_ville]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getBesoinsArgentByVille($id_ville) {
        $stmt = $this->db->prepare(
            "SELECT ba.id_besoin_argent, ba.montant_necessaire
             FROM besoin_argent ba
             JOIN sinistre s ON s.id_sinistre = ba.id_sinistre
             WHERE s.id_ville = ?
             ORDER BY ba.id_besoin_argent"
        );
        $stmt->execute([$id_ville]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getDonsArgentByVille($id_ville) {
        $stmt = $this->db->prepare(
            "SELECT ba.id_besoin_argent,
                    SUM(da.montant_donne) AS montant_donne
             FROM don_argent da
             JOIN besoin_argent ba ON da.id_besoin_argent = ba.id_besoin_argent
             JOIN sinistre s ON s.id_sinistre = ba.id_sinistre
             WHERE s.id_ville = ?
             GROUP BY ba.id_besoin_argent
             ORDER BY ba.id_besoin_argent"
        );
        $stmt->execute([$id_ville]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getRestantArgentByVille($id_ville) {
        $stmt = $this->db->prepare(
            "SELECT ba.id_besoin_argent,
                    (ba.montant_necessaire - COALESCE(SUM(da.montant_donne), 0)) AS montant_restant
             FROM besoin_argent ba
             JOIN sinistre s ON s.id_sinistre = ba.id_sinistre
             LEFT JOIN don_argent da ON da.id_besoin_argent = ba.id_besoin_argent
             WHERE s.id_ville = ?
             GROUP BY ba.id_besoin_argent, ba.montant_necessaire
             ORDER BY ba.id_besoin_argent"
        );
        $stmt->execute([$id_ville]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getBesoinsMateriauxForForm() {
        $stmt = $this->db->query(
            "SELECT bm.id_besoin, bm.nom_besoin, bm.quantite, bm.unite, s.id_ville, cb.nom AS categorie
             FROM besoin_materiaux bm
             JOIN sinistre s ON s.id_sinistre = bm.id_sinistre
             JOIN categorie_besoin cb ON cb.id_categorie = bm.id_categorie
             ORDER BY bm.id_besoin"
        );
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getBesoinsArgentForForm() {
        $stmt = $this->db->query(
            "SELECT ba.id_besoin_argent, ba.montant_necessaire, s.id_ville
             FROM besoin_argent ba
             JOIN sinistre s ON s.id_sinistre = ba.id_sinistre
             ORDER BY ba.id_besoin_argent"
        );
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function insertDonMateriaux($id_besoin, $quantite) {
        $stmt = $this->db->prepare(
            "INSERT INTO don_materiaux (id_besoin, quantite_donnee) VALUES (?, ?)"
        );
        return $stmt->execute([$id_besoin, $quantite]);
    }

    public function insertDonArgent($id_besoin_argent, $montant) {
        $stmt = $this->db->prepare(
            "INSERT INTO don_argent (id_besoin_argent, montant_donne) VALUES (?, ?)"
        );
        return $stmt->execute([$id_besoin_argent, $montant]);
    }

    public function getRestantMateriauxByBesoin($id_besoin) {
        $stmt = $this->db->prepare(
            "SELECT (bm.quantite - COALESCE(SUM(dm.quantite_donnee), 0)) AS quantite_restante
             FROM besoin_materiaux bm
             LEFT JOIN don_materiaux dm ON dm.id_besoin = bm.id_besoin
             WHERE bm.id_besoin = ?
             GROUP BY bm.quantite"
        );
        $stmt->execute([$id_besoin]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? (float)$row['quantite_restante'] : 0.0;
    }

    public function getRestantArgentByBesoin($id_besoin_argent) {
        $stmt = $this->db->prepare(
            "SELECT (ba.montant_necessaire - COALESCE(SUM(da.montant_donne), 0)) AS montant_restant
             FROM besoin_argent ba
             LEFT JOIN don_argent da ON da.id_besoin_argent = ba.id_besoin_argent
             WHERE ba.id_besoin_argent = ?
             GROUP BY ba.montant_necessaire"
        );
        $stmt->execute([$id_besoin_argent]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? (float)$row['montant_restant'] : 0.0;
    }

    // ──────────────────────────────────────────────────────────
    // Insertion des besoins
    // ──────────────────────────────────────────────────────────

    public function insertBesoinMateriaux(
        int    $id_ville,
        int    $id_categorie,
        string $nom_besoin,
        float  $quantite,
        string $unite = ''
    ): int {
        // Récupérer le sinistre le plus récent de la ville
        $stmtS = $this->db->prepare(
            "SELECT id_sinistre FROM sinistre WHERE id_ville = ? ORDER BY id_sinistre DESC LIMIT 1"
        );
        $stmtS->execute([$id_ville]);
        $sinistre = $stmtS->fetch(PDO::FETCH_ASSOC);

        if (!$sinistre) {
            throw new \RuntimeException("Aucun sinistre trouvé pour la ville $id_ville.");
        }

        $stmt = $this->db->prepare(
            "INSERT INTO besoin_materiaux (id_sinistre, id_categorie, nom_besoin, quantite, unite)
             VALUES (:id_sinistre, :id_categorie, :nom_besoin, :quantite, :unite)"
        );
        $stmt->execute([
            ':id_sinistre'  => $sinistre['id_sinistre'],
            ':id_categorie' => $id_categorie,
            ':nom_besoin'   => $nom_besoin,
            ':quantite'     => $quantite,
            ':unite'        => $unite,
        ]);

        return (int) $this->db->lastInsertId();
    }

    public function insertBesoinArgent(int $id_sinistre, float $montant): int {
        $stmt = $this->db->prepare(
            "INSERT INTO besoin_argent (id_sinistre, montant_necessaire)
             VALUES (:id_sinistre, :montant)"
        );
        $stmt->execute([
            ':id_sinistre' => $id_sinistre,
            ':montant'     => $montant,
        ]);
        return (int) $this->db->lastInsertId();
    }

    // ──────────────────────────────────────────────────────────
    // Gestion des produits de référence
    // ──────────────────────────────────────────────────────────

    public function getAllProduits() {
        $stmt = $this->db->query(
            "SELECT p.*, cb.nom AS categorie 
             FROM produit p
             JOIN categorie_besoin cb ON p.id_categorie = cb.id_categorie
             ORDER BY cb.nom, p.nom_produit"
        );
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getProduitsParCategorie($id_categorie) {
        $stmt = $this->db->prepare(
            "SELECT * FROM produit 
             WHERE id_categorie = ? 
             ORDER BY nom_produit"
        );
        $stmt->execute([$id_categorie]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getProduitById($id_produit) {
        $stmt = $this->db->prepare(
            "SELECT p.*, cb.nom AS categorie 
             FROM produit p
             JOIN categorie_besoin cb ON p.id_categorie = cb.id_categorie
             WHERE p.id_produit = ?"
        );
        $stmt->execute([$id_produit]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // ──────────────────────────────────────────────────────────
    // Stock global de dons (non attribués)
    // ──────────────────────────────────────────────────────────
    
    public function insertDonStockMateriel($id_produit, $quantite) {
        // Vérifier si le produit existe déjà dans le stock
        $stmt = $this->db->prepare(
            "SELECT id_stock, quantite_disponible FROM don_stock_materiel WHERE id_produit = ?"
        );
        $stmt->execute([$id_produit]);
        $existing = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existing) {
            // Mettre à jour la quantité existante
            $stmt = $this->db->prepare(
                "UPDATE don_stock_materiel 
                 SET quantite_disponible = quantite_disponible + ?,
                     date_don = CURRENT_TIMESTAMP
                 WHERE id_stock = ?"
            );
            $stmt->execute([$quantite, $existing['id_stock']]);
            return $existing['id_stock'];
        } else {
            // Insérer un nouveau stock
            $stmt = $this->db->prepare(
                "INSERT INTO don_stock_materiel (id_produit, quantite_disponible) 
                 VALUES (?, ?)"
            );
            $stmt->execute([$id_produit, $quantite]);
            return $this->db->lastInsertId();
        }
    }

    public function insertDonStockArgent($montant) {
        $stmt = $this->db->prepare(
            "INSERT INTO don_stock_argent (montant_disponible) VALUES (?)"
        );
        $stmt->execute([$montant]);
        return $this->db->lastInsertId();
    }

    public function getStockMateriel() {
        $stmt = $this->db->query(
            "SELECT s.id_stock, s.id_produit, s.quantite_disponible, s.date_don,
                    p.nom_produit, p.unite_standard AS unite, c.nom AS categorie, c.id_categorie
             FROM don_stock_materiel s
             JOIN produit p ON s.id_produit = p.id_produit
             JOIN categorie_besoin c ON p.id_categorie = c.id_categorie
             WHERE s.quantite_disponible > 0
             ORDER BY c.nom, p.nom_produit"
        );
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getStockArgent() {
        $stmt = $this->db->query(
            "SELECT * FROM don_stock_argent 
             WHERE montant_disponible > 0
             ORDER BY date_don DESC"
        );
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function diminuerStockMateriel($id_produit, $quantite) {
        $stmt = $this->db->prepare(
            "UPDATE don_stock_materiel 
             SET quantite_disponible = quantite_disponible - ?  
             WHERE id_produit = ? AND quantite_disponible >= ?"
        );
        $stmt->execute([$quantite, $id_produit, $quantite]);
        return $stmt->rowCount() > 0;
    }

    public function diminuerStockArgent($id_stock_argent, $montant) {
        $stmt = $this->db->prepare(
            "UPDATE don_stock_argent 
             SET montant_disponible = montant_disponible - ?
             WHERE id_stock_argent = ? AND montant_disponible >= ?"
        );
        $stmt->execute([$montant, $id_stock_argent, $montant]);
        return $stmt->rowCount() > 0;
    }

    // ──────────────────────────────────────────────────────────
    // Sinistre par ville (retourne le 1er sinistre)
    // ──────────────────────────────────────────────────────────

    public function getSinistreByVille($id_ville) {
        $stmt = $this->db->prepare(
            "SELECT id_sinistre FROM sinistre WHERE id_ville = ? LIMIT 1"
        );
        $stmt->execute([$id_ville]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getBesoinById($id_besoin) {
        $stmt = $this->db->prepare(
            "SELECT bm.id_besoin, bm.id_sinistre, bm.id_categorie, bm.nom_besoin, 
                    bm.quantite, bm.unite,
                    COALESCE(bm.prix_unitaire, p.prix_unitaire, 0) AS prix_unitaire,
                    cb.nom AS categorie
             FROM besoin_materiaux bm
             JOIN categorie_besoin cb ON cb.id_categorie = bm.id_categorie
             LEFT JOIN produit p ON LOWER(TRIM(bm.nom_besoin)) = LOWER(TRIM(p.nom_produit))
             WHERE bm.id_besoin = ?"
        );
        $stmt->execute([$id_besoin]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // ──────────────────────────────────────────────────────────
    // Inventaire par sinistre
    // ──────────────────────────────────────────────────────────

    public function getInventaireArgentBySinistre($id_sinistre) {
        $stmt = $this->db->prepare(
            "SELECT id_inventaire_argent, montant_disponible
             FROM inventaire_argent WHERE id_sinistre = ?"
        );
        $stmt->execute([$id_sinistre]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getInventaireMateriauxBySinistre($id_sinistre) {
        $stmt = $this->db->prepare(
            "SELECT im.id_inventaire, im.id_besoin, bm.nom_besoin, 
                    im.quantite_disponible, bm.unite, bm.prix_unitaire
             FROM inventaire_materiaux im
             JOIN besoin_materiaux bm ON bm.id_besoin = im.id_besoin
             WHERE im.id_sinistre = ?
             ORDER BY bm.nom_besoin"
        );
        $stmt->execute([$id_sinistre]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateInventaireArgentApresAchat($id_sinistre, $montant_utilise) {
        $stmt = $this->db->prepare(
            "UPDATE inventaire_argent
             SET montant_disponible = montant_disponible - ?
             WHERE id_sinistre = ? AND montant_disponible >= ?"
        );
        $stmt->execute([$montant_utilise, $id_sinistre, $montant_utilise]);
        return $stmt->rowCount() > 0;
    }

    // Ajouter un don argent à l'inventaire du sinistre (upsert)
    public function ajouterInventaireArgent($id_sinistre, $montant) {
        $stmt = $this->db->prepare(
            "INSERT INTO inventaire_argent (id_sinistre, montant_disponible)
             VALUES (?, ?)
             ON DUPLICATE KEY UPDATE montant_disponible = montant_disponible + VALUES(montant_disponible)"
        );
        return $stmt->execute([$id_sinistre, $montant]);
    }

    // ──────────────────────────────────────────────────────────
    // Achats matériaux
    // ──────────────────────────────────────────────────────────

    public function insertAchatMateriaux($id_sinistre, $id_besoin, $quantite_achetee, $prix_unitaire, $prix_total) {
        $stmt = $this->db->prepare(
            "INSERT INTO achat_materiaux (id_sinistre, id_besoin, quantite_achetee, prix_unitaire, prix_total)
             VALUES (?, ?, ?, ?, ?)"
        );
        return $stmt->execute([$id_sinistre, $id_besoin, $quantite_achetee, $prix_unitaire, $prix_total]);
    }

    public function getAchatsBySinistre($id_sinistre) {
        $stmt = $this->db->prepare(
            "SELECT am.id_achat, am.quantite_achetee, am.prix_unitaire, am.prix_total, am.date_achat,
                    bm.nom_besoin, cb.nom AS categorie, bm.unite
             FROM achat_materiaux am
             JOIN besoin_materiaux bm ON bm.id_besoin = am.id_besoin
             JOIN categorie_besoin cb ON cb.id_categorie = bm.id_categorie
             WHERE am.id_sinistre = ?
             ORDER BY am.date_achat DESC"
        );
        $stmt->execute([$id_sinistre]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function reduceBesoinQuantite($id_besoin, $quantite_reduite) {
        $stmt = $this->db->prepare(
            "UPDATE besoin_materiaux SET quantite = quantite - ? WHERE id_besoin = ?"
        );
        return $stmt->execute([$quantite_reduite, $id_besoin]);
    }

    // ──────────────────────────────────────────────────────────
    // Historiques
    // ──────────────────────────────────────────────────────────

    public function insertHistoriqueDonMateriaux($id_sinistre, $id_ville, $id_besoin, $quantite, $unite, $descriptif = null) {
        $stmt = $this->db->prepare(
            "INSERT INTO historique_dons_materiaux (id_sinistre, id_ville, id_besoin, quantite_donnee, unite, descriptif)
             VALUES (?, ?, ?, ?, ?, ?)"
        );
        return $stmt->execute([$id_sinistre, $id_ville, $id_besoin, $quantite, $unite, $descriptif]);
    }

    public function insertHistoriqueDonArgent($id_sinistre, $id_ville, $montant, $descriptif = null) {
        $stmt = $this->db->prepare(
            "INSERT INTO historique_dons_argent (id_sinistre, id_ville, montant_donne, descriptif)
             VALUES (?, ?, ?, ?)"
        );
        return $stmt->execute([$id_sinistre, $id_ville, $montant, $descriptif]);
    }

    public function insertHistoriqueUsedArgent($id_sinistre, $id_besoin, $montant, $quantite, $descriptif = null) {
        $stmt = $this->db->prepare(
            "INSERT INTO historique_used_argent (id_sinistre, id_besoin, montant_utilise, quantite_achetee, descriptif)
             VALUES (?, ?, ?, ?, ?)"
        );
        return $stmt->execute([$id_sinistre, $id_besoin, $montant, $quantite, $descriptif]);
    }

    // ──────────────────────────────────────────────────────────
    // Dashboard — montants d'achats par ville
    // ──────────────────────────────────────────────────────────

    public function getTotalAchatsParVille() {
        $stmt = $this->db->query(
            "SELECT v.id_ville, v.nom_ville, COALESCE(SUM(am.prix_total), 0) AS total_achats
             FROM ville v
             LEFT JOIN sinistre s ON s.id_ville = v.id_ville
             LEFT JOIN achat_materiaux am ON am.id_sinistre = s.id_sinistre
             GROUP BY v.id_ville, v.nom_ville
             HAVING total_achats > 0
             ORDER BY total_achats DESC"
        );
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Besoins matériaux par ville AVEC prix_unitaire (pour la page achat)
    public function getBesoinsMateriauxByVilleAvecPrix($id_ville) {
        $stmt = $this->db->prepare(
            "SELECT bm.id_besoin, cb.nom AS categorie, bm.nom_besoin, bm.quantite, bm.unite, 
                    COALESCE(bm.prix_unitaire, p.prix_unitaire, 0) AS prix_unitaire
             FROM besoin_materiaux bm
             JOIN sinistre s ON s.id_sinistre = bm.id_sinistre
             JOIN categorie_besoin cb ON cb.id_categorie = bm.id_categorie
             LEFT JOIN produit p ON p.nom_produit = bm.nom_besoin
             WHERE s.id_ville = ?
             ORDER BY cb.nom, bm.nom_besoin"
        );
        $stmt->execute([$id_ville]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Restant matériaux avec prise en compte des achats
    public function getRestantMateriauxByVilleAvecAchats($id_ville) {
        $stmt = $this->db->prepare(
            "SELECT bm.id_besoin, cb.nom AS categorie, bm.nom_besoin,
                    (bm.quantite 
                     - COALESCE((SELECT SUM(dm.quantite_donnee) FROM don_materiaux dm WHERE dm.id_besoin = bm.id_besoin), 0)
                     - COALESCE((SELECT SUM(am.quantite_achetee) FROM achat_materiaux am WHERE am.id_besoin = bm.id_besoin), 0)
                    ) AS quantite_restante, bm.unite
             FROM besoin_materiaux bm
             JOIN sinistre s ON s.id_sinistre = bm.id_sinistre
             JOIN categorie_besoin cb ON cb.id_categorie = bm.id_categorie
             WHERE s.id_ville = ?
             ORDER BY bm.id_besoin"
        );
        $stmt->execute([$id_ville]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ──────────────────────────────────────────────────────────
    // Récapitulation globale
    // ──────────────────────────────────────────────────────────

    public function getRecapData() {
        $data = [];

        // 1) Besoins totaux en montant
        // Matériaux : quantite × prix_unitaire (fallback produit)
        $stmt = $this->db->query(
            "SELECT COALESCE(SUM(bm.quantite * COALESCE(bm.prix_unitaire, p.prix_unitaire, 0)), 0) AS total
             FROM besoin_materiaux bm
             LEFT JOIN produit p ON LOWER(TRIM(bm.nom_besoin)) = LOWER(TRIM(p.nom_produit))"
        );
        $data['besoins_materiaux_montant'] = (float)$stmt->fetchColumn();

        // Argent : somme montant_necessaire
        $stmt = $this->db->query("SELECT COALESCE(SUM(montant_necessaire), 0) FROM besoin_argent");
        $data['besoins_argent_montant'] = (float)$stmt->fetchColumn();

        $data['besoins_total'] = $data['besoins_materiaux_montant'] + $data['besoins_argent_montant'];

        // 2) Besoins satisfaits en montant
        // Matériaux satisfaits = dons attribués (don_materiaux) + achats (achat_materiaux)
        $stmt = $this->db->query(
            "SELECT COALESCE(SUM(dm.quantite_donnee * COALESCE(bm.prix_unitaire, p.prix_unitaire, 0)), 0) AS total
             FROM don_materiaux dm
             JOIN besoin_materiaux bm ON bm.id_besoin = dm.id_besoin
             LEFT JOIN produit p ON LOWER(TRIM(bm.nom_besoin)) = LOWER(TRIM(p.nom_produit))"
        );
        $data['satisfaits_dons_mat'] = (float)$stmt->fetchColumn();

        $stmt = $this->db->query("SELECT COALESCE(SUM(prix_total), 0) FROM achat_materiaux");
        $data['satisfaits_achats_mat'] = (float)$stmt->fetchColumn();

        // Argent satisfait = dons argent attribués aux besoins
        $stmt = $this->db->query("SELECT COALESCE(SUM(montant_donne), 0) FROM don_argent");
        $data['satisfaits_argent'] = (float)$stmt->fetchColumn();

        $data['satisfaits_total'] = $data['satisfaits_dons_mat'] + $data['satisfaits_achats_mat'] + $data['satisfaits_argent'];

        // 3) Dons reçus en montant
        // Stock matériel reçu : quantite_disponible × prix_unitaire du produit
        $stmt = $this->db->query(
            "SELECT COALESCE(SUM(dsm.quantite_disponible * COALESCE(p.prix_unitaire, 0)), 0) AS total
             FROM don_stock_materiel dsm
             JOIN produit p ON p.id_produit = dsm.id_produit"
        );
        $data['dons_stock_mat'] = (float)$stmt->fetchColumn();

        // Stock argent reçu
        $stmt = $this->db->query("SELECT COALESCE(SUM(montant_disponible), 0) FROM don_stock_argent");
        $data['dons_stock_argent'] = (float)$stmt->fetchColumn();

        $data['dons_recus_total'] = $data['dons_stock_mat'] + $data['dons_stock_argent'];

        // 4) Dons dispatchés en montant
        // Matériaux attribués à des besoins
        $data['dispatches_mat'] = $data['satisfaits_dons_mat']; // même valeur

        // Argent attribué à des besoins
        $data['dispatches_argent'] = $data['satisfaits_argent'];

        // Achats effectués
        $data['dispatches_achats'] = $data['satisfaits_achats_mat'];

        $data['dispatches_total'] = $data['dispatches_mat'] + $data['dispatches_argent'] + $data['dispatches_achats'];

        // 5) Taux de satisfaction
        $data['taux_satisfaction'] = $data['besoins_total'] > 0
            ? round(($data['satisfaits_total'] / $data['besoins_total']) * 100, 1)
            : 0;

        return $data;
    }
}
?>
