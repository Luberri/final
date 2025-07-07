DROP DATABASE tp_flight;
CREATE DATABASE tp_flight;
use tp_flight;
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
    detail TEXT
);

-- Table FondHistorique/
CREATE TABLE fond_historique (
    id INT AUTO_INCREMENT PRIMARY KEY,
    montant DECIMAL(12,2),
    date_heure TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fond_detail_id INT,
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
INSERT INTO type_pret (nom, detail, taux) VALUES
('Pret Personnel', 'Pret non affecte a un achat precis, utilise pour des besoins personnels (voyage, mariage, etc.).', 8.50),
('Pret Immobilier', 'Pret destine a financer l achat ou la construction d un bien immobilier.', 4.20),
('Credit Auto', 'Pret pour financer l achat d un vehicule neuf ou d occasion.', 6.75),
('Pret etudiant', 'Pret accorde aux etudiants pour financer leurs etudes et frais de vie.', 3.00),
('Credit a la Consommation', 'Pret a court terme pour l achat de biens de consommation (electromenager, meubles, etc.).', 9.10),
('Pret Travaux', 'Pret pour financer des travaux de renovation ou d amelioration du logement.', 5.80),
('Microcredit', 'Petit pret destine aux personnes n ayant pas acces aux prets bancaires classiques.', 12.00);


INSERT INTO admin (nom, mdp) VALUES
('admin1', 'admin123');

INSERT INTO client (nom, prenom, mail, mdp, date_naissance) VALUES
('Rasolofoson', 'Jean', 'jean.rasolofoson@mail.com', 'mdp1234', '1990-03-15'),
('Andriamihaja', 'Miora', 'miora.andria@mail.com', 'mdp1234', '1985-07-22'),
('Randriamalala', 'Tiana', 'tiana.randriamalala@mail.com', 'mdp1234', '1998-12-02'),
('Rakoto', 'Hery', 'hery.rakoto@mail.com', 'mdp1234', '2001-01-30'),
('Raharinirina', 'Nomena', 'nomena.raharinirina@mail.com', 'mdp1234', '1995-06-10');
