INSERT INTO citoyens (nci, nom, prenom, date_naissance, lieu_naissance, url_recto, url_verso)
VALUES
('1234567890123', 'NDIAYE', 'Mamadou', '1990-05-15', 'Dakar', 'https://example.com/cni/mamadou_recto.jpg', 'https://example.com/cni/mamadou_verso.jpg'),
('9876543210987', 'DIOP', 'Awa', '1988-09-25', 'Saint-Louis', 'https://example.com/cni/awa_recto.jpg', 'https://example.com/cni/awa_verso.jpg'),
('4567891234567', 'BA', 'Ibrahima', '1995-12-10', 'Thiès', 'https://example.com/cni/ibrahima_recto.jpg', 'https://example.com/cni/ibrahima_verso.jpg');


INSERT INTO journal (nci_recherche, ip, localisation, statut, message)
VALUES
('1234567890123', '192.168.1.1', 'Dakar, Sénégal', 'success', 'Citoyen trouvé avec succès.'),
('1111111111111', '192.168.1.15', 'Ziguinchor, Sénégal', 'error', 'Aucun citoyen trouvé pour ce NCI.'),
('9876543210987', '192.168.1.45', 'Saint-Louis, Sénégal', 'success', 'Recherche réussie pour la citoyenne Awa Diop.');
