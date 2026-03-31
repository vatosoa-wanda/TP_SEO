<?php
require_once 'includes/config.php';

// Les paramètres arrivent soit via GET (formulaire)
// soit déjà parsés par nginx (URL propre)
$q          = trim($_GET['q']          ?? '');
$champ      = $_GET['champ']           ?? 'tous';
$date_debut = $_GET['date_debut']      ?? '';
$date_fin   = $_GET['date_fin']        ?? '';

// Convertir les tirets du slug en espaces pour la recherche
// ex: "conflit-en-iran" → "conflit en iran"
if (!empty($q)) {
    $q = str_replace('-', ' ', $q);
}

$champs_autorises = ['tous', 'titre', 'slug', 'contenu'];
if (!in_array($champ, $champs_autorises)) {
    $champ = 'tous';
}

// -----------------------------------------------
// Construire l'URL propre pour le formulaire
// -----------------------------------------------
function construire_url(string $q, string $date_debut, string $date_fin): string {
    // Convertir espaces en tirets pour l'URL
    $slug_q = strtolower(trim(preg_replace('/\s+/', '-', $q)));

    if (!empty($slug_q) && !empty($date_debut) && !empty($date_fin)) {
        return "/recherche/$slug_q/$date_debut/$date_fin";
    }
    if (!empty($slug_q) && !empty($date_debut)) {
        return "/recherche/$slug_q/$date_debut";
    }
    if (!empty($slug_q)) {
        return "/recherche/$slug_q";
    }
    if (!empty($date_debut)) {
        return "/recherche/$date_debut";
    }
    return "/recherche";
}

// Construction requête SQL
$conditions = [];
$params     = [];

if (!empty($q)) {
    if ($champ === 'tous') {
        $conditions[] = "(titre LIKE :q OR slug LIKE :q OR contenu LIKE :q)";
    } else {
        $conditions[] = "$champ LIKE :q";
    }
    $params[':q'] = '%' . $q . '%';
}

if (!empty($date_debut)) {
    $conditions[] = "DATE(date_creation) >= :date_debut";
    $params[':date_debut'] = $date_debut;
}

if (!empty($date_fin)) {
    $conditions[] = "DATE(date_creation) <= :date_fin";
    $params[':date_fin'] = $date_fin;
}

$sql = "SELECT id, titre, slug, meta_description, date_creation FROM article";
if (!empty($conditions)) {
    $sql .= " WHERE " . implode(" AND ", $conditions);
}
$sql .= " ORDER BY date_creation DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$articles = $stmt->fetchAll();

$titre_page = !empty($q)
    ? "Résultats pour \"" . htmlspecialchars($q) . "\" | Le Monde"
    : "Recherche | Le Monde";
$url_canonique = 'http://localhost:8080' . construire_url($q, $date_debut, $date_fin);

