<?php
require_once 'includes/config.php';

$stmt    = $pdo->query("SELECT id, titre, slug, meta_description, date_creation FROM article ORDER BY date_creation DESC");
$articles = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Suivez l'actualité du conflit en Iran : analyses, contexte historique et impact humanitaire.">

  <!-- Open Graph -->
  <meta property="og:title"       content="Conflit en Iran - Actualités | Le Monde">
  <meta property="og:description" content="Suivez l'actualité du conflit en Iran : analyses, contexte historique et impact humanitaire.">
  <meta property="og:type"        content="website">

  <title>Conflit en Iran - Actualités et analyses | Le Monde</title>

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
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    header .title {
      text-align: center;
      flex: 1;
    }
    header h1 {
      font-size: 2.8rem;
      font-weight: normal;
      letter-spacing: 2px;
    }
    header p {
      font-size: 0.9rem;
      color: #aaa;
      margin-top: 5px;
    }
    header .user-info {
      font-size: 0.9rem;
      color: #aaa;
      white-space: nowrap;
      margin-left: 20px;
    }
    header .user-info strong {
      color: white;
    }
    header .btn-logout {
      background: #c00;
      color: white;
      text-decoration: none;
      padding: 8px 15px;
      border-radius: 4px;
      font-size: 0.85rem;
      font-family: Arial, sans-serif;
      margin-left: 15px;
      transition: background 0.3s;
      display: inline-block;
    }
    header .btn-logout:hover {
      background: #a00;
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
      color: #ddd;
      text-decoration: none;
      margin: 0 20px;
      font-size: 0.85rem;
      text-transform: uppercase;
      letter-spacing: 1px;
      font-family: Arial, sans-serif;
    }
    nav a:hover { color: white; border-bottom: 2px solid #c00; padding-bottom: 2px; }

    /* MAIN */
    main {
      max-width: 860px;
      margin: 40px auto;
      padding: 0 20px;
    }

    /* TITRE SECTION */
    .section-titre {
      font-size: 1.1rem;
      text-transform: uppercase;
      letter-spacing: 2px;
      color: #c00;
      border-bottom: 2px solid #c00;
      padding-bottom: 8px;
      margin-bottom: 30px;
      font-family: Arial, sans-serif;
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
      text-decoration: none;
    }
    .article-card h2 a:hover {
      color: #c00;
    }

    .article-card .meta {
      font-size: 0.8rem;
      color: #999;
      font-family: Arial, sans-serif;
      margin-bottom: 10px;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    .article-card .description {
      color: #555;
      font-size: 0.95rem;
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
    
    /* RESPONSIVE */
    @media (max-width: 768px) {
      .article-card {
        flex-direction: column;
      }
      .article-card-photo {
        flex: 0 0 auto;
      }
    }

    /* AUCUN ARTICLE */
    .vide {
      text-align: center;
      color: #999;
      padding: 60px 0;
      font-style: italic;
    }

    /* FOOTER */
    footer {
      background: #1a1a1a;
      color: #666;
      text-align: center;
      padding: 25px;
      margin-top: 60px;
      font-family: Arial, sans-serif;
      font-size: 0.85rem;
    }
    footer span { color: #c00; }
  </style>
</head>
<body>

  <!-- HEADER -->
  <header>
    <div class="title">
      <h1>Le Monde</h1>
      <p>Actualités &amp; Analyses — Conflit en Iran</p>
    </div>
    <!-- <?php if (isLoggedIn()): ?>
    <div class="user-info">
      Connecté : <strong><?= htmlspecialchars($_SESSION['admin_username']) ?></strong>
      <a href="admin/logout.php" class="btn-logout">Déconnexion</a>
    </div>
    <?php endif; ?> -->
  </header>

  <!-- NAV -->
  <nav>
    <a href="/">Accueil</a>
    <a href="#">International</a>
    <a href="#">Politique</a>
    <a href="#">Économie</a>
    <?php if (isLoggedIn()): ?>
    <!-- <a href="/admin/list.php" style="color: #c00; font-weight: bold;">⚙️ Administration</a> -->
    <?php endif; ?>
  </nav>

  <!-- MAIN -->
  <main>
    <p class="section-titre">Dossier : Conflit en Iran</p>

    <?php if (empty($articles)): ?>
      <p class="vide">Aucun article publié pour le moment.</p>

    <?php else: ?>
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
    <?php endif; ?>

  </main>

  <!-- FOOTER -->
  <footer>
    <p>&copy; <?= date('Y') ?> <span>Le Monde</span> — Projet Web Design</p>
  </footer>

</body>
</html>