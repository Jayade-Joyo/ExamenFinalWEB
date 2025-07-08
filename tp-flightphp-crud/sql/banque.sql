DROP DATABASE IF EXISTS banque;
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

-- SELECT * FROM Remboursement WHERE (annee > 2026 OR (annee = 2026 AND mois >= 2)) AND (annee < 2026 OR (annee = 2026 AND mois <= 5));
-- SELECT * FROM Remboursement WHERE (annee > 2026 OR (annee = 2026 AND mois >= 2)) AND (annee < 2026 OR (annee = 2026 AND mois <= 5));