if (!empty($q) && !empty($date_debut)) {
    $meta_desc = "Résultats de recherche pour \"" . htmlspecialchars($q) . "\" du " . date('d/m/Y', strtotime($date_debut));
} elseif (!empty($q)) {
    $meta_desc = "Résultats de recherche pour \"" . htmlspecialchars($q) . "\" sur Le Monde — Conflit en Iran.";
} elseif (!empty($date_debut)) {
    $meta_desc = "Articles publiés à partir du " . date('d/m/Y', strtotime($date_debut)) . " sur Le Monde.";
} else {
    $meta_desc = "Recherchez des articles sur le conflit en Iran — titres, contenus et dates disponibles.";
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="robots" content="index, follow">
  <meta name="description" content="<?= $meta_desc ?>">
  <title><?= $titre_page ?></title>
  <link rel="canonical" href="<?= htmlspecialchars($url_canonique) ?>">
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: Georgia, 'Times New Roman', serif; background: #f5f5f5; color: #1a1a1a; line-height: 1.6; }
    header { background: #1a1a1a; color: white; text-align: center; padding: 30px 20px; border-bottom: 3px solid #c00; }
    header a { color: white; text-decoration: none; }
    header .site-name { font-size: 2.8rem; font-weight: normal; letter-spacing: 2px; display: block; }
    header p { font-size: 0.9rem; color: #aaa; margin-top: 5px; }
    nav { background: #222; text-align: center; padding: 12px 0; position: sticky; top: 0; z-index: 100; }
    nav a { color: #ddd; text-decoration: none; margin: 0 20px; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1px; font-family: Arial, sans-serif; }
    nav a:hover { color: white; border-bottom: 2px solid #c00; padding-bottom: 2px; }
    main { max-width: 860px; margin: 40px auto; padding: 0 20px; }
    .recherche { background: white; padding: 20px 25px; margin-bottom: 25px; border-left: 4px solid #c00; }
    .recherche h2 { font-size: 1rem; font-family: Arial, sans-serif; text-transform: uppercase; letter-spacing: 1px; color: #333; margin-bottom: 15px; }
    .recherche-grid { display: flex; gap: 10px; flex-wrap: wrap; align-items: flex-end; }
    .champ { display: flex; flex-direction: column; gap: 5px; flex: 1; min-width: 160px; }
    .champ label { font-size: 0.8rem; font-family: Arial, sans-serif; color: #767676; text-transform: uppercase; }
    .champ input, .champ select { padding: 9px 12px; border: 1px solid #ddd; font-size: 0.9rem; background: #fafafa; }
    .champ input:focus, .champ select:focus { outline: none; border-color: #c00; background: white; }
    .btn-recherche { padding: 9px 20px; background: #c00; color: white; border: none; cursor: pointer; font-family: Arial, sans-serif; font-size: 0.85rem; text-transform: uppercase; white-space: nowrap; }
    .btn-recherche:hover { background: #a00; }
    .btn-reset { padding: 9px 15px; background: #333; color: white; text-decoration: none; font-family: Arial, sans-serif; font-size: 0.85rem; white-space: nowrap; }
    .btn-reset:hover { background: #555; }
    .url-propre { font-family: monospace; font-size: 0.85rem; background: #f0f0f0; padding: 8px 12px; margin-bottom: 20px; color: #c00; border-left: 3px solid #c00; }
    .resultats-header { margin-bottom: 20px; }
    .resultats-header h2 { font-size: 1.3rem; font-weight: normal; }
    .resultats-info { font-family: Arial, sans-serif; font-size: 0.85rem; color: #767676; }
    .resultats-info strong { color: #c00; }
    .article-card { background: white; padding: 25px 30px; margin-bottom: 15px; border-left: 4px solid transparent; transition: border-color 0.2s; }
    .article-card:hover { border-left-color: #c00; }
    .article-card h3 { font-size: 1.4rem; margin-bottom: 8px; font-weight: normal; }
    .article-card h3 a { color: #1a1a1a; text-decoration: none; }
    .article-card h3 a:hover { color: #c00; }
    .article-card .meta { font-size: 0.8rem; color: #767676; font-family: Arial, sans-serif; margin-bottom: 10px; text-transform: uppercase; }
    .article-card .description { color: #555; font-size: 0.95rem; }
    .lire-plus { display: inline-block; margin-top: 12px; font-size: 0.85rem; color: #c00; text-decoration: none; font-family: Arial, sans-serif; font-weight: bold; text-transform: uppercase; }
    .lire-plus:hover { text-decoration: underline; }
    mark { background: #fff3cd; color: #1a1a1a; padding: 0 2px; }
    .vide { text-align: center; color: #767676; padding: 60px 0; font-style: italic; }
    footer { background: #1a1a1a; color: #666; text-align: center; padding: 25px; margin-top: 60px; font-family: Arial, sans-serif; font-size: 0.85rem; }
    footer span { color: #c00; }
    @media (max-width: 600px) {
      header .site-name { font-size: 1.8rem; }
      .recherche-grid { flex-direction: column; }
    }
  </style>
</head>
<body>

  <header>
    <a href="/"><span class="site-name">Le Monde</span></a>
    <p>Actualités &amp; Analyses — Conflit en Iran</p>
  </header>

  <nav aria-label="Navigation principale">
    <a href="/">Accueil</a>
    <a href="#">International</a>
    <a href="#">Politique</a>
    <a href="#">Économie</a>
  </nav>

  <main>

    <!-- FORMULAIRE — redirige vers URL propre via JS -->
    <div class="recherche">
      <h2>Rechercher un article</h2>
      <form id="form-recherche">
        <div class="recherche-grid">

          <div class="champ">
            <label for="q">Mot-clé</label>
            <input type="text" id="q" name="q"
                   placeholder="conflit, iran..."
                   value="<?= htmlspecialchars($q) ?>">
          </div>

          <div class="champ">
            <label for="champ">Rechercher dans</label>
            <select id="champ" name="champ">
              <option value="tous"    <?= $champ === 'tous'    ? 'selected' : '' ?>>Tout</option>
              <option value="titre"   <?= $champ === 'titre'   ? 'selected' : '' ?>>Titre</option>
              <option value="slug"    <?= $champ === 'slug'    ? 'selected' : '' ?>>Slug</option>
              <option value="contenu" <?= $champ === 'contenu' ? 'selected' : '' ?>>Contenu</option>
            </select>
          </div>

          <div class="champ">
            <label for="date_debut">Date début</label>
            <input type="date" id="date_debut" name="date_debut"
                   value="<?= htmlspecialchars($date_debut) ?>">
          </div>

          <div class="champ">
            <label for="date_fin">Date fin</label>
            <input type="date" id="date_fin" name="date_fin"
                   value="<?= htmlspecialchars($date_fin) ?>">
          </div>

          <button type="submit" class="btn-recherche">Rechercher</button>
          <a href="/" class="btn-reset">Réinitialiser</a>

        </div>
      </form>
    </div>

    <!-- RÉSULTATS -->
    <div class="resultats-header">
      <h2>
        <?php if (!empty($q)): ?>
          Résultats pour : "<?= htmlspecialchars($q) ?>"
        <?php elseif (!empty($date_debut)): ?>
          Articles du <?= date('d/m/Y', strtotime($date_debut)) ?>
        <?php else: ?>
          Tous les articles
        <?php endif; ?>
      </h2>
      <p class="resultats-info">
        <strong><?= count($articles) ?></strong> article(s) trouvé(s)
        <?php if (!empty($date_debut)): ?>
          — du <strong><?= date('d/m/Y', strtotime($date_debut)) ?></strong>
        <?php endif; ?>
        <?php if (!empty($date_fin)): ?>
          au <strong><?= date('d/m/Y', strtotime($date_fin)) ?></strong>
        <?php endif; ?>
      </p>
    </div>

    <?php if (empty($articles)): ?>
      <p class="vide">Aucun article ne correspond à votre recherche.</p>
    <?php else: ?>
      <?php foreach ($articles as $article): ?>
        <article class="article-card">
          <h3>
            <a href="/article/<?= $article['id'] ?>/<?= htmlspecialchars($article['slug']) ?>">
              <?php
              if (!empty($q)) {
                  echo preg_replace(
                      '/(' . preg_quote(htmlspecialchars($q), '/') . ')/i',
                      '<mark>$1</mark>',
                      htmlspecialchars($article['titre'])
                  );
              } else {
                  echo htmlspecialchars($article['titre']);
              }
              ?>
            </a>
          </h3>
          <p class="meta">
            Publié le <?= date('d/m/Y \à H\hi', strtotime($article['date_creation'])) ?>
          </p>
          <?php if (!empty($article['meta_description'])): ?>
            <p class="description">
              <?php
              if (!empty($q)) {
                  echo preg_replace(
                      '/(' . preg_quote(htmlspecialchars($q), '/') . ')/i',
                      '<mark>$1</mark>',
                      htmlspecialchars($article['meta_description'])
                  );
              } else {
                  echo htmlspecialchars($article['meta_description']);
              }
              ?>
            </p>
          <?php endif; ?>
          <a class="lire-plus"
             href="/article/<?= $article['id'] ?>/<?= htmlspecialchars($article['slug']) ?>">
            Lire la suite →
          </a>
        </article>
      <?php endforeach; ?>
    <?php endif; ?>

  </main>

  <footer>
    <p>&copy; <?= date('Y') ?> <span>Le Monde</span> — Projet Web Design</p>
  </footer>

  <!-- JS : construire l'URL propre avant soumission -->
  <script>
    document.getElementById('form-recherche').addEventListener('submit', function(e) {
      e.preventDefault();

      const q          = document.getElementById('q').value.trim();
      const date_debut = document.getElementById('date_debut').value;
      const date_fin   = document.getElementById('date_fin').value;
      const champ      = document.getElementById('champ').value;

      // Convertir espaces en tirets
      const slug = q.toLowerCase().replace(/\s+/g, '-').replace(/[^a-z0-9-]/g, '');

      let url = '/recherche';

      if (slug && date_debut && date_fin) {
        url = `/recherche/${slug}/${date_debut}/${date_fin}`;
      } else if (slug && date_debut) {
        url = `/recherche/${slug}/${date_debut}`;
      } else if (slug) {
        url = `/recherche/${slug}`;
      } else if (date_debut) {
        url = `/recherche/${date_debut}`;
      }

      // Ajouter le champ si différent de "tous"
      if (champ !== 'tous') {
        url += `?champ=${champ}`;
      }

      window.location.href = url;
    });
  </script>

</body>
</html>