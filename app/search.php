<?php
require_once 'includes/config.php';

$query = trim($_GET['q'] ?? '');
$articles = [];
$total = 0;

if (!empty($query) && strlen($query) >= 2) {
    // Échapper la recherche pour éviter les injections SQL
    $search_term = '%' . $query . '%';
    
    // Recherche dans titre, slug, contenu, meta_description
    $sql = "
        SELECT id, titre, slug, meta_description, date_creation 
        FROM article 
        WHERE 
            titre LIKE :search 
            OR slug LIKE :search 
            OR contenu LIKE :search 
            OR meta_description LIKE :search
        ORDER BY date_creation DESC
    ";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':search' => $search_term]);
    $articles = $stmt->fetchAll();
    $total = count($articles);
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Résultats de recherche - Conflit en Iran">
  <title>Recherche - Conflit en Iran | Le Monde</title>

  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }

    body {
      font-family: Georgia, 'Times New Roman', serif;
      background: #f5f5f5;
      color: #1a1a1a;
      line-height: 1.6;
    }

    /* HEADER */
    header {
      background: #1a1a1a;
      color: white;
      padding: 30px 20px;
      border-bottom: 3px solid #c00;
      text-align: center;
    }
    header h1 {
      font-size: 2.8rem;
      font-weight: normal;
      letter-spacing: 2px;
    }
    header p {
      font-size: 0.9rem;
      color: #ddd;
      margin-top: 5px;
    }

    /* NAV */
    nav {
      background: #222;
      text-align: center;
      padding: 12px 0;
      position: sticky;
      top: 0;
      z-index: 100;
    }
    nav a {
      color: #fff;
      text-decoration: underline;
      margin: 0 20px;
      font-size: 0.85rem;
      text-transform: uppercase;
      letter-spacing: 1px;
      font-family: Arial, sans-serif;
    }
    nav a:hover { color: #c00; text-decoration: underline; text-decoration-thickness: 2px; }

    /* MAIN */
    main {
      max-width: 860px;
      margin: 40px auto;
      padding: 0 20px;
    }

    .search-info {
      background: white;
      padding: 20px 30px;
      margin-bottom: 30px;
      border-left: 4px solid #c00;
      border-radius: 4px;
    }
    .search-info h2 {
      font-size: 1.5rem;
      margin-bottom: 8px;
      font-weight: normal;
    }
    .search-info .query {
      color: #c00;
      font-weight: bold;
    }
    .search-info .count {
      color: #555;
      font-size: 0.9rem;
    }

    .search-form {
      background: white;
      padding: 20px 30px;
      margin-bottom: 30px;
      border-radius: 4px;
    }
    .search-form form {
      display: flex;
      gap: 10px;
    }
    .search-form input {
      flex: 1;
      padding: 10px 15px;
      border: 1px solid #ddd;
      border-radius: 4px;
      font-size: 0.95rem;
      font-family: Arial, sans-serif;
    }
    .search-form button {
      padding: 10px 25px;
      background: #c00;
      color: white;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      font-weight: bold;
      text-transform: uppercase;
      font-size: 0.85rem;
      font-family: Arial, sans-serif;
    }
    .search-form button:hover {
      background: #a00;
    }

    /* ARTICLE CARD */
    .article-card {
      background: white;
      padding: 25px 30px;
      margin-bottom: 15px;
      border-left: 4px solid transparent;
      transition: border-color 0.2s;
      display: flex;
      gap: 25px;
      align-items: flex-start;
    }
    .article-card:hover {
      border-left-color: #c00;
    }
    
    .article-card-content {
      flex: 1;
      min-width: 0;
    }
    
    .article-card-photo {
      flex: 0 0 280px;
      min-height: 200px;
    }
    
    .article-card-photo img {
      width: 100%;
      height: 200px;
      object-fit: cover;
      border-radius: 4px;
    }

    .article-card h2 {
      font-size: 1.4rem;
      margin-bottom: 8px;
      font-weight: normal;
    }
    .article-card h2 a {
      color: #1a1a1a;
      text-decoration: underline;
      text-decoration-color: transparent;
      transition: text-decoration-color 0.2s;
    }
    .article-card h2 a:hover {
      color: #c00;
      text-decoration-color: #c00;
    }

    .article-card .meta {
      font-size: 0.8rem;
      color: #555;
      font-family: Arial, sans-serif;
      margin-bottom: 10px;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    .article-card .description {
      color: #555;
      font-size: 0.95rem;
    }
    
    .article-card .highlight {
      background: #fffacd;
      padding: 2px 4px;
      border-radius: 2px;
    }

    .lire-plus {
      display: inline-block;
      margin-top: 12px;
      font-size: 0.85rem;
      color: #c00;
      text-decoration: none;
      font-family: Arial, sans-serif;
      font-weight: bold;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }
    .lire-plus:hover { text-decoration: underline; }

    .no-results {
      background: white;
      padding: 40px 30px;
      text-align: center;
      color: #555;
      border-radius: 4px;
    }
    .no-results p {
      font-style: italic;
      margin-bottom: 20px;
    }
    .no-results a {
      color: #c00;
      text-decoration: underline;
      font-weight: bold;
    }
    .no-results a:hover {
      text-decoration: underline;
    }

    /* Footer */
    footer {
      background: #1a1a1a;
      color: #aaa;
      text-align: center;
      padding: 25px;
      margin-top: 60px;
      font-family: Arial, sans-serif;
      font-size: 0.85rem;
    }
    footer span { color: #c00; }

    /* RESPONSIVE */
    @media (max-width: 768px) {
      .article-card {
        flex-direction: column;
      }
      .article-card-photo {
        flex: 0 0 auto;
      }
      .search-form form {
        flex-direction: column;
      }
    }
  </style>
</head>
<body>

  <!-- HEADER -->
  <header>
    <div class="title">
      <h1>Le Monde</h1>
      <p>Actualités &amp; Analyses — Conflit en Iran</p>
    </div>
  </header>

  <!-- NAV -->
  <nav>
    <a href="/">Accueil</a>
    <a href="#">International</a>
    <a href="#">Politique</a>
    <a href="#">Économie</a>
  </nav>

  <!-- MAIN -->
  <main>

    <div class="search-form">
      <form method="GET" action="/search.php">
        <input type="text" name="q" placeholder="Rechercher un article..." value="<?= htmlspecialchars($query) ?>" required>
        <button type="submit">🔍 Rechercher</button>
      </form>
    </div>

    <?php if (!empty($query)): ?>
      <div class="search-info">
        <h2>Résultats pour <span class="query"><?= htmlspecialchars($query) ?></span></h2>
        <p class="count"><?= $total ?> article<?= $total > 1 ? 's' : '' ?> trouvé<?= $total > 1 ? 's' : '' ?></p>
      </div>

      <?php if ($total > 0): ?>
        <?php foreach ($articles as $article): ?>
          <article class="article-card">
            
            <div class="article-card-content">
              <h2>
                <a href="/articles/<?= slugify($article['titre']) ?>-<?= $article['id'] ?>.html">
                  <?= htmlspecialchars($article['titre']) ?>
                </a>
              </h2>

              <p class="meta">
                Publié le <?= date('d/m/Y \à H\hi', strtotime($article['date_creation'])) ?>
              </p>
              
              <?php if (!empty($article['meta_description'])): ?>
                <p class="description">
                  <?= htmlspecialchars($article['meta_description']) ?>
                </p>
              <?php endif; ?>

              <a class="lire-plus"
                 href="/articles/<?= slugify($article['titre']) ?>-<?= $article['id'] ?>.html">
                Lire la suite →
              </a>
            </div>
            
            <?php 
            // Récupérer la PREMIÈRE photo (photo principale)
            $stmt_photo = $pdo->prepare("SELECT photos FROM photos WHERE id_article = :id_article ORDER BY date_ajout ASC LIMIT 1");
            $stmt_photo->execute([':id_article' => $article['id']]);
            $photo = $stmt_photo->fetch();
            ?>
            
            <?php if ($photo): ?>
              <div class="article-card-photo">
                <img src="/uploads/<?= htmlspecialchars($photo['photos']) ?>" 
                     alt="<?= htmlspecialchars($article['titre']) ?>" 
                     loading="lazy">
              </div>
            <?php endif; ?>

          </article>
        <?php endforeach; ?>
      <?php else: ?>
        <div class="no-results">
          <p>Aucun article ne correspond à votre recherche.</p>
          <a href="/">← Retour à l'accueil</a>
        </div>
      <?php endif; ?>

    <?php else: ?>
      <div class="no-results">
        <p>Entrez au moins 2 caractères pour rechercher.</p>
      </div>
    <?php endif; ?>

  </main>

  <!-- FOOTER -->
  <footer>
    <p>&copy; <?= date('Y') ?> <span>Le Monde</span> — Projet Web Design</p>
  </footer>

</body>
</html>
