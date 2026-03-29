-- ============================================
-- Base de données : guerre_iran
-- Script d'initialisation
-- ============================================

USE guerre_iran;

-- ============================================
-- Table des articles
-- ============================================
CREATE TABLE IF NOT EXISTS article (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titre VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    contenu LONGTEXT NOT NULL,
    meta_description VARCHAR(160) DEFAULT NULL,
    date_creation DATETIME DEFAULT CURRENT_TIMESTAMP,
    date_modification DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_slug (slug),
    INDEX idx_date (date_creation)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ============================================
-- Table des administrateurs (BackOffice)
-- ============================================
CREATE TABLE IF NOT EXISTS admin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    date_creation DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Insertion admin par defaut
-- Login: admin / Password: admin123
-- ============================================
INSERT INTO admin (username, password) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');
-- Note: Hash bcrypt de 'admin123'

-- ============================================
-- Articles de demonstration
-- ============================================
INSERT INTO article (titre, slug, contenu, meta_description, image_principale) VALUES
(
    'Contexte historique du conflit en Iran',
    'contexte-historique-conflit-iran',
    '<h2>Les origines du conflit</h2>
<p>Le conflit en Iran trouve ses racines dans une histoire complexe, melant enjeux geopolitiques, religieux et economiques.</p>
<h2>Les acteurs principaux</h2>
<p>Plusieurs puissances regionales et internationales sont impliquees dans ce conflit qui a des repercussions mondiales.</p>
<h3>Les enjeux energetiques</h3>
<p>Le petrole et le gaz naturel jouent un role central dans les dynamiques de ce conflit.</p>',
    'Decouvrez le contexte historique du conflit en Iran, ses origines et les acteurs principaux impliques.',
    NULL
),
(
    'Impact humanitaire de la guerre',
    'impact-humanitaire-guerre-iran',
    '<h2>Les consequences sur la population civile</h2>
<p>La guerre a entraine des deplacements massifs de population et une crise humanitaire sans precedent.</p>
<h2>Les besoins urgents</h2>
<p>Les organisations humanitaires alertent sur les besoins critiques en nourriture, eau et soins medicaux.</p>',
    'Analyse de l''impact humanitaire de la guerre en Iran sur les populations civiles et les besoins urgents.',
    NULL
),
(
    'Les negotiations diplomatiques en cours',
    'negociations-diplomatiques-iran',
    '<h2>Les tentatives de mediation</h2>
<p>Plusieurs pays tentent de jouer un role de mediateur pour mettre fin au conflit.</p>
<h2>Les obstacles a la paix</h2>
<p>De nombreux defis persistent pour parvenir a un accord durable.</p>',
    'Point sur les negotiations diplomatiques en cours concernant le conflit iranien.',
    NULL
);
