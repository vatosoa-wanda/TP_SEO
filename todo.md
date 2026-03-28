Parfait 👍 — voici une **TO DO LIST complète, structurée et exploitable directement par un développeur** pour ton projet :

👉 **Site d’information (guerre Iran) avec PHP + MySQL + TinyMCE + Docker + SEO**

---

# 🧠 📌 0. Vue globale du projet

Objectif :

* FrontOffice → afficher articles
* BackOffice → CRUD avec éditeur TinyMCE
* DB → stocker HTML + images
* SEO → optimisation complète
* Docker → environnement portable

---

# ⚙️ 🧰 1. Installation & environnement

## 🔹 1.1 Outils à installer

* PHP 8.x (OK)
* MySQL / MariaDB (OK)
* Docker + Docker Compose (OK)
* Navigateur (Chrome recommandé)
* VS Code (OK)

---

## 🔹 1.2 Extensions VS Code recommandées

* PHP Intelephense
* Docker
* Prettier
* Thunder Client (test API)

---

## 🔹 1.3 Vérifications

✔️ `php -v` (OK)
✔️ `docker -v` (OK)
✔️ `docker-compose -v` (OK)
✔️ accès navigateur localhost (OK)

---

# 🐳 🧱 2. Initialisation projet Docker

## 🔹 2.1 Créer structure (OK)

```plaintext
/projet
 ├── app/
 ├── nginx/
 ├── php/
 ├── docker-compose.yml
```

---

## 🔹 2.2 Config Docker

* Créer `docker-compose.yml`(OK)
* Créer Dockerfile PHP (OK)
* Configurer Nginx (OK)
 
---

## 🔹 2.3 Lancer environnement

```bash
docker-compose up --build (OK)
```

---

## 🧪 TEST

✔️ Accès : `http://localhost:8080` (OK)
✔️ Page PHP s’affiche (OK)

---

# 🗄️ 🧾 3. Base de données

## 🔹 3.1 Création DB

* Base : `guerre_iran` (OK)
* Table : `article` (OK)

---

## 🔹 3.2 Structure table

```sql
id, titre, contenu, date_creation (OK)
```

---

## 🔹 3.3 Connexion PHP

Créer `config.php`

---

## 🧪 TEST

✔️ Connexion réussie (OK)
✔️ SELECT simple fonctionne (OK)

---

# ✍️ 🧑‍💻 4. BackOffice (CRUD)

---

## 🔹 4.1 Page ajout article

* `admin/add.php`
* Formulaire :

  * titre
  * contenu (TinyMCE)

---

## 🔹 4.2 Intégration TinyMCE

* Ajouter script CDN ou local
* Initialiser `tinymce.init()`

---

## 🧪 TEST

✔️ textarea transformé en éditeur
✔️ boutons visibles

---

## 🔹 4.3 Sauvegarde article

* `admin/save.php`
* INSERT en base

---

## 🧪 TEST

✔️ données enregistrées
✔️ HTML présent en base

---

## 🔹 4.4 Liste des articles

* `admin/list.php`
* afficher tableau

---

## 🔹 4.5 Modifier article

* `edit.php`
* pré-remplir TinyMCE

---

## 🔹 4.6 Supprimer article

* bouton delete

---

## 🧪 TEST CRUD COMPLET

✔️ Create
✔️ Read
✔️ Update
✔️ Delete

---

# 🖼️ 📤 5. Upload images TinyMCE

---

## 🔹 5.1 Config TinyMCE

```js
images_upload_url: 'upload.php'
```

---

## 🔹 5.2 Créer `upload.php`

* upload fichier
* retourner JSON

---

## 🔹 5.3 Dossier `/uploads`

* permissions OK

---

## 🧪 TEST

✔️ upload image fonctionne
✔️ image insérée dans contenu
✔️ plusieurs images OK

---

# 🌐 🖥️ 6. FrontOffice

---

## 🔹 6.1 Page index

* afficher liste articles

---

## 🔹 6.2 Page détail article

* afficher contenu HTML

---

## 🧪 TEST

✔️ HTML bien rendu
✔️ images visibles
✔️ tableau affiché correctement

---

# 🔗 🔍 7. URL rewriting (SEO)

---

## 🔹 7.1 Slug

* générer slug depuis titre

---

## 🔹 7.2 Routing

* `/article/id/slug`

---

## 🔹 7.3 Config serveur

* Nginx rewrite

---

## 🧪 TEST

✔️ URL propre fonctionne
✔️ accès article OK

---

# 📈 🧠 8. SEO On-page

---

## 🔹 8.1 Balises HTML

* `<h1>` = titre
* `<h2>` dans contenu

---

## 🔹 8.2 `<title>`

* dynamique

---

## 🔹 8.3 META

* description
* viewport

---

## 🔹 8.4 Images

* alt obligatoire

---

## 🧪 TEST

✔️ code HTML propre
✔️ pas de balises manquantes

---

# 🚀 ⚡ 9. Performance

---

## 🔹 9.1 Optimisations

* images compressées
* lazy loading
* minifier CSS/JS

---

## 🔹 9.2 Cache (optionnel)

---

## 🧪 TEST

✔️ chargement rapide

---

# 🔍 📊 10. Audit avec Google Lighthouse

---

## 🔹 10.1 Tests

* Mobile
* Desktop

---

## 🔹 10.2 Objectifs

* SEO > 90
* Perf > 80

---

## 🧪 TEST

✔️ score OK
✔️ corriger erreurs

---

# 🔐 🔒 11. Sécurité

---

## 🔹 11.1 SQL Injection

* requêtes préparées

---

## 🔹 11.2 Upload

* type fichier
* taille max

---

## 🔹 11.3 XSS

* filtrer contenu si nécessaire

---

## 🧪 TEST

✔️ injection bloquée
✔️ fichiers invalides refusés

---

# 📁 🧩 12. Organisation code

---

## 🔹 12.1 Structurer

```plaintext
/app
 ├── admin
 ├── uploads
 ├── includes
```

---

## 🔹 12.2 Réutilisation

* header.php
* footer.php

---

# 🧪 🧪 13. Tests finaux

---

## 🔹 Fonctionnels

✔️ navigation complète
✔️ CRUD OK
✔️ images OK

---

## 🔹 UX

✔️ responsive
✔️ lisible

---

## 🔹 Technique

✔️ pas d’erreurs console
✔️ pas d’erreurs PHP

---

# 🚀 🎯 Résultat final attendu

✔️ CMS complet
✔️ SEO optimisé
✔️ multi-images TinyMCE
✔️ Docker prêt prod
✔️ code maintenable

---

# 💡 BONUS (optionnel mais très pro)

* Auth admin (login)
* Catégories d’articles
* Pagination
* Recherche
* Sitemap.xml

---

# 🧠 CONSEIL IMPORTANT

👉 Toujours :

1. coder une petite partie
2. tester immédiatement
3. corriger
4. continuer

👉 évite 90% des bugs finaux
