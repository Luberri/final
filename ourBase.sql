-- Table Client
CREATE TABLE client (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100),
    prenom VARCHAR(100),
    mail VARCHAR(150),
    mdp VARCHAR(255),
    date_naissance DATE
);

-- Table TypeTaux
CREATE TABLE type_taux (
    id INT AUTO_INCREMENT PRIMARY KEY,
    taux DECIMAL(5,2)
);

-- Table TypePret
CREATE TABLE type_pret (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100),
    detail TEXT,
    taux_id INT,
    FOREIGN KEY (taux_id) REFERENCES type_taux(id)
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
    fond_id INT,
    montant DECIMAL(12,2),
    date_heure TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (fond_id) REFERENCES fond(id)
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
    nom VARCHAR(100),
    date DATE
);

-- Table Pret
CREATE TABLE pret (
    id INT AUTO_INCREMENT PRIMARY KEY,
    client_id INT,
    montant DECIMAL(12,2),
    duree INT,
    type_remboursement_id INT,
    type_pret_id INT,
    status_pret_id INT,
    FOREIGN KEY (client_id) REFERENCES client(id),
    FOREIGN KEY (type_remboursement_id) REFERENCES type_remboursement(id),
    FOREIGN KEY (type_pret_id) REFERENCES type_pret(id),
    FOREIGN KEY (status_pret_id) REFERENCES status_pret(id)
);

-- Table Remboursement
CREATE TABLE remboursement (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pret_id INT,
    montant DECIMAL(10,2),
    numero_mois INT,
    date_heure TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (pret_id) REFERENCES pret(id)
);
