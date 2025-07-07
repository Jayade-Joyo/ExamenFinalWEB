-- Insertion pour EtablissementFinancier
INSERT INTO EtablissementFinancier (nom_etablissement, adresse, telephone, email, date_creation, solde)
VALUES ('Banque Nationale', '123 Avenue des Champs, Paris', '0142759632', 'contact@banquenationale.fr', '1990-05-15', 2500000.00);


-- Insertions pour TypePret (3 types)
INSERT INTO TypePret (nom_type, description, taux_interet, duree_max, montant_min, montant_max, actif)
VALUES 
('Prêt Personnel', 'Prêt à la consommation pour besoins personnels', 4.50, 60, 1000000.00, 3000000.00, TRUE),
('Prêt Immobilier', 'Prêt pour acquisition immobilière', 2.75, 300, 5000000.00, 10000000.00, TRUE),
('Prêt Étudiant', 'Prêt pour financer des études', 1.90, 84, 10000000.00, 30000000.00, TRUE);

-- Insertions pour Client (2 clients)
INSERT INTO Client (nom, prenom, date_naissance, adresse, telephone, email, profession, revenu_mensuel)
VALUES 
('Dupont', 'Jean', '1985-07-22', '45 Rue de la République, Lyon', '0645321897', 'jean.dupont@email.com', 'Ingénieur', 800000.00),
('Martin', 'Sophie', '1992-11-05', '12 Boulevard Voltaire, Marseille', '0789654321', 'sophie.martin@email.com', 'Enseignante', 1000000.00);

-- Insertion pour Pret
INSERT INTO Pret (
    id_client, 
    id_type_pret, 
    id_etablissement, 
    id_statut, 
    id_frequence, 
    montant, 
    date_debut, 
    date_fin, 
    taux_applique,
    montant_restant
)
VALUES (
    1, -- ID client Jean Dupont
    1, -- ID type Prêt Personnel
    1, -- ID établissement Banque Nationale
    (SELECT id_statut FROM StatutPret WHERE code_statut = 'APPROUVE'), -- Statut approuvé
    (SELECT id_frequence FROM FrequencePaiement WHERE code_frequence = 'MENSUEL'), -- Fréquence mensuelle
    12000000.00, -- Montant
    '2025-07-01', -- Date début
    '2026-07-01', -- Date fin (5 ans)
    4.50, -- Taux
    3000000.00 -- Montant restant initial
);

-- Optionnel: Insertion d'un remboursement associé
-- INSERT INTO Remboursement (
--     id_pret,
--     id_statut,
--     montant,
--     date_echeance,
--     mois,
--     annee
-- )
-- VALUES (
--     1, -- ID du prêt
--     (SELECT id_statut FROM StatutRemboursement WHERE code_statut = 'PAYE'), -- Statut payé
--     281.25, -- Montant (15000 * 4.5% / 12 mois)
--     '2023-02-15', -- Date échéance
--     2, -- Mois
--     2023  -- Année
-- );


INSERT INTO Remboursement (id_pret, id_statut, montant, date_echeance, mois, annee)
VALUES 
-- Année 1 (2025)
(1, (SELECT id_statut FROM StatutRemboursement WHERE code_statut = 'IMPAYE'), 55948.49, '2025-08-01', 8, 2025),
(1, (SELECT id_statut FROM StatutRemboursement WHERE code_statut = 'IMPAYE'), 55948.49, '2025-09-01', 9, 2025),
(1, (SELECT id_statut FROM StatutRemboursement WHERE code_statut = 'IMPAYE'), 55948.49, '2025-10-01', 10, 2025),
(1, (SELECT id_statut FROM StatutRemboursement WHERE code_statut = 'IMPAYE'), 55948.49, '2025-11-01', 11, 2025),
(1, (SELECT id_statut FROM StatutRemboursement WHERE code_statut = 'IMPAYE'), 55948.49, '2025-12-01', 12, 2025),