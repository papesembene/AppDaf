

CREATE TABLE IF NOT EXISTS citoyens (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    nci VARCHAR(20) UNIQUE NOT NULL,
                    nom VARCHAR(100) NOT NULL,
                    prenom VARCHAR(100) NOT NULL,
                    date_naissance DATE NOT NULL,
                    lieu_naissance VARCHAR(255) NOT NULL,
                    url_recto TEXT NOT NULL,
                    url_verso TEXT NOT NULL,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


             

CREATE TABLE IF NOT EXISTS journal (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    nci_recherche VARCHAR(20) NOT NULL,
                    ip VARCHAR(45) NOT NULL,
                    localisation VARCHAR(255),
                    statut ENUM('success', 'error') NOT NULL,
                    message TEXT,
                    date_recherche TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;