CREATE DATABASE IF NOT EXISTS banque;
USE banque;

-- Table des établissements financiers
CREATE TABLE EtablissementFinancier (
    id_etablissement INT PRIMARY KEY AUTO_INCREMENT,
    nom_etablissement VARCHAR(100) NOT NULL,
    adresse VARCHAR(200),
    telephone VARCHAR(20),
    email VARCHAR(100),
    date_creation DATE,
    solde DECIMAL(15, 2) DEFAULT 0
);

-- Table des types d'opérations
CREATE TABLE TypeOperation (
    id_type_operation INT PRIMARY KEY AUTO_INCREMENT,
    code_type VARCHAR(20) NOT NULL UNIQUE,
    libelle VARCHAR(50) NOT NULL,
    description VARCHAR(255)
);

INSERT INTO TypeOperation (code_type, libelle) VALUES 
('DEPOT', 'Dépôt de fonds'),
('RETRAIT', 'Retrait de fonds'),
('PRET', 'Prêt accordé');


CREATE TABLE HistoriqueMouvement (
    id_mouvement INT PRIMARY KEY AUTO_INCREMENT,
    id_etablissement INT NOT NULL,
    id_type_operation INT NOT NULL,
    montant DECIMAL(15, 2) NOT NULL,
    date_mouvement DATETIME DEFAULT CURRENT_TIMESTAMP,
    description VARCHAR(255),
    FOREIGN KEY (id_etablissement) REFERENCES EtablissementFinancier(id_etablissement),
    FOREIGN KEY (id_type_operation) REFERENCES TypeOperation(id_type_operation)
);

-- Table des opérations financières
CREATE TABLE OperationFonds (
    id_operation INT PRIMARY KEY AUTO_INCREMENT,
    id_etablissement INT NOT NULL,
    id_type_operation INT NOT NULL,
    montant DECIMAL(15, 2) NOT NULL,
    date_operation DATETIME DEFAULT CURRENT_TIMESTAMP,
    description VARCHAR(255),
    FOREIGN KEY (id_etablissement) REFERENCES EtablissementFinancier(id_etablissement),
    FOREIGN KEY (id_type_operation) REFERENCES TypeOperation(id_type_operation)
);

CREATE TABLE TypePret (
    id_type_pret INT PRIMARY KEY AUTO_INCREMENT,
    nom_type VARCHAR(100) NOT NULL,
    description TEXT,
    taux_interet DECIMAL(5, 2) NOT NULL,
    duree_max INT COMMENT 'Durée maximale en mois',
    montant_min DECIMAL(15, 2),
    montant_max DECIMAL(15, 2),
    actif BOOLEAN DEFAULT TRUE
);

-- Table des clients
CREATE TABLE Client (
    id_client INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(50) NOT NULL,
    prenom VARCHAR(50) NOT NULL,
    date_naissance DATE,
    adresse VARCHAR(200),
    telephone VARCHAR(20),
    email VARCHAR(100),
    profession VARCHAR(100),
    revenu_mensuel DECIMAL(15, 2),
    date_inscription DATETIME DEFAULT CURRENT_TIMESTAMP
);


-- Table des statuts de prêt
CREATE TABLE StatutPret (
    id_statut INT PRIMARY KEY AUTO_INCREMENT,
    code_statut VARCHAR(20) NOT NULL UNIQUE,
    libelle VARCHAR(50) NOT NULL,
    description VARCHAR(255)
);

INSERT INTO StatutPret (code_statut, libelle) VALUES
('EN_ATTENTE', 'En attente de traitement'),
('APPROUVE', 'Prêt approuvé'),
('REJETE', 'Prêt rejeté'),
('REMBOURSE', 'Prêt remboursé');

-- Table des fréquences de paiement
CREATE TABLE FrequencePaiement (
    id_frequence INT PRIMARY KEY AUTO_INCREMENT,
    code_frequence VARCHAR(20) NOT NULL UNIQUE,
    libelle VARCHAR(50) NOT NULL,
    nb_mois INT NOT NULL COMMENT 'Nombre de mois entre chaque paiement'
);

INSERT INTO FrequencePaiement (code_frequence, libelle, nb_mois) VALUES
('MENSUEL', 'Mensuel', 1),
('TRIMESTRIEL', 'Trimestriel', 3),
('ANNUEL', 'Annuel', 12);

