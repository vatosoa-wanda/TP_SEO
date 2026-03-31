<?php
require_once 'includes/config.php';

// Récupérer l'URL et extraire l'ID du fichier
// Format: /articles/titre-slugifie-ID.html
$request_uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Rétirer /articles/ et .html
$filename = basename($request_uri, '.html'); // titre-slugifie-ID

if (empty($filename)) {
    header('Location: /');
    exit;
}

// Extraire l'ID à la fin (après le dernier tiret)
$parts = explode('-', $filename);
$id = array_pop($parts); // Récupérer le dernier élément (l'ID)

// Vérifier que c'est un nombre
if (!is_numeric($id)) {
    http_response_code(404);
    header('Location: /');
    exit;
}

// Récupérer l'article
$stmt = $pdo->prepare("SELECT * FROM article WHERE id = :id LIMIT 1");
$stmt->execute([':id' => $id]);
$article = $stmt->fetch();

if (!$article) {
    http_response_code(404);
    header('Location: /');
    exit;
}

// Récupérer TOUTES les photos de l'article
$stmt_photos = $pdo->prepare("SELECT photos FROM photos WHERE id_article = :id_article ORDER BY date_ajout ASC");
$stmt_photos->execute([':id_article' => $article['id']]);
$photos = $stmt_photos->fetchAll();

// URL canonique absolue
$base_url    = 'http://localhost:8080';
$url_article = $base_url . '/articles/' . slugify($article['titre']) . '-' . $article['id'] . '.html';

$contenu = $article['contenu'];

// Ajouter loading="lazy" sur toutes les images du contenu
$contenu = preg_replace(
    '/<img(?![^>]*loading=)/',
    '<img loading="lazy"',
    $contenu
);

$premiere = false;
$contenu = preg_replace_callback('/<img[^>]+>/', function($match) use (&$premiere) {
    if (!$premiere) {
        $premiere = true;
        $img = str_replace('loading="lazy"', '', $match[0]);
        $img = str_replace('<img ', '<img fetchpriority="high" ', $img);
        return $img;
    }
    return $match[0];
}, $contenu);


