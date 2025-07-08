-- Remboursement 1 (Août 2025)
INSERT INTO Remboursement (id_pret, id_statut, base, interet, amortissement, annuite, val_fin, date_echeance, date_remboursement, mois, annee)
VALUES (
    1, 
    (SELECT id_statut FROM StatutRemboursement WHERE code_statut = 'PAYE'), 
    2000000.00, 
    7500.00, 
    163303.57, 
    170803.57, 
    1836696.43,
    '2025-08-01', 
    '2025-08-01', 
    8, 
    2025
);

-- Remboursement 2 (Septembre 2025)
INSERT INTO Remboursement (id_pret, id_statut, base, interet, amortissement, annuite, val_fin, date_echeance, date_remboursement, mois, annee)
VALUES (
    1,
    (SELECT id_statut FROM StatutRemboursement WHERE code_statut = 'PAYE'),
    1836696.43,
    6887.61,
    163915.96,
    170803.57,
    1672780.47,
    '2025-09-01',
    '2025-09-01',
    9,
    2025
);

-- Remboursement 3 (Octobre 2025)
INSERT INTO Remboursement (id_pret, id_statut, base, interet, amortissement, annuite, val_fin, date_echeance, date_remboursement, mois, annee)
VALUES (
    1,
    (SELECT id_statut FROM StatutRemboursement WHERE code_statut = 'PAYE'),
    1672780.47,
    6272.93,
    164530.64,
    170803.57,
    1508249.83,
    '2025-10-01',
    '2025-10-01',
    10,
    2025
);

-- Remboursement 4 (Novembre 2025)
INSERT INTO Remboursement (id_pret, id_statut, base, interet, amortissement, annuite, val_fin, date_echeance, date_remboursement, mois, annee)
VALUES (
    1,
    (SELECT id_statut FROM StatutRemboursement WHERE code_statut = 'PAYE'),
    1508249.83,
    5655.94,
    165147.63,
    170803.57,
    1343102.20,
    '2025-11-01',
    '2025-11-01',
    11,
    2025
);

-- Remboursement 5 (Décembre 2025)
INSERT INTO Remboursement (id_pret, id_statut, base, interet, amortissement, annuite, val_fin, date_echeance, date_remboursement, mois, annee)
VALUES (
    1,
    (SELECT id_statut FROM StatutRemboursement WHERE code_statut = 'PAYE'),
    1343102.20,
    5036.63,
    165766.94,
    170803.57,
    1177335.26,
    '2025-12-01',
    '2025-12-01',
    12,
    2025
);

-- Remboursement 6 (Janvier 2026)
INSERT INTO Remboursement (id_pret, id_statut, base, interet, amortissement, annuite, val_fin, date_echeance, date_remboursement, mois, annee)
VALUES (
    1,
    (SELECT id_statut FROM StatutRemboursement WHERE code_statut = 'PAYE'),
    1177335.26,
    4415.01,
    166388.56,
    170803.57,
    1010946.70,
    '2026-01-01',
    '2026-01-01',
    1,
    2026
);

-- Remboursement 7 (Février 2026)
INSERT INTO Remboursement (id_pret, id_statut, base, interet, amortissement, annuite, val_fin, date_echeance, date_remboursement, mois, annee)
VALUES (
    1,
    (SELECT id_statut FROM StatutRemboursement WHERE code_statut = 'PAYE'),
    1010946.70,
    3791.05,
    167012.52,
    170803.57,
    843934.18,
    '2026-02-01',
    '2026-02-01',
    2,
    2026
);

-- Remboursement 8 (Mars 2026)
INSERT INTO Remboursement (id_pret, id_statut, base, interet, amortissement, annuite, val_fin, date_echeance, date_remboursement, mois, annee)
VALUES (
    1,
    (SELECT id_statut FROM StatutRemboursement WHERE code_statut = 'PAYE'),
    843934.18,
    3164.75,
    167638.82,
    170803.57,
    676295.36,
    '2026-03-01',
    '2026-03-01',
    3,
    2026
);

-- Remboursement 9 (Avril 2026)
INSERT INTO Remboursement (id_pret, id_statut, base, interet, amortissement, annuite, val_fin, date_echeance, date_remboursement, mois, annee)
VALUES (
    1,
    (SELECT id_statut FROM StatutRemboursement WHERE code_statut = 'PAYE'),
    676295.36,
    2536.11,
    168267.46,
    170803.57,
    508027.90,
    '2026-04-01',
    '2026-04-01',
    4,
    2026
);

-- Remboursement 10 (Mai 2026)
INSERT INTO Remboursement (id_pret, id_statut, base, interet, amortissement, annuite, val_fin, date_echeance, date_remboursement, mois, annee)
VALUES (
    1,
    (SELECT id_statut FROM StatutRemboursement WHERE code_statut = 'PAYE'),
    508027.90,
    1905.10,
    168898.47,
    170803.57,
    339129.43,
    '2026-05-01',
    '2026-05-01',
    5,
    2026
);

-- Remboursement 11 (Juin 2026)
INSERT INTO Remboursement (id_pret, id_statut, base, interet, amortissement, annuite, val_fin, date_echeance, date_remboursement, mois, annee)
VALUES (
    1,
    (SELECT id_statut FROM StatutRemboursement WHERE code_statut = 'PAYE'),
    339129.43,
    1271.74,
    169531.83,
    170803.57,
    169597.60,
    '2026-06-01',
    '2026-06-01',
    6,
    2026
);

-- Remboursement 12 (Juillet 2026)
INSERT INTO Remboursement (id_pret, id_statut, base, interet, amortissement, annuite, val_fin, date_echeance, date_remboursement, mois, annee)
VALUES (
    1,
    (SELECT id_statut FROM StatutRemboursement WHERE code_statut = 'PAYE'),
    169597.60,
    635.99,
    170167.58,
    170803.57,
    0.00,
    '2026-07-01',
    '2026-07-01',
    7,
    2026
);