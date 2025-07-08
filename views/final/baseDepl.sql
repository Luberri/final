DELETE TABLE IF EXISTS admin , client , type_pret , client_detail , fond,fond_detail , fond_historique , type_remboursement , status_pret , pret , remboursement;
CREATE TABLE admin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100),
    mdp VARCHAR(255)
);

-- Table Client
CREATE TABLE client (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100),
    prenom VARCHAR(100),
    mail VARCHAR(150),
    mdp VARCHAR(255),
    date_naissance DATE
);

-- Table TypePret
CREATE TABLE type_pret (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100),
    detail TEXT,
    taux DECIMAL(12,2)
);

-- Table ClientDetail
CREATE TABLE client_detail (
    id INT AUTO_INCREMENT PRIMARY KEY,
    client_id INT,
    profession VARCHAR(100),
    revenu_mensuel DECIMAL(10,2),
    charge_mensuelle DECIMAL(10,2),
    FOREIGN KEY (client_id) REFERENCES client(id)
);

-- Table Fonds
CREATE TABLE fond (
    id INT AUTO_INCREMENT PRIMARY KEY,
    montant DECIMAL(12,2)
);

-- Table FondDetail
CREATE TABLE fond_detail (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fond_id INT,
    detail TEXT,
    FOREIGN KEY (fond_id) REFERENCES fond(id)
);

-- Table FondHistorique
CREATE TABLE fond_historique (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fond_detail_id INT,
    montant DECIMAL(12,2),
    date_heure TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (fond_detail_id) REFERENCES fond_detail(id)
);

-- Table TypeRemboursement
CREATE TABLE type_remboursement (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100),
    mois INT
);

-- Table StatusPret
CREATE TABLE status_pret (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_pret INT,
    nom VARCHAR(100),
    date DATE default CURRENT_DATE
);