?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="<?= htmlspecialchars($article['meta_description'] ?? '') ?>">

  <!-- Open Graph -->
  <meta property="og:title"       content="<?= htmlspecialchars($article['titre']) ?> | Le Monde">
  <meta property="og:description" content="<?= htmlspecialchars($article['meta_description'] ?? '') ?>">
  <meta property="og:type"        content="article">
  <meta property="og:url"         content="/article/<?= $article['id'] ?>/<?= htmlspecialchars($article['slug']) ?>">

  <meta name="robots"      content="index, follow">

  <title><?= htmlspecialchars($article['titre']) ?> | Le Monde</title>

  <!-- URL canonique -->
  <link rel="canonical" href="<?= $url_article ?>">

  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }

    body {
      font-family: Georgia, 'Times New Roman', serif;
      background: #f5f5f5;
      color: #1a1a1a;
      line-height: 1.8;
    }

    /* HEADER */
    header {
      background: #1a1a1a;
      color: white;
      text-align: center;
      padding: 30px 20px;
      border-bottom: 3px solid #c00;
    }
    header a {
      color: white;
      text-decoration: none;
    }
    header .site-name {
      font-size: 2.8rem;
      font-weight: normal;
      letter-spacing: 2px;
      display: block;
    }
    header p {
      font-size: 0.9rem;
      color: #aaa;
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
      color: #ddd;
      text-decoration: none;
      margin: 0 20px;
      font-size: 0.85rem;
      text-transform: uppercase;
      letter-spacing: 1px;
      font-family: Arial, sans-serif;
    }
    nav a:hover { color: white; border-bottom: 2px solid #c00; padding-bottom: 2px; }
    
    nav .search-bar {
      display: flex;
      gap: 8px;
      margin: 0 10px;
    }
    nav .search-bar input {
      padding: 6px 12px;
      border: 1px solid #444;
      background: #333;
      color: white;
      border-radius: 4px;
      font-size: 0.85rem;
      font-family: Arial, sans-serif;
      min-width: 150px;
    }
    nav .search-bar input::placeholder {
      color: #999;
    }
    nav .search-bar input:focus {
      outline: none;
      border-color: #c00;
      background: #3a3a3a;
    }
    nav .search-bar button {
      padding: 6px 15px;
      background: #c00;
      color: white;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      font-weight: bold;
      font-size: 0.85rem;
      font-family: Arial, sans-serif;
      text-transform: uppercase;
    }
    nav .search-bar button:hover {
      background: #a00;
    }
    
    nav {
      display: flex;
      justify-content: center;
      align-items: center;
      gap: 20px;
      flex-wrap: wrap;
    }

    /* BREADCRUMB */
    .breadcrumb {
      max-width: 860px;
      margin: 20px auto 0;
      padding: 0 20px;
      font-family: Arial, sans-serif;
      font-size: 0.85rem;
      color: #999;
    }
    .breadcrumb a { color: #c00; text-decoration: none; }
    .breadcrumb a:hover { text-decoration: underline; }

    /* MAIN */
    main {
      max-width: 860px;
      margin: 30px auto 60px;
      padding: 0 20px;
    }

    .article-header {
      background: white;
      padding: 35px 40px 25px;
      border-left: 4px solid #c00;
      margin-bottom: 5px;
    }

    /* ARTICLE */
    .article-header h1 {
      font-size: 2rem;
      font-weight: normal;
      line-height: 1.3;
      margin-bottom: 15px;
    }

    .article-header .meta {
      font-size: 0.82rem;
      color: #999;
      font-family: Arial, sans-serif;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    .article-header .categorie {
      font-size: 0.8rem;
      color: #c00;
      text-transform: uppercase;
      letter-spacing: 2px;
      font-family: Arial, sans-serif;
      margin-bottom: 12px;
    }

    .article-header h2 {
      font-size: 2rem;
      font-weight: normal;
      line-height: 1.3;
      margin-bottom: 15px;
    }

    .article-header .meta-description {
      margin-top: 15px;
      padding-top: 15px;
      border-top: 1px solid #eee;
      color: #555;
      font-style: italic;
      font-size: 1rem;
    }

    /* CONTENU TINYMCE */
    .article-contenu {
      background: white;
      padding: 35px 40px;
    }

    .article-contenu h2 {
      font-size: 1.5rem;
      margin: 30px 0 12px;
      color: #1a1a1a;
      border-bottom: 1px solid #eee;
      padding-bottom: 8px;
    }
    .article-contenu h3 {
      font-size: 1.2rem;
      margin: 25px 0 10px;
      color: #333;
    }
    .article-contenu h4, 
    .article-contenu h5, 
    .article-contenu h6 {
      margin: 20px 0 8px;
      color: #444;
    }
    .article-contenu p {
      margin-bottom: 16px;
      color: #333;
    }
    .article-contenu img {
      max-width: 100%;
      height: auto;
      display: block;
      margin: 20px auto;
      border: 1px solid #eee;
    }
    .article-contenu ul,
    .article-contenu ol {
      margin: 15px 0 15px 25px;
    }
    .article-contenu li {
      margin-bottom: 6px;
    }
    .article-contenu table {
      width: 100%;
      border-collapse: collapse;
      margin: 20px 0;
      font-family: Arial, sans-serif;
      font-size: 0.9rem;
    }
    .article-contenu table td,
    .article-contenu table th {
      border: 1px solid #ddd;
      padding: 10px 14px;
    }
    .article-contenu table th {
      background: #1a1a1a;
      color: white;
    }
    .article-contenu table tr:nth-child(even) {
      background: #f9f9f9;
    }
    .article-contenu a {
      color: #c00;
    }
    .article-contenu blockquote {
      border-left: 4px solid #c00;
      margin: 20px 0;
      padding: 10px 20px;
      background: #f9f9f9;
      font-style: italic;
      color: #555;
    }

    /* RETOUR */
    .retour {
      display: inline-block;
      margin-top: 30px;
      font-family: Arial, sans-serif;
      font-size: 0.85rem;
      color: #c00;
      text-decoration: none;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      font-weight: bold;
    }
    .retour:hover { text-decoration: underline; }

    /* FOOTER */
    footer {
      background: #1a1a1a;
      color: #666;
      text-align: center;
      padding: 25px;
      font-family: Arial, sans-serif;
      font-size: 0.85rem;
    }
    footer span { color: #c00; }

    /* RESPONSIVE */
    @media (max-width: 600px) {
      header .site-name { font-size: 1.8rem; }
      .article-header, .article-contenu { padding: 20px; }
      .article-header h1 { font-size: 1.4rem; }
      nav a { margin: 0 10px; font-size: 0.75rem; }
    }
  </style>
</head>
<body>

  <!-- HEADER -->
  <header>
    <a href="/">
      <span class="site-name">Le Monde</span>
    </a>
    <p>Actualités &amp; Analyses — Conflit en Iran</p>
  </header>

  <!-- NAV -->
  <nav>
    <a href="/">Accueil</a>
    <a href="#">International</a>
    <a href="#">Politique</a>
    <a href="#">Économie</a>
    <div class="search-bar">
      <form method="GET" action="/search.php" style="display: flex; gap: 8px;">
        <input type="text" name="q" placeholder="Rechercher..." required>
        <button type="submit">🔍</button>
      </form>
    </div>
  </nav>

  <!-- MAIN -->
  <main>

    <!-- BREADCRUMB -->
    <p class="breadcrumb">
      <a href="/">Accueil</a> &rsaquo; <?= htmlspecialchars($article['titre']) ?>
    </p>

    <!-- EN-TÊTE ARTICLE -->
    <div class="article-header">
      <p class="categorie">Conflit en Iran</p>

      <h2><?= htmlspecialchars($article['titre']) ?></h2>

      <p class="meta">
        Publié le <?= date('d/m/Y \à H\hi', strtotime($article['date_creation'])) ?>
        <?php if ($article['date_modification'] !== $article['date_creation']): ?>
          &mdash; Modifié le <?= date('d/m/Y', strtotime($article['date_modification'])) ?>
        <?php endif; ?>
      </p>

      <?php if (!empty($article['meta_description'])): ?>
        <p class="meta-description"><?= htmlspecialchars($article['meta_description']) ?></p>
      <?php endif; ?>
    </div>

    <!-- PHOTOS DE L'ARTICLE -->
    <?php if (!empty($photos)): ?>
      <div class="article-contenu" style="padding: 35px 40px 20px; background: white; margin-bottom: 0;">
        <h3 style="font-size: 1.3rem; margin-top: 0; margin-bottom: 20px;">Photos de l'article</h3>
        <div class="photos-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 20px;">
          <?php foreach ($photos as $photo): ?>
            <img src="/uploads/<?= htmlspecialchars($photo['photos']) ?>" 
                 alt="Photo article" 
                 style="width: 100%; height: 250px; object-fit: cover; border-radius: 4px; box-shadow: 0 2px 8px rgba(0,0,0,0.15); cursor: pointer; transition: transform 0.2s;"
                 loading="lazy">
          <?php endforeach; ?>
        </div>
      </div>
    <?php endif; ?>

    <!-- CONTENU TINYMCE -->
    <div class="article-contenu">
      <?= $article['contenu'] ?>
    </div>

    <a class="retour" href="/">← Retour aux articles</a>

  </main>

  <!-- FOOTER -->
  <footer>
    <p>&copy; <?= date('Y') ?> <span>Le Monde</span> — Projet Web Design</p>
  </footer>

</body>
</html>