-- Table des prêts
CREATE TABLE Pret (
    id_pret INT PRIMARY KEY AUTO_INCREMENT,
    id_client INT NOT NULL,
    id_type_pret INT NOT NULL,
    id_etablissement INT NOT NULL,
    id_statut INT NOT NULL DEFAULT 1,
    id_frequence INT NOT NULL DEFAULT 1,
    montant DECIMAL(15, 2) NOT NULL,
    date_debut DATE NOT NULL,
    date_fin DATE NOT NULL,
    taux_applique DECIMAL(5, 2) NOT NULL,
    montant_restant DECIMAL(15, 2),
    date_creation DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_client) REFERENCES Client(id_client),
    FOREIGN KEY (id_type_pret) REFERENCES TypePret(id_type_pret),
    FOREIGN KEY (id_etablissement) REFERENCES EtablissementFinancier(id_etablissement),
    FOREIGN KEY (id_statut) REFERENCES StatutPret(id_statut),
    FOREIGN KEY (id_frequence) REFERENCES FrequencePaiement(id_frequence)
);

-- Table des statuts de remboursement
CREATE TABLE StatutRemboursement (
    id_statut INT PRIMARY KEY AUTO_INCREMENT,
    code_statut VARCHAR(20) NOT NULL UNIQUE,
    libelle VARCHAR(50) NOT NULL,
    description VARCHAR(255)
);

INSERT INTO StatutRemboursement (code_statut, libelle) VALUES
('PAYE', 'Paiement effectué'),
('EN_RETARD', 'Paiement en retard'),
('IMPAYE', 'Paiement impayé');



CREATE TABLE Remboursement (
    id_remboursement INT PRIMARY KEY AUTO_INCREMENT,
    id_pret INT NOT NULL,
    id_statut INT NOT NULL DEFAULT 1,
    
    -- Champs de l'image
    base DECIMAL(15, 2) NOT NULL COMMENT 'Capital restant avant ce remboursement',
    interet DECIMAL(15, 2) NOT NULL COMMENT 'Montant des intérêts payés',
    amortissement DECIMAL(15, 2) NOT NULL COMMENT 'Part du capital remboursé',
    annuite DECIMAL(15, 2) NOT NULL COMMENT 'Montant total du paiement (intérêt + amortissement)',
    val_fin DECIMAL(15, 2) NOT NULL COMMENT 'Capital restant après ce remboursement',
    
    -- Champs temporels existants conservés
    date_remboursement DATETIME DEFAULT CURRENT_TIMESTAMP,
    date_echeance DATE NOT NULL,
    mois TINYINT NOT NULL COMMENT 'Mois du remboursement (1-12)',
    annee SMALLINT NOT NULL COMMENT 'Année du remboursement (4 chiffres)',
    
    -- Clés étrangères
    FOREIGN KEY (id_pret) REFERENCES Pret(id_pret),
    FOREIGN KEY (id_statut) REFERENCES StatutRemboursement(id_statut),
    
    -- Index
    INDEX idx_mois_annee (mois, annee),
    INDEX idx_pret (id_pret)  -- Pour les requêtes par prêt
);

-- Table d'historique des taux
-- CREATE TABLE HistoriqueTaux (
--     id_historique INT PRIMARY KEY AUTO_INCREMENT,
--     id_type_pret INT NOT NULL,
--     ancien_taux DECIMAL(5, 2) NOT NULL,
--     nouveau_taux DECIMAL(5, 2) NOT NULL,
--     date_modification DATETIME DEFAULT CURRENT_TIMESTAMP,
--     auteur_modification VARCHAR(100),
--     FOREIGN KEY (id_type_pret) REFERENCES TypePret(id_type_pret)
-- );

--donnee de test
-- Insertion dans EtablissementFinancier
INSERT INTO EtablissementFinancier (nom_etablissement, adresse, telephone, email, date_creation, solde) VALUES
('Banque Nationale', '123 Avenue des Champs, Paris', '0142789654', 'contact@banquenationale.fr', '1990-05-15', 2500000.00),
('Caisse d\'Epargne Régionale', '456 Rue du Commerce, Lyon', '0478563214', 'accueil@caisse-epargne.fr', '1985-11-20', 1800000.00),
('Crédit Municipal', '789 Boulevard Voltaire, Marseille', '0491654328', 'info@credit-municipal.com', '2000-03-10', 1200000.00);

