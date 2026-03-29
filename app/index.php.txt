<?php
require_once 'includes/config.php';

// Recuperer tous les articles
$stmt = $pdo->query("SELECT id, titre, slug, contenu, meta_description, date_creation FROM article ORDER BY date_creation DESC");
$articles = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Actualites et analyses sur le conflit en Iran - Informations, contexte historique et impact humanitaire.">
    <title>Le Monde - Conflit en Iran</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: Georgia, 'Times New Roman', serif;
            line-height: 1.6;
            color: #1a1a1a;
            background: #f5f5f5;
        }
        header {
            background: #1a1a1a;
            color: white;
            padding: 20px 0;
            text-align: center;
        }
        header h1 { font-size: 2.5rem; font-weight: normal; }
        nav {
            background: #333;
            padding: 10px 0;
            text-align: center;
        }
        nav a {
            color: white;
            text-decoration: none;
            margin: 0 15px;
            font-size: 0.9rem;
            text-transform: uppercase;
        }
        nav a:hover { text-decoration: underline; }
        main {
            max-width: 900px;
            margin: 40px auto;
            padding: 0 20px;
        }
        .article-card {
            background: white;
            padding: 30px;
            margin-bottom: 20px;
            border-bottom: 1px solid #ddd;
        }
        .article-card h2 {
            font-size: 1.5rem;
            margin-bottom: 10px;
        }
        .article-card h2 a {
            color: #1a1a1a;
            text-decoration: none;
        }
        .article-card h2 a:hover { text-decoration: underline; }
        .article-card .meta {
            color: #666;
            font-size: 0.85rem;
            margin-bottom: 10px;
        }
        .article-card p {
            color: #444;
        }
        footer {
            background: #1a1a1a;
            color: #aaa;
            text-align: center;
            padding: 20px;
            margin-top: 40px;
        }
    </style>
</head>
<body>
    <header>
        <h1>Le Monde</h1>
    </header>
    <nav>
        <a href="/">Accueil</a>
        <a href="#">International</a>
        <a href="#">Politique</a>
        <a href="#">Economie</a>
    </nav>
    <main>
        <h2 style="margin-bottom: 30px; font-size: 1.8rem;">Dossier : Conflit en Iran</h2>

        <?php foreach ($articles as $article): ?>
        <article class="article-card">
            <h2>
                <a href="/article/<?= $article['id'] ?>/<?= $article['slug'] ?>">
                    <?= htmlspecialchars($article['titre']) ?>
                </a>
            </h2> 
            <p class="meta">
                Publie le <?= date('d/m/Y', strtotime($article['date_creation'])) ?>
            </p>
            <p><?= htmlspecialchars($article['meta_description']) ?></p>
            <div class="contenu">
                <?= $article['contenu'] ?>
            </div>
        </article>
        <?php endforeach; ?>
    </main>
    <footer>
        <p>&copy; 2024 Le Monde - Projet SEO</p>
    </footer>
</body>
</html>
