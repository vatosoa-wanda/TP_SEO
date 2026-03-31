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
  <meta property="og:title"       content="Conflit en Iran - Actualités | Le Monde">
  <meta property="og:description" content="Suivez l'actualité du conflit en Iran : analyses, contexte historique et impact humanitaire.">
  <meta property="og:type"        content="website">
  <title>Conflit en Iran - Actualités et analyses | Le Monde</title>
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: Georgia, 'Times New Roman', serif; background: #f5f5f5; color: #1a1a1a; line-height: 1.6; }

    header {
      background: #1a1a1a; color: white; padding: 30px 20px;
      border-bottom: 3px solid #c00; display: flex;
      justify-content: space-between; align-items: center;
    }
    header .title { text-align: center; flex: 1; }
    header h1 { font-size: 2.8rem; font-weight: normal; letter-spacing: 2px; }
    header p { font-size: 0.9rem; color: #aaa; margin-top: 5px; }

    nav {
      background: #222; text-align: center; padding: 12px 0;
      position: sticky; top: 0; z-index: 100;
    }
    nav a {
      color: #ddd; text-decoration: none; margin: 0 20px;
      font-size: 0.85rem; text-transform: uppercase;
      letter-spacing: 1px; font-family: Arial, sans-serif;
    }
    nav a:hover { color: white; border-bottom: 2px solid #c00; padding-bottom: 2px; }

    main { max-width: 860px; margin: 40px auto; padding: 0 20px; }

    .section-titre {
      font-size: 1.1rem; text-transform: uppercase; letter-spacing: 2px;
      color: #c00; border-bottom: 2px solid #c00;
      padding-bottom: 8px; margin-bottom: 30px; font-family: Arial, sans-serif;
    }

    /* FORMULAIRE RECHERCHE */
    .recherche {
      background: white;
      padding: 20px 25px;
      margin-bottom: 30px;
      border-left: 4px solid #c00;
    }
    .recherche h2 {
      font-size: 1rem;
      font-family: Arial, sans-serif;
      text-transform: uppercase;
      letter-spacing: 1px;
      color: #333;
      margin-bottom: 15px;
    }
    .recherche-grid {
      display: flex;
      gap: 10px;
      flex-wrap: wrap;
      align-items: flex-end;
    }
    .recherche-grid .champ {
      display: flex;
      flex-direction: column;
      gap: 5px;
      flex: 1;
      min-width: 160px;
    }
    .recherche-grid label {
      font-size: 0.8rem;
      font-family: Arial, sans-serif;
      color: #767676;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }
    .recherche-grid input,
    .recherche-grid select {
      padding: 9px 12px;
      border: 1px solid #ddd;
      font-family: Georgia, serif;
      font-size: 0.9rem;
      color: #1a1a1a;
      background: #fafafa;
    }
    .recherche-grid input:focus,
    .recherche-grid select:focus {
      outline: none;
      border-color: #c00;
      background: white;
    }
    .btn-recherche {
      padding: 9px 20px;
      background: #c00;
      color: white;
      border: none;
      cursor: pointer;
      font-family: Arial, sans-serif;
      font-size: 0.85rem;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      white-space: nowrap;
    }
    .btn-recherche:hover { background: #a00; }
    .btn-reset {
      padding: 9px 15px;
      background: #333;
      color: white;
      text-decoration: none;
      font-family: Arial, sans-serif;
      font-size: 0.85rem;
      white-space: nowrap;
    }
    .btn-reset:hover { background: #555; }

    /* RÉSULTATS */
    .resultats-info {
      font-family: Arial, sans-serif;
      font-size: 0.85rem;
      color: #767676;
      margin-bottom: 15px;
    }
    .resultats-info strong { color: #c00; }

    .article-card {
      background: white; padding: 25px 30px; margin-bottom: 15px;
      border-left: 4px solid transparent; transition: border-color 0.2s;
    }
    .article-card:hover { border-left-color: #c00; }
    .article-card h2 { font-size: 1.4rem; margin-bottom: 8px; font-weight: normal; }
    .article-card h2 a { color: #1a1a1a; text-decoration: none; }
    .article-card h2 a:hover { color: #c00; }
    .article-card .meta {
      font-size: 0.8rem; color: #767676; font-family: Arial, sans-serif;
      margin-bottom: 10px; text-transform: uppercase; letter-spacing: 0.5px;
    }
    .article-card .description { color: #555; font-size: 0.95rem; }
    .lire-plus {
      display: inline-block; margin-top: 12px; font-size: 0.85rem;
      color: #c00; text-decoration: none; font-family: Arial, sans-serif;
      font-weight: bold; text-transform: uppercase; letter-spacing: 0.5px;
    }
    .lire-plus:hover { text-decoration: underline; }

    .vide { text-align: center; color: #767676; padding: 60px 0; font-style: italic; }

    footer {
      background: #1a1a1a; color: #666; text-align: center;
      padding: 25px; margin-top: 60px; font-family: Arial, sans-serif; font-size: 0.85rem;
    }
    footer span { color: #c00; }

    @media (max-width: 600px) {
      header h1 { font-size: 1.8rem; }
      .recherche-grid { flex-direction: column; }
    }
  </style>
</head>
<body>

  <header>
    <div class="title">
      <h1>Le Monde</h1>
      <p>Actualités &amp; Analyses — Conflit en Iran</p>
    </div>
  </header>

  <nav aria-label="Navigation principale">
    <a href="/">Accueil</a>
    <a href="#">International</a>
    <a href="#">Politique</a>
    <a href="#">Économie</a>
  </nav>

  <main>
    <p class="section-titre">Dossier : Conflit en Iran</p>

    <!-- FORMULAIRE DE RECHERCHE -->
    <div class="recherche">
      <h2>Rechercher un article</h2>
      <form method="GET" action="/recherche.php">
        <div class="recherche-grid">
  
          <div class="champ">
            <label for="q">Mot-clé</label>
            <input type="text" id="q" name="q"
                   placeholder="titre, contenu..."
                   value="<?= htmlspecialchars($_GET['q'] ?? '') ?>">
          </div>

          <div class="champ">
            <label for="champ">Rechercher dans</label>
            <select id="champ" name="champ">
              <option value="tous"   <?= ($_GET['champ'] ?? '') === 'tous'    ? 'selected' : '' ?>>Tout</option>
              <option value="titre"  <?= ($_GET['champ'] ?? '') === 'titre'   ? 'selected' : '' ?>>Titre</option>
              <option value="slug"   <?= ($_GET['champ'] ?? '') === 'slug'    ? 'selected' : '' ?>>Slug</option>
              <option value="contenu"<?= ($_GET['champ'] ?? '') === 'contenu' ? 'selected' : '' ?>>Contenu</option>
            </select>
          </div>

          <div class="champ">
            <label for="date_debut">Date début</label>
            <input type="date" id="date_debut" name="date_debut"
                   value="<?= htmlspecialchars($_GET['date_debut'] ?? '') ?>">
          </div>

          <div class="champ">
            <label for="date_fin">Date fin</label>
            <input type="date" id="date_fin" name="date_fin"
                   value="<?= htmlspecialchars($_GET['date_fin'] ?? '') ?>">
          </div>

          <button type="submit" class="btn-recherche">Rechercher</button>
          <a href="/" class="btn-reset">Réinitialiser</a>

        </div>
      </form>
    </div>

    <!-- LISTE ARTICLES -->
    <?php if (empty($articles)): ?>
      <p class="vide">Aucun article publié pour le moment.</p>
    <?php else: ?>
      <p class="resultats-info"><strong><?= count($articles) ?></strong> article(s) disponible(s)</p>
      <?php foreach ($articles as $article): ?>
        <article class="article-card">
          <h2>
            <a href="/article/<?= $article['id'] ?>/<?= htmlspecialchars($article['slug']) ?>">
              <?= htmlspecialchars($article['titre']) ?>
            </a>
          </h2>
          <p class="meta">
            Publié le <?= date('d/m/Y \à H\hi', strtotime($article['date_creation'])) ?>
          </p>
          <?php if (!empty($article['meta_description'])): ?>
            <p class="description"><?= htmlspecialchars($article['meta_description']) ?></p>
          <?php endif; ?>
          <a class="lire-plus" href="/article/<?= $article['id'] ?>/<?= htmlspecialchars($article['slug']) ?>">
            Lire la suite →
          </a>
        </article>
      <?php endforeach; ?>
    <?php endif; ?>

  </main>

  <footer>
    <p>&copy; <?= date('Y') ?> <span>Le Monde</span> — Projet Web Design</p>
  </footer>

</body>
</html>