-- Insertion dans TypeOperation (déjà partiellement peuplé)
INSERT INTO TypeOperation (code_type, libelle, description) VALUES
('VIREMENT', 'Virement bancaire', 'Transfert d\'argent entre comptes'),
('FRAIS', 'Frais bancaires', 'Commission prélevée par la banque');

-- Insertion dans Client
INSERT INTO Client (nom, prenom, date_naissance, adresse, telephone, email, profession, revenu_mensuel, date_inscription) VALUES
('Dupont', 'Jean', '1980-05-15', '10 Rue de la Paix, Paris', '0612345678', 'jean.dupont@email.com', 'Ingénieur', 3500.00, '2020-01-15 09:30:00'),
('Martin', 'Sophie', '1975-08-22', '25 Avenue des Roses, Lyon', '0698765432', 'sophie.martin@email.com', 'Médecin', 6500.00, '2019-11-10 14:15:00'),
('Bernard', 'Pierre', '1990-02-28', '30 Boulevard des Oliviers, Marseille', '0678912345', 'pierre.bernard@email.com', 'Enseignant', 2200.00, '2021-03-05 10:45:00'),
('Petit', 'Marie', '1988-07-17', '15 Rue du Commerce, Lille', '0632145698', 'marie.petit@email.com', 'Architecte', 4200.00, '2020-07-22 16:20:00');

-- Insertion dans TypePret
INSERT INTO TypePret (nom_type, description, taux_interet, duree_max, montant_min, montant_max, actif) VALUES
('Prêt Personnel', 'Prêt pour besoins personnels', 3.50, 60, 1000.00, 50000.00, TRUE),
('Prêt Immobilier', 'Achat ou travaux immobiliers', 2.20, 240, 50000.00, 500000.00, TRUE),
('Prêt Étudiant', 'Financement des études', 1.90, 84, 500.00, 20000.00, TRUE),
('Crédit Renouvelable', 'Réserve d argent disponible', 5.50, 36, 500.00, 10000.00, FALSE);

-- Insertion dans Pret
INSERT INTO Pret (id_client, id_type_pret, id_etablissement, id_statut, id_frequence, montant, date_debut, date_fin, taux_applique, montant_restant) VALUES
(1, 1, 1, 2, 1, 15000.00, '2023-01-10', '2028-01-10', 3.50, 12000.00),
(2, 2, 2, 2, 1, 200000.00, '2022-06-15', '2042-06-15', 2.20, 195000.00),
(3, 3, 3, 1, 3, 8000.00, '2023-03-01', '2030-03-01', 1.90, 8000.00),
(4, 1, 1, 3, 1, 10000.00, '2021-11-20', '2024-11-20', 3.75, 0.00),
(1, 2, 2, 4, 1, 150000.00, '2018-05-10', '2038-05-10', 2.50, 0.00);

-- Insertion dans Remboursement
INSERT INTO Remboursement (id_pret, id_statut, montant, date_echeance, mois, annee) VALUES
(1, 1, 300.00, '2023-02-10', 2, 2023),
(1, 1, 300.00, '2023-03-10', 3, 2023),
(1, 1, 300.00, '2023-04-10', 4, 2023),
(1, 2, 300.00, '2023-05-10', 5, 2023),
(2, 1, 850.00, '2022-07-15', 7, 2022),
(2, 1, 850.00, '2022-08-15', 8, 2022),
(5, 1, 650.00, '2018-06-10', 6, 2018),
(5, 1, 650.00, '2018-07-10', 7, 2018),
(4, 1, 350.00, '2021-12-20', 12, 2021),
(4, 1, 350.00, '2022-01-20', 1, 2022);

-- Insertion dans HistoriqueMouvement
INSERT INTO HistoriqueMouvement (id_etablissement, id_type_operation, montant, description) VALUES
(1, 3, 15000.00, 'Octroi prêt personnel client Dupont'),
(2, 3, 200000.00, 'Octroi prêt immobilier client Martin'),
(1, 1, 5000.00, 'Dépôt espèces agence Paris'),
(2, 2, 200.00, 'Retrait DAB Lyon'),
(3, 1, 1200.00, 'Virement salaire');

-- Insertion dans OperationFonds
INSERT INTO OperationFonds (id_etablissement, id_type_operation, montant, description) VALUES
(1, 1, 5000.00, 'Dépôt initial'),
(1, 3, -15000.00, 'Déblocage prêt'),
(2, 1, 10000.00, 'Virement entreprise'),
(2, 3, -200000.00, 'Déblocage prêt immobilier'),
(3, 1, 500.00, 'Dépôt client');