-- Table Pret (CORRIGÉE - suppression de la référence status_pret_id qui n'existe pas)
CREATE TABLE pret (
    id INT AUTO_INCREMENT PRIMARY KEY,
    client_id INT,
    montant DECIMAL(12,2),
    duree INT,
    type_remboursement_id INT,
    type_pret_id INT,
    FOREIGN KEY (client_id) REFERENCES client(id),
    FOREIGN KEY (type_remboursement_id) REFERENCES type_remboursement(id),
    FOREIGN KEY (type_pret_id) REFERENCES type_pret(id)
);

-- Table Remboursement (maintenant que pret existe)
CREATE TABLE remboursement (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pret_id INT,
    montant DECIMAL(10,2),
    numero_mois INT,
    date_heure TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (pret_id) REFERENCES pret(id)
);

ALTER TABLE status_pret ADD COLUMN delai INT DEFAULT 0;

-- Insertion des données de base
INSERT INTO type_pret (nom, detail, taux) VALUES
('Pret Personnel', 'Pret non affecte a un achat precis, utilise pour des besoins personnels (voyage, mariage, etc.).', 8.50),
('Pret Immobilier', 'Pret destine a financer l achat ou la construction d un bien immobilier.', 4.20),
('Credit Auto', 'Pret pour financer l achat d un vehicule neuf ou d occasion.', 6.75),
('Pret etudiant', 'Pret accorde aux etudiants pour financer leurs etudes et frais de vie.', 3.00),
('Credit a la Consommation', 'Pret a court terme pour l achat de biens de consommation (electromenager, meubles, etc.).', 9.10),
('Pret Travaux', 'Pret pour financer des travaux de renovation ou d amelioration du logement.', 5.80),
('Microcredit', 'Petit pret destine aux personnes n ayant pas acces aux prets bancaires classiques.', 12.00);

INSERT INTO admin (nom, mdp) VALUES
('admin1', 'admin123'),
('superuser', 'supersecure');

INSERT INTO client (nom, prenom, mail, mdp, date_naissance) VALUES
('Rasolofoson', 'Jean', 'jean.rasolofoson@mail.com', 'mdp1234', '1990-03-15'),
('Andriamihaja', 'Miora', 'miora.andria@mail.com', 'motdepasse', '1985-07-22'),
('Randriamalala', 'Tiana', 'tiana.randriamalala@mail.com', 'tiana2024', '1998-12-02'),
('Rakoto', 'Hery', 'hery.rakoto@mail.com', 'azertyui', '2001-01-30'),
('Raharinirina', 'Nomena', 'nomena.raharinirina@mail.com', 'passw0rd', '1995-06-10');

INSERT INTO type_remboursement (nom, mois) VALUES
('Mensuel', 1),
('Trimestriel', 3),
('Semestriel', 6),
('Annuel', 12);

-- Insertion de données de test
INSERT INTO client_detail (client_id, profession, revenu_mensuel, charge_mensuelle) VALUES
(1, 'Ingénieur', 2500000, 800000),
(2, 'Professeur', 1800000, 600000),
(3, 'Commerçant', 3200000, 1200000);

INSERT INTO fond (montant) VALUES (50000000);
INSERT INTO fond_detail (fond_id, detail) VALUES (1, 'Fonds initial de la banque');

-- Insertion de quelques prêts d'exemple
INSERT INTO pret (client_id, montant, duree, type_remboursement_id, type_pret_id) VALUES
(1, 1200000, 12, 1, 1),  -- Prêt personnel de 1.2M sur 12 mois
(2, 5000000, 24, 1, 2),  -- Prêt immobilier de 5M sur 24 mois  
(3, 800000, 18, 1, 3);   -- Crédit auto de 800K sur 18 mois

-- Vue simplifiée pour calculer les intérêts mensuels
DROP VIEW IF EXISTS vue_interets_mensuels;
CREATE VIEW vue_interets_mensuels AS
SELECT 
    p.id as pret_id,
    c.nom,
    c.prenom,
    p.montant as capital_emprunte,
    tp.taux as taux_annuel,
    (tp.taux / 12 / 100) as taux_mensuel,
    p.duree,
    mois.numero_mois,
    DATE_ADD(CURDATE(), INTERVAL (mois.numero_mois - 1) MONTH) as date_mois,
    YEAR(DATE_ADD(CURDATE(), INTERVAL (mois.numero_mois - 1) MONTH)) as annee,
    MONTH(DATE_ADD(CURDATE(), INTERVAL (mois.numero_mois - 1) MONTH)) as mois,
    
    -- Capital restant simplifié
    ROUND(p.montant * POWER(1 + (tp.taux / 12 / 100), mois.numero_mois - 1), 2) as capital_restant,
    
    -- Intérêts du mois = capital restant × taux mensuel
    ROUND(p.montant * POWER(1 + (tp.taux / 12 / 100), mois.numero_mois - 1) * (tp.taux / 12 / 100), 2) as interets_mois,
    
    -- Mensualité constante
    ROUND(p.montant * ((tp.taux / 12 / 100) / (1 - POWER(1 + (tp.taux / 12 / 100), -p.duree))), 2) as mensualite_constante

FROM pret p
JOIN client c ON p.client_id = c.id
JOIN type_pret tp ON p.type_pret_id = tp.id
JOIN (
    SELECT 1 as numero_mois UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION 
    SELECT 7 UNION SELECT 8 UNION SELECT 9 UNION SELECT 10 UNION SELECT 11 UNION SELECT 12 UNION 
    SELECT 13 UNION SELECT 14 UNION SELECT 15 UNION SELECT 16 UNION SELECT 17 UNION SELECT 18 UNION 
    SELECT 19 UNION SELECT 20 UNION SELECT 21 UNION SELECT 22 UNION SELECT 23 UNION SELECT 24 UNION 
    SELECT 25 UNION SELECT 26 UNION SELECT 27 UNION SELECT 28 UNION SELECT 29 UNION SELECT 30 UNION 
    SELECT 31 UNION SELECT 32 UNION SELECT 33 UNION SELECT 34 UNION SELECT 35 UNION SELECT 36 UNION 
    SELECT 37 UNION SELECT 38 UNION SELECT 39 UNION SELECT 40 UNION SELECT 41 UNION SELECT 42 UNION 
    SELECT 43 UNION SELECT 44 UNION SELECT 45 UNION SELECT 46 UNION SELECT 47 UNION SELECT 48 UNION 
    SELECT 49 UNION SELECT 50 UNION SELECT 51 UNION SELECT 52 UNION SELECT 53 UNION SELECT 54 UNION 
    SELECT 55 UNION SELECT 56 UNION SELECT 57 UNION SELECT 58 UNION SELECT 59 UNION SELECT 60
) mois ON mois.numero_mois <= p.duree;

-- Vue pour agréger les intérêts par période
DROP VIEW IF EXISTS vue_interets_par_periode;
CREATE VIEW vue_interets_par_periode AS
SELECT 
    annee,
    mois,
    SUM(interets_mois) as total_interets_mois,
    COUNT(DISTINCT pret_id) as nombre_prets_actifs
FROM vue_interets_mensuels
GROUP BY annee, mois
ORDER BY annee DESC, mois DESC;

-- Vue corrigée pour calculer les fonds disponibles par mois
DROP VIEW IF EXISTS vue_fonds_disponibles;
CREATE VIEW vue_fonds_disponibles AS
SELECT 
    YEAR(dates.date_mois) as annee,
    MONTH(dates.date_mois) as mois,
    dates.date_mois,
    
    -- Fonds initiaux disponibles (cumul des ajouts de fonds jusqu'à cette date)
    COALESCE((
        SELECT SUM(fh.montant)
        FROM fond_historique fh
        WHERE DATE(fh.date_heure) <= dates.date_mois
    ), 0) as montant_initial,
    
    -- Montant total prêté ce mois (en utilisant la date de status_pret)
    COALESCE((
        SELECT SUM(p.montant)
        FROM pret p
        JOIN status_pret sp ON p.id = sp.id_pret
        WHERE YEAR(sp.date) = YEAR(dates.date_mois) 
        AND MONTH(sp.date) = MONTH(dates.date_mois)
        AND sp.nom = 'Accordé'  -- ou le statut qui indique la création du prêt
    ), 0) as montant_prete,
    
    -- Montant total remboursé ce mois
    COALESCE((
        SELECT SUM(r.montant)
        FROM remboursement r
        WHERE YEAR(r.date_heure) = YEAR(dates.date_mois) 
        AND MONTH(r.date_heure) = MONTH(dates.date_mois)
    ), 0) as montant_rembourse

FROM (
    -- Générer une série de dates mensuelles
    SELECT DATE_FORMAT(DATE_ADD(CURDATE(), INTERVAL -seq MONTH), '%Y-%m-01') as date_mois
    FROM (
        SELECT 0 as seq UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION
        SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9 UNION SELECT 10 UNION SELECT 11 UNION
        SELECT 12 UNION SELECT 13 UNION SELECT 14 UNION SELECT 15 UNION SELECT 16 UNION SELECT 17 UNION
        SELECT 18 UNION SELECT 19 UNION SELECT 20 UNION SELECT 21 UNION SELECT 22 UNION SELECT 23
    ) seq
) dates
ORDER BY dates.date_mois DESC;


-- Données de test pour faire fonctionner la fonctionnalité fonds disponibles

-- -- 1. Ajouter des données dans fond_historique (ajouts de fonds)
INSERT INTO fond_historique (fond_detail_id, montant, date_heure) VALUES
(1, 100000000, '2024-01-15 09:00:00'),  -- 100M en janvier
(1, 75000000, '2024-03-20 10:30:00'),   -- 75M en mars
(1, 50000000, '2024-06-10 14:00:00'),   -- 50M en juin
(1, 25000000, '2024-09-05 11:15:00'),   -- 25M en septembre
(1, 30000000, '2024-11-12 16:45:00');   -- 30M en novembre

-- -- 2. Ajouter des prêts avec des dates différentes
INSERT INTO pret (client_id, type_pret_id, montant, duree) VALUES
(1, 1, 5000000, 12),   -- 5M, 12 mois
(2, 2, 10000000, 24),  -- 10M, 24 mois
(3, 1, 3000000, 18),   -- 3M, 18 mois
(1, 3, 8000000, 36),   -- 8M, 36 mois
(2, 2, 12000000, 24),  -- 12M, 24 mois
(3, 1, 6000000, 12);   -- 6M, 12 mois

-- -- 3. Ajouter des statuts pour ces prêts avec des dates étalées
INSERT INTO status_pret (id_pret, nom, date) VALUES
-- Prêts accordés en 2024
(1, 'Accordé', '2024-02-10'),  -- Février
(2, 'Accordé', '2024-04-15'),  -- Avril
(3, 'Accordé', '2024-07-20'),  -- Juillet
(4, 'Accordé', '2024-08-25'),  -- Août
(5, 'Accordé', '2024-10-05'),  -- Octobre
(6, 'Accordé', '2024-11-18');

INSERT INTO status_pret (id_pret, nom, date) VALUES
(1, 'En cours', '2024-02-11'),
(2, 'En cours', '2024-04-16'),
(3, 'En cours', '2024-07-21'),
(4, 'En cours', '2024-08-26'),
(5, 'En cours', '2024-10-06'),
(6, 'En cours', '2024-11-19');

-- -- 4. Ajouter des remboursements étalés sur plusieurs mois
INSERT INTO remboursement (pret_id, montant, numero_mois, date_heure) VALUES
-- -- Remboursements pour le prêt 1 (5M)

(1, 450000, 1, '2024-03-10 00:00:00'),
(1, 450000, 2, '2024-04-10 00:00:00'),
(1, 450000, 3, '2024-05-10 00:00:00'),
(1, 450000, 4, '2024-06-10 00:00:00'),
(1, 450000, 5, '2024-07-10 00:00:00'),

-- -- Remboursements pour le prêt 2 (10M)
(2, 480000, 1, '2024-05-15 00:00:00'),
(2, 480000, 2, '2024-06-15 00:00:00'),
(2, 480000, 3, '2024-07-15 00:00:00'),
(2, 480000, 4, '2024-08-15 00:00:00'),
(2, 480000, 5, '2024-09-15 00:00:00'),
(2, 480000, 6, '2024-10-15 00:00:00'),

-- -- Remboursements pour le prêt 3 (3M)
(3, 180000, 1, '2024-08-20 00:00:00'),
(3, 180000, 2, '2024-09-20 00:00:00'),
(3, 180000, 3, '2024-10-20 00:00:00'),
(3, 180000, 4, '2024-11-20 00:00:00'),
(3, 180000, 5, '2024-12-20 00:00:00'),

-- -- Remboursements pour le prêt 4 (8M)
(4, 250000, 1, '2024-09-25 00:00:00'),
(4, 250000, 2, '2024-10-25 00:00:00'),
(4, 250000, 3, '2024-11-25 00:00:00'),
(4, 250000, 4, '2024-12-25 00:00:00'),

-- -- Remboursements pour le prêt 5 (12M)
(5, 580000, 1, '2024-11-05 00:00:00'),
(5, 580000, 2, '2024-12-05 00:00:00'),

-- Remboursements pour le prêt 6 (6M)
(6, 520000, 1, '2024-12-18 00:00:00');

-- Table Simulation
CREATE TABLE simulation (
    id INT AUTO_INCREMENT PRIMARY KEY,
    client_id INT,
    montant DECIMAL(12,2),
    duree INT,
    type_pret_id INT,
    taux_mensuel DECIMAL(8,6),
    delai_premier INT DEFAULT 0,
    assurance DECIMAL(5,2) DEFAULT 0,
    mensualite DECIMAL(12,2),
    total_interets DECIMAL(12,2),
    cout_total_assurance DECIMAL(12,2),
    date_simulation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (client_id) REFERENCES client(id),
    FOREIGN KEY (type_pret_id) REFERENCES type_pret(id)
);