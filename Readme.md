# 🌍 Guerre en Iran — Site d'information

Site d'information sur le conflit en Iran avec FrontOffice public et BackOffice de gestion de contenu, développé en PHP + MySQL + TinyMCE, containerisé avec Docker.

---

## 🧰 Technologies utilisées

| Technologie | Rôle |
|---|---|
| PHP 8.x | Langage backend |
| MySQL 8 | Base de données |
| Nginx | Serveur web + URL rewriting |
| Docker / Docker Compose | Containerisation |
| TinyMCE (local) | Éditeur de contenu WYSIWYG |
| HTML / CSS | FrontOffice |

---

## 📁 Structure du projet

```
/projet
├── app/
│   ├── admin/              # BackOffice
│   │   ├── add.php         # Formulaire ajout article
│   │   ├── save.php        # Traitement ajout
│   │   ├── list.php        # Liste des articles
│   │   ├── edit.php        # Formulaire modification
│   │   ├── update.php      # Traitement modification
│   │   ├── delete.php      # Suppression article
│   │   ├── upload.php      # Upload images (TinyMCE + formulaire)
│   │   └── upload_form.php # Formulaire upload multiple
│   ├── includes/
│   │   ├── config.php      # Connexion PDO à la base
│   │   └── tinymce/        # TinyMCE en local (sans API key)
│   ├── uploads/            # Images uploadées (gitignored)
│   │   └── .gitkeep
│   ├── images/             # Images statiques du site
│   ├── index.php           # FrontOffice — liste articles
│   └── article.php         # FrontOffice — détail article
├── bdd/
│   └── init.sql            # Script d'initialisation de la base
├── nginx/
│   └── default.conf        # Config Nginx + URL rewriting
├── php/
│   └── Dockerfile          # Image PHP custom
├── .gitignore
└── docker-compose.yml
```

---

## ✅ Prérequis

- [Docker](https://www.docker.com/) installé
- [Docker Compose](https://docs.docker.com/compose/) installé
- Git

Vérifications :
```bash
docker -v
docker-compose -v
```

---

## 🚀 Lancer le projet

### 1. Cloner le dépôt

```bash
git clone <URL_DU_DEPOT>
cd <nom_du_projet>
```

### 2. Lancer les conteneurs

```bash
docker-compose up --build
```

> La base de données est automatiquement initialisée via `bdd/init.sql` au premier démarrage.

### 3. Accéder au site

| Interface | URL |
|---|---|
| FrontOffice | http://localhost:8080 |
| BackOffice — liste articles | http://localhost:8080/admin/list.php |
| BackOffice — ajouter article | http://localhost:8080/admin/add.php |
| Upload images | http://localhost:8080/admin/upload_form.php |

---

## 🗄️ Accès à la base de données

### Via terminal Docker

```bash
docker exec -it <nom_container_db> mysql -u root -proot guerre_iran
```

### Informations de connexion

| Paramètre | Valeur |
|---|---|
| Host | `db` (dans Docker) / `localhost` (externe) |
| Port | `3306` |
| Base | `guerre_iran` |
| Utilisateur | `root` |
| Mot de passe | `root` |

### Commandes SQL utiles

```sql
-- Voir tous les articles
SELECT id, titre, slug, date_creation FROM article;

-- Corriger les chemins d'images si nécessaire
UPDATE article SET contenu = REPLACE(contenu, 'src="uploads/', 'src="/uploads/');
```

---

## 🔗 URLs & fonctionnalités

### FrontOffice

| URL | Description |
|---|---|
| `http://localhost:8080/` | Page d'accueil — liste des articles |
| `http://localhost:8080/article/{id}/{slug}` | Page détail d'un article |

### BackOffice

| URL | Description |
|---|---|
| `http://localhost:8080/admin/list.php` | Liste tous les articles |
| `http://localhost:8080/admin/add.php` | Créer un nouvel article |
| `http://localhost:8080/admin/edit.php?id={id}` | Modifier un article |
| `http://localhost:8080/admin/upload_form.php` | Uploader des images |

---

## ✍️ Utilisation du BackOffice

### Ajouter un article

1. Aller sur `http://localhost:8080/admin/add.php`
2. Remplir les champs :
   - **Titre** — titre de l'article
   - **Slug** — URL normalisée (ex: `guerre-iran-2024`)
   - **Meta description** — 160 caractères max, pour le SEO
   - **Contenu** — éditeur TinyMCE (texte, images, tableaux...)
3. Pour insérer une image dans TinyMCE :
   - Cliquer sur le bouton **Image** dans la toolbar
   - Onglet **Upload** → choisir un fichier depuis le PC
   - ⚠️ Ne pas coller un chemin manuellement dans le champ URL
4. Cliquer sur **Enregistrer**

### Modifier un article

1. Aller sur `http://localhost:8080/admin/list.php`
2. Cliquer sur **Modifier** sur la ligne souhaitée
3. Modifier le contenu dans TinyMCE
4. Cliquer sur **Enregistrer les modifications**

### Supprimer un article

1. Aller sur `http://localhost:8080/admin/list.php`
2. Cliquer sur **Supprimer** — une confirmation est demandée

---

## 🖼️ Upload d'images

Les images uploadées via TinyMCE ou le formulaire sont stockées dans `app/uploads/`.

> Le dossier `uploads/` est dans `.gitignore` — seul `.gitkeep` est versionné.

**Règle importante :** toujours utiliser le bouton **Upload** dans TinyMCE (pas le champ URL) pour que les chemins soient corrects (`/uploads/img_...png`).

---

## 🔍 SEO — Points vérifiés

| Point | Implémentation |
|---|---|
| URL normalisées | `/article/{id}/{slug}` via Nginx rewrite |
| Balise `<title>` | Dynamique par article |
| Balise `<meta description>` | Dynamique par article |
| Structure `h1/h2/h3...` | `h1` = titre article, `h2/h3` dans contenu TinyMCE |
| Attribut `alt` images | Rempli via TinyMCE à l'insertion |
| Open Graph | `og:title`, `og:description`, `og:type`, `og:url` |
| Responsive | `meta viewport` + `@media` CSS |

---

## 🔒 Sécurité

- Requêtes SQL préparées (PDO) — protection injection SQL
- Vérification type MIME réel des fichiers uploadés
- Taille max upload : 5 MB
- Accès au dossier `includes/` bloqué par Nginx
- Accès aux fichiers cachés (`.env`, `.git`) bloqué par Nginx

---

## 🛑 Commandes Docker utiles

```bash
# Lancer le projet
docker-compose up -d

# Arrêter le projet
docker-compose down

# Relancer nginx après modif default.conf
docker-compose restart web

# Rebuild après modif du Dockerfile PHP
docker-compose build php && docker-compose up -d

# Voir les logs
docker-compose logs -f

# Accéder au conteneur PHP
docker exec -it <container_php> bash
```

---

## 👤 Informations livraison

| Élément | Valeur |
|---|---|
| Login BO par défaut | *(à implémenter — voir BONUS)* |
| Dépôt Git | *(URL à renseigner)* |

---

## 💡 BONUS à implémenter

- [ ] Authentification admin (login/logout)
- [ ] Catégories d'articles
- [ ] Pagination sur la liste FrontOffice
- [ ] Recherche d'articles
- [ ] Sitemap.xml
- [ ] Audit Lighthouse (SEO > 90, Perf > 80)