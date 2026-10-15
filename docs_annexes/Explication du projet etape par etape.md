# Explication du projet tintinSymfony, étape par étape

Reconstitué à partir de l'historique git (20 commits) et du fichier `Historique des actions projet Symfony`.

## Plan

1. Génération du projet Symfony
2. Base de données, entité `Pays` et installation d'EasyAdmin
3. Les autres entités et leurs CRUD
4. Gestion des images avec VichUploader et mise en forme de l'admin
5. Les migrations : retirées puis remises
6. Sécurité : utilisateurs, login, mot de passe oublié, mails
7. Front : page d'accueil, polices Tintin, galerie
8. Popup carrousel de la galerie (WIP) et dernières modifications

---

## Étape 1 : génération du projet (23/03/2026)

**Commande lancée :** `symfony new tintinSymfony --webapp`

Elle a créé automatiquement les deux premiers commits.

### Commit `2ca02a5` « Add initial set of files » : le squelette minimal

- **`public/index.php`** : le seul point d'entrée web. Toutes les requêtes passent par ce fichier.
- **`src/Kernel.php`** : le noyau de l'application. Il charge les bundles et la configuration.
- **`bin/console`** : l'outil en ligne de commande (`make:entity`, `doctrine:migrations:migrate`…).
- **`config/`** :
  - `bundles.php` : la liste des bundles actifs ;
  - `services.yaml` : l'injection de dépendances, qui rend tout ce qui est dans `src/` injectable automatiquement ;
  - `routes.yaml` : lit les routes déclarées par les attributs `#[Route]` dans les contrôleurs.
- **`.env` / `.env.dev`** : les variables d'environnement (`APP_ENV`, `APP_SECRET`…).
- **`composer.json` / `composer.lock`** : les dépendances PHP.

### Commit `0df3b48` « Add webapp packages » : le pack complet d'application web

- **Doctrine ORM et Migrations** : pour faire le lien entre la base de données et les classes PHP. Il crée les dossiers vides `src/Entity/`, `src/Repository/` et `migrations/`.
- **Twig** : le moteur de templates, avec `templates/base.html.twig` comme gabarit parent de toutes les pages.
- **Security** : le fichier `config/packages/security.yaml`, encore presque vide à ce stade.
- **AssetMapper, Stimulus et Turbo** :
  - le JS et le CSS sont servis sans Webpack ni Node ;
  - `importmap.php` déclare les bibliothèques JS ;
  - `assets/app.js` et `assets/styles/app.css` sont les points d'entrée.
- **Mailer, Messenger et Notifier** : pour l'envoi de mails et le traitement asynchrone. Par défaut, Messenger met les mails en file d'attente (voir étape 6).
- **Validator, Form, Translation, Monolog** (les logs) et le **Web Profiler** (la barre de debug en bas des pages en dev).
- **PHPUnit** : `tests/` et `phpunit.dist.xml`.
- **`compose.yaml`** : un conteneur Docker de base de données, PostgreSQL par défaut. Il n'est pas utilisé, puisque la base est en MySQL local (étape 2).

À ce stade, aucune ligne de code métier n'existe encore : c'est uniquement la structure fournie par Symfony.

---

## Étape 2 : base de données, entité `Pays` et EasyAdmin (23/03/2026)

### Commit `653cb8c` « Entité/bdd Pays »

**1. Connexion à MySQL** dans `.env` :

```
DATABASE_URL="mysql://web:web@127.0.0.1:3306/tintinSymfony?serverVersion=8.0.45&charset=utf8mb4"
```

La ligne PostgreSQL générée par défaut est commentée. La base a ensuite été créée avec `php bin/console doctrine:database:create`.

**2. Reprise des données d'une ancienne base `tintin`** (hors git) : la table `pays` est copiée directement en SQL.

```sql
CREATE TABLE tintinSymfony.pays LIKE tintin.pays;
INSERT INTO tintinSymfony.pays SELECT * FROM tintin.pays;
```

C'est le principe de tout le projet : **on ne part pas de zéro**. On reprend une base existante, puis on écrit des entités Doctrine qui correspondent à ses tables, en normalisant les noms au passage. Les règles suivies sont décrites dans l'historique : `id` en INT auto-incrémenté, noms courts et en français, clés étrangères en `xxx_id`, champs `created_at`/`updated_at`…

**3. L'entité `src/Entity/Pays.php`** (générée par `make:entity`) :

- des attributs PHP `#[ORM\Column]` sur chaque propriété : `id`, `code`, `alpha2`, `alpha3`, `nomFr`, `nomEn` ;
- Doctrine convertit automatiquement le camelCase en snake_case : `nomFr` devient la colonne `nom_fr` ;
- des getters/setters pour chaque champ.

**4. Le repository `src/Repository/PaysRepository.php`** : la classe qui sert à interroger la table (`find()`, `findAll()`, `findBy()`…). Pour l'instant, elle ne contient que les exemples commentés du maker.

**5. La première migration `Version20260323212805`** ne crée **que** la table `messenger_messages`, celle de la file d'attente de Messenger. Elle ne crée pas la table `pays`, parce que celle-ci existait déjà (copiée en SQL) et correspondait au mapping. Doctrine n'avait donc rien à changer.

**6. `.gitignore`** : ajout de `/.idea/` (PhpStorm).

> Remarque : les lignes marqueurs `###< ... ###` de fin de bloc ont été supprimées. Symfony Flex s'en sert pour mettre à jour ou retirer automatiquement les blocs de chaque recipe. Ce n'est pas bloquant, mais un futur `composer recipes:update` pourrait mal gérer ce fichier.

### Commit `abdf5af` « EasyAdmin + crud pays »

**Installation :** `composer require easycorp/easyadmin-bundle` (version 5). Ce que ça ajoute :

- deux bundles dans `config/bundles.php` : `EasyAdminBundle` et `TwigComponentBundle`, une dépendance d'EasyAdmin ;
- `config/routes/easyadmin.yaml`, qui charge les routes générées par EasyAdmin.

**`src/Controller/Admin/DashboardController.php`** : la page d'accueil du back-office.

- `#[AdminDashboard(routePath: '/admin', routeName: 'admin')]` : l'admin est accessible sur **`/admin`**.
- `index()` affiche le template `templates/Admin/dashboard.html.twig`. Ce template, vide pour l'instant, hérite de la mise en page d'EasyAdmin.
- `configureDashboard()` définit le titre « TintinSymfony ».
- `configureMenuItems()` construit le menu de gauche avec un `yield` par entrée : Dashboard et Pays.

**`src/Controller/Admin/PaysCrudController.php`** : le CRUD (liste, création, modification, suppression) des pays, généré par `make:admin:crud`.

- `getEntityFqcn()` indique à EasyAdmin quelle entité il gère.
- `configureFields()` liste les champs affichés, avec leur type (`IdField`, `NumberField`, `TextField`) et leur visibilité. Par exemple, `id` et `code` sont masqués dans la liste grâce à `hideOnIndex()`.

**Bilan de l'étape :** une première table métier est branchée à Doctrine et administrable via `/admin`. C'est le modèle qui sera répété pour toutes les autres entités à l'étape 3.

---

## Étape 3 : les autres entités et leurs CRUD (26-27/03/2026)

On applique la même méthode qu'à l'étape 2 : copier la table de l'ancienne base, renommer les champs, faire `make:entity`, vérifier avec `doctrine:schema:validate`, puis créer la migration et le CRUD EasyAdmin. Pour les entités qui ont des clés étrangères, on commence par celles qui n'en ont pas.

### Le modèle de données obtenu

```
Pays 1───n Editeur 1───n Objet n───1 Serie
                           │  │
               Categorie 1─┘  ├── n───n Personnage   (table de liaison objet_personnage)
                              └── 1───n ObjetImage
```

**`Objet` est l'entité centrale** : c'est un objet de collection Tintin. Ses champs :

- **description** : `nom`, `description`, `reference`, `taille`, `poids`, `annee`, `mois` ;
- **commerce** : `prix` (défaut `-1.00`, sans doute pour « prix inconnu »), `montantAchat` ;
- **possession** : `estPossede`, `descriptionPossession`, `rangement` ;
- **gestion** : `actif`, `createdAt`, `updatedAt`.

### Commit `325d152` « Finalisation des entités et crud principaux » (26/03)

**Entités créées : `Categorie`, `Editeur`, `Objet`, `Personnage`, `Serie`**, chacune avec son repository.

**Relations ManyToOne / OneToMany.** Elles sont toujours déclarées des deux côtés :

- côté « plusieurs », un `#[ORM\ManyToOne(inversedBy: 'objets')]` : c'est lui qui porte la clé étrangère `xxx_id` en base. Par exemple, `Objet::$editeur` correspond à la colonne `objet.editeur_id` ;
- côté « un », un `#[ORM\OneToMany(mappedBy: 'editeur')]` avec une `Collection` initialisée dans le constructeur, et les méthodes `addObjet()` / `removeObjet()` générées par le maker. Exemple : `Editeur::$objets`.
- `Pays` reçoit ainsi une collection `editeurs`, et `Editeur` reçoit `pays`. C'est le remplacement de l'ancienne colonne texte `edipays` par une vraie clé `pays_id`, remplie par un `UPDATE … JOIN` sur `alpha2`.

**`__toString()`** dans chaque entité : EasyAdmin s'en sert pour afficher une entité liée dans une liste déroulante ou un tableau. Par exemple, `Pays` renvoie `nomFr`.

**Migration `Version20260326213856`** : elle adapte les tables copiées au mapping Doctrine.

- Conversion des types : `TEXT` devient `LONGTEXT`, `DOUBLE`/`FLOAT` devient `DECIMAL(6,2)` pour les montants. Le type DECIMAL évite les erreurs d'arrondi sur les prix.
- Création des **vraies clés étrangères** (`FOREIGN KEY`) et des index sur `editeur.pays_id`, `objet.serie_id`, `objet.editeur_id` et `objet.categorie_id`.

**Migrations `…213944` et `…214106`** : elles sont **identiques**. Les deux remettent `actif` à `DEFAULT false`. C'est un aller-retour classique : MySQL stocke `false` comme `0`, donc Doctrine détecte une différence à chaque fois. Ce n'est pas grave, mais c'est un doublon.

**CRUD EasyAdmin** pour chaque entité :

- `AssociationField` pour les relations : liste déroulante en formulaire, libellé `__toString()` en affichage ;
- `MoneyField` en EUR pour `prix` et `montantAchat` ;
- `BooleanField` pour `actif` et `estPossede` ;
- `ImageField` (upload simple d'EasyAdmin) pour les images de catégories, éditeurs et personnages, stockées dans `public/images/<entité>/`.

**Dashboard** : le menu contient maintenant Objet, Éditeur, Série, Catégorie, Personnage et Pays, avec des icônes Font Awesome.

### Commit `67bb7c6` « Objet personnage géré » (27/03)

**Relation ManyToMany Objet ↔ Personnage.** Un objet représente plusieurs personnages, et un personnage apparaît sur plusieurs objets.

- Côté `Objet` (propriétaire) : `#[ORM\ManyToMany(inversedBy: 'objets')]` et `#[ORM\JoinTable(name: 'objet_personnage')]`. Le `JoinTable` force le nom de la table de liaison existante. Sans lui, Doctrine aurait choisi le même nom par défaut, mais il est ici explicite.
- Côté `Personnage` : `#[ORM\ManyToMany(mappedBy: 'personnages')]`.
- `Personnage::__toString()` affiche l'alias s'il existe (« Tintin »), sinon « prénom nom ».
- Dans le CRUD Objet : `AssociationField::new('personnages')` donne un sélecteur multiple.

> Point d'attention : la migration `Version20260327172905` contient **`DROP TABLE objet_image` et `DROP TABLE objet_personnage`**. Doctrine l'a générée au moment où ces tables existaient en base sans être encore mappées. La migration suivante (`8c2551a`) renomme un index de `objet_personnage`, ce qui montre que la table n'a pas été réellement supprimée (migration non jouée ou données recopiées). **Si on rejoue toutes les migrations sur une base remplie, ces deux tables seraient vidées.** On en reparle à l'étape 5.

### Commit `8c2551a` « migration » (27/03)

Il ajoute les clés étrangères de la table de liaison `objet_personnage` vers `objet` et `personnage`, avec **`ON DELETE CASCADE`** : supprimer un objet supprime ses liens vers les personnages, mais pas les personnages eux-mêmes.

### Commit `f498af6` « Ajout lien images dans objet + correction return qui ne gérait pas les null » (27/03)

**Nouvelle entité `ObjetImage`** : `id`, `image` (nom de fichier), `ordre` et `objet` en ManyToOne. Un objet a donc plusieurs photos, triées par `ordre`.

- Côté `Objet` : `OneToMany` `objetImages`.
- Migration : l'ancienne clé primaire composite `(id, objet_id)` est remplacée par une clé simple `(id)`, et une vraie clé étrangère vers `objet` est ajoutée.

**Correction des `__toString()`** : `return $this->getNom();` devient `return $this->getNom() ?? '';`.

- `__toString()` doit obligatoirement renvoyer une `string`.
- Sur une entité neuve (formulaire de création), le nom vaut `null`, ce qui provoquait une erreur fatale.
- Avec `?? ''`, la méthode renvoie une chaîne vide dans ce cas.

**Bilan de l'étape :** tout le modèle de données est en place et administrable. Il reste à gérer correctement l'upload des images (étape 4).

---

## Étape 4 : gestion des images avec VichUploader et mise en forme de l'admin (29/03/2026)

### Pourquoi VichUploader ?

L'`ImageField` d'EasyAdmin utilisé à l'étape 3 fait un upload très basique. Il ne sait pas renommer proprement les fichiers et ne supprime pas l'ancien fichier lors d'un remplacement. **VichUploaderBundle** relie un fichier uploadé à une propriété d'entité et gère tout le cycle de vie du fichier : nommage, déplacement, suppression.

### Commit `2896a6d` « Mise à jour et formatage des formulaires et index easyAdmin »

**1. Installation et configuration.** Après `composer require vich/uploader-bundle`, le fichier `config/packages/vich_uploader.yaml` déclare un **mapping** par type d'image :

```yaml
editeur:
    uri_prefix: /images/editeur                                       # URL publique
    upload_destination: '%kernel.project_dir%/public/images/editeur' # dossier sur le disque
    namer: Vich\UploaderBundle\Naming\SmartUniqueNamer               # nom de fichier unique
```

Il y a quatre mappings : `editeur`, `personnage`, `objet` et `categorie`. Le `SmartUniqueNamer` garde le nom d'origine en le « slugifiant » et ajoute un suffixe unique. Par exemple, `Milou chien.jpg` devient `milou-chien-65f3a1b2.jpg`, ce qui évite d'écraser un fichier existant.

**2. Le pattern dans les entités** `Categorie`, `Editeur` et `Personnage` :

```php
#[Vich\Uploadable]                       // sur la classe
class Editeur {
    #[Vich\UploadableField(mapping: 'editeur', fileNameProperty: 'image')]
    private ?File $imageFile = null;     // le fichier uploadé : NON stocké en base

    private ?string $image = null;       // le nom du fichier : stocké en base

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    public function setImageFile(?File $imageFile): void {
        $this->imageFile = $imageFile;
        $this->updatedAt = new \DateTimeImmutable();   // ← indispensable
    }
}
```

Pourquoi modifier `updatedAt` dans `setImageFile()` ?

- `imageFile` n'est pas une colonne Doctrine. Si on ne change **que** l'image, Doctrine considère que rien n'a changé et n'enregistre rien.
- VichUploader s'appuie sur les événements Doctrine. Sans changement détecté, le fichier ne serait donc jamais enregistré.
- Modifier `updatedAt`, qui est une vraie colonne, force la mise à jour.

C'est pour cette raison que la migration `Version20260329084339` ajoute la colonne `updated_at` à `categorie`, `editeur` et `personnage`.

**3. Dans les CRUD**, chaque image est déclarée avec trois champs :

- **en liste** : `ImageField::new('image')->hideOnForm()->setBasePath('/images/xxx')` affiche la miniature ;
- **en formulaire** : `Field::new('imageFile')->setFormType(VichImageType::class)->onlyOnForms()` affiche le champ d'upload. L'option `allow_delete` ajoute une case « supprimer », et `image_uri` affiche un aperçu ;
- **en formulaire, en lecture seule** : `TextField::new('image')->setDisabled()` montre le nom du fichier actuel.

**4. Mise en forme d'EasyAdmin** (le gros du commit) :

- **`configureCrud()`** règle le nombre de lignes par page avec `setPaginatorPageSize()`.
- **`setColumns(n)`** place les champs sur une grille Bootstrap de 12 colonnes, et **`FormField::addRow()`** force un retour à la ligne. Ensemble, ils permettent d'avoir plusieurs champs sur une même ligne du formulaire.
- **`FormField::addTab()`** crée des onglets. Le formulaire Objet en a trois : **Principal**, **Images** et **Possession**.
- **`formatValue(fn ($value) => $value ?: '-')`** affiche `-` ou un blanc à la place d'une valeur vide dans la liste.
- Les libellés sont francisés (« Année de Création », « Nom courant » pour l'alias…).
- `NumberField` est remplacé par `IntegerField` pour les années et les mois, pour ne pas avoir de décimales.

### Commit `cc08985` « Correction de order + divers anomalies du crud »

**1. Images multiples d'un Objet**, la partie la plus intéressante.

L'entité `ObjetImage` reçoit elle aussi le traitement VichUploader : mapping `objet`, `imageFile`, `updatedAt`. Sa colonne `ordre` a désormais une valeur par défaut de `1`.

Un sous-formulaire **`src/Form/ObjetImageType.php`** est créé. Il contient deux champs :

- `ordre` (`IntegerType`) ;
- `imageFile` (`VichImageType`).

Dans `ObjetCrudController`, l'`AssociationField` est remplacé par un **`CollectionField`** :

```php
yield CollectionField::new('objetImages')
    ->setEntryType(ObjetImageType::class)   // chaque ligne = un sous-formulaire ObjetImageType
    ->allowAdd()->allowDelete()             // boutons + / ×
    ->setFormTypeOption('by_reference', false)
    ->renderExpanded();
```

Avec ce champ, on ajoute ou retire des images **directement dans le formulaire de l'objet**. Avant, il fallait sélectionner des `ObjetImage` créées séparément.

- **`by_reference: false`** oblige Symfony à appeler `addObjetImage()` et `removeObjetImage()` au lieu de modifier la collection directement. C'est indispensable pour que `setObjet($this)` soit appelé et que la clé étrangère soit renseignée.

Côté `Objet`, la relation devient :

```php
#[ORM\OneToMany(..., cascade: ['persist', 'remove'], orphanRemoval: true)]
```

- **`cascade: ['persist']`** : quand on enregistre l'objet, ses nouvelles images sont enregistrées avec lui.
- **`cascade: ['remove']`** : supprimer l'objet supprime ses images.
- **`orphanRemoval: true`** : une image retirée de la collection est supprimée de la base (et son fichier, grâce à Vich).

**2. Correction de `createdAt` dans `Objet`.** La valeur par défaut SQL `CURRENT_TIMESTAMP` est retirée, et le constructeur fait maintenant `$this->createdAt = new \DateTimeImmutable();`. La date est donc fixée côté PHP à la création de l'entité, ce qui évite qu'un `null` soit envoyé à une colonne `NOT NULL`.

**3. `Categorie.image` devient nullable**, pour pouvoir créer une catégorie sans image. Le setter accepte `?string`.

**4. Tris par défaut** avec `setDefaultSort()` : éditeurs par `nom`, pays par `alpha2`, personnages par `alias`. La pagination est aussi ajustée : 20 objets par page, 15 pays par page.

**Bilan de l'étape :** le back-office est complet. Toutes les entités sont éditables proprement, avec upload d'images, et un objet gère sa galerie de photos ordonnées directement dans son formulaire.

---

## Étape 5 : les migrations retirées puis remises (06/05 et 21/06/2026)

### Rappel : à quoi servent les migrations ?

Une migration est une classe PHP (`migrations/VersionAAAAMMJJHHMMSS.php`) qui contient le SQL permettant de faire évoluer la base :

- **`up()`** applique la modification ;
- **`down()`** l'annule.

Doctrine enregistre les migrations déjà jouées dans la table **`doctrine_migration_versions`**. La commande `doctrine:migrations:migrate` joue toutes celles qui n'y figurent pas encore.

### Ce qui s'est passé dans git

| Date | Commit | Action |
|---|---|---|
| 23/03 → 29/03 | étapes 2 à 4 | 10 migrations créées au fil de l'eau |
| 06/05 | `c2cc97a` « Retrait des migrations » | les 10 fichiers sont supprimés |
| 06/05 et 07/05 | *(aucun commit)* | 2 nouvelles migrations jouées en base mais **jamais commitées** |
| 21/06 | `1649558` « Gallerie modif » | les 10 anciens fichiers reviennent, à l'identique |

Le retrait était probablement une tentative de « repartir propre » avant d'ajouter la sécurité (étape 6). Les migrations historiques avaient été générées sur une base copiée à la main, et elles étaient incohérentes si on les rejouait depuis zéro. Leur retour dans le commit « Gallerie modif » ressemble à une restauration involontaire, par exemple un `git checkout` ou un `git add .`.

### État réel constaté (`doctrine:migrations:list`, le 14/10/2026)

| Migration | Statut |
|---|---|
| Les 10 de mars (`Version20260323212805` … `Version20260329160139`) | **not migrated** |
| `Version20260506162858` | migrated, **not available** (le fichier n'existe plus) |
| `Version20260507162228` | migrated, **not available** (le fichier n'existe plus) |

`doctrine:schema:validate` répond quant à lui que **la base est synchronisée avec les entités**. Les tables sont donc correctes aujourd'hui, mais l'historique des migrations ne correspond plus à la réalité.

### ⚠️ Les risques

1. **Ne pas lancer `doctrine:migrations:migrate` en l'état.** Doctrine essaierait de jouer les 10 migrations de mars, considérées comme nouvelles :
   - `Version20260327172905` exécuterait **`DROP TABLE objet_image` et `DROP TABLE objet_personnage`**, ce qui supprimerait toutes les images des objets et tous les liens objet ↔ personnage ;
   - les autres échoueraient, par exemple en essayant de créer une clé étrangère qui existe déjà.
2. **Le projet ne peut pas être réinstallé depuis git.** Les tables `user` et `reset_password_request` (étape 6) ont été créées par les 2 migrations non commitées. Sur un autre poste, aucune migration ne les créerait. De plus, les tables métier n'ont jamais été créées par une migration : elles ont été copiées en SQL depuis l'ancienne base `tintin`.
3. **Les images uploadées ne sont pas dans git.** `/public/images/` est dans le `.gitignore` (depuis le commit `325d152`). C'est normal, mais il faut sauvegarder ce dossier à part, en même temps que la base.

### Piste de correction (non appliquée)

La solution habituelle est de repartir d'une **migration de référence unique** (« baseline ») :

1. Sauvegarder la base (`mysqldump`) et le dossier `public/images/`.
2. Supprimer les 10 fichiers de `migrations/`.
3. Générer une migration qui crée le schéma complet : `php bin/console doctrine:migrations:dump-schema`.
4. Nettoyer la table de suivi et y marquer cette baseline comme déjà jouée, sans l'exécuter :
   - `php bin/console doctrine:migrations:version --delete --all` ;
   - `php bin/console doctrine:migrations:version --add --all`.
5. Committer la baseline. À partir de là, on reprend le cycle normal `make:migration` → `migrate` → commit.

Pour une installation sur un nouveau poste, il faudrait aussi un moyen de charger les données (dump SQL ou fixtures).

**Bilan de l'étape :** il n'y a pas de code fonctionnel ici, mais c'est un point de dette technique à régler avant tout déploiement ou nouvelle migration.

---

## Étape 6 : sécurité, login, mot de passe oublié et mails (07/05 et 22/05/2026)

### Commit `d458dc3` « Sécurité » (07/05)

Il est entièrement généré par les commandes du MakerBundle (détaillées dans l'historique) : `make:user`, `make:security:form-login` et `make:reset-password`.

**1. L'entité `User`** (`src/Entity/User.php`)

- Elle implémente **`UserInterface`** et **`PasswordAuthenticatedUserInterface`**. Ce sont les deux interfaces dont Symfony Security a besoin pour gérer un utilisateur qui se connecte avec un mot de passe.
- **Champs** :
  - `email`, qui sert d'identifiant et est unique (`UniqueConstraint`) ;
  - `roles` (un tableau stocké en JSON) ;
  - `password`, stocké **haché** et jamais en clair.
- **Champs ajoutés à la main avec `make:entity`** : `alias`, `newsletter` et `createdAt`.
- `getUserIdentifier()` renvoie l'email.
- `getRoles()` ajoute toujours `ROLE_USER` : tout utilisateur connecté a au moins ce rôle.
- `__serialize()` est fourni par le maker. Dans la session, il remplace le hash du mot de passe par un hash CRC32. Le vrai hash n'est donc jamais stocké en session, mais Symfony peut quand même détecter un changement de mot de passe et déconnecter les autres sessions.

**2. `config/packages/security.yaml`**

- **`password_hashers: auto`** : Symfony choisit le meilleur algorithme disponible, aujourd'hui bcrypt ou argon2.
- **`providers.app_user_provider`** : les utilisateurs sont chargés depuis Doctrine, en les cherchant par `email`.
- **Firewall `main`** :
  - `form_login`, avec la route `app_login` qui sert à la fois à afficher le formulaire et à le traiter, et une protection CSRF ;
  - `logout`, avec la route `app_logout`.
- **`access_control`** : les règles sont **toujours commentées** (voir le point d'attention plus bas).

**3. `SecurityController`**

- **`/login`** : il affiche le formulaire. `AuthenticationUtils` lui fournit la dernière erreur et le dernier email saisi. Ce contrôleur ne vérifie pas lui-même le mot de passe : c'est le firewall qui intercepte le POST.
- **`/logout`** : la méthode est vide, car le firewall intercepte la route.
- Le template `security/login.html.twig` est celui du maker, avec un lien « Mot de passe oublié ? » ajouté.

**4. Mot de passe oublié** (bundle `symfonycasts/reset-password-bundle`)

- **`ResetPasswordController`**, sous `/reset-password` :
  1. on saisit son email ;
  2. un mail est envoyé avec un lien contenant un **jeton à usage unique et à durée limitée** ;
  3. on arrive sur la page « vérifiez vos mails » ;
  4. le lien mène au formulaire de nouveau mot de passe ;
  5. le mot de passe est haché et enregistré, puis on est redirigé vers `app_home`.
- **`ResetPasswordRequest`** (entité et repository) stocke les jetons en base, sous forme hachée.
- **Formulaires** : `ResetPasswordRequestFormType` (l'email) et `ChangePasswordFormType` (le mot de passe deux fois, avec contraintes de longueur et de force).
- **Templates** : `templates/reset_password/` (`request`, `check_email`, `email`, `reset`).
- **Par sécurité**, la page « vérifiez vos mails » s'affiche même si l'email n'existe pas. Un attaquant ne peut donc pas savoir quels emails sont inscrits.

**5. `HomeController`** : la route `/home` (`app_home`) est créée, avec le template par défaut du maker. C'est la cible de redirection après un reset.

**6. Les mails en local avec Mailpit**

- Dans `.env`, `MAILER_DSN=smtp://127.0.0.1:1025`. Mailpit est un faux serveur SMTP qui capture tous les mails, consultables sur `http://localhost:8025`.
- Dans `messenger.yaml`, la ligne `SendEmailMessage: async` est **commentée**.
  - Par défaut, Symfony ne s'envoie pas les mails tout de suite : il les met en file d'attente dans la table `messenger_messages`, et il faut un worker (`messenger:consume`) pour les traiter.
  - En commentant cette ligne, les mails partent immédiatement, ce qui est plus simple en développement.

### Commit `6e33d9a` « EasyAdmin User » (07/05)

`UserCrudController` affiche les utilisateurs dans l'admin : email, alias, rôles (`ArrayField`), newsletter et date de création. Le mot de passe est affiché désactivé et masqué en liste.

### Commit `8315245` « Mails + Todo » (22/05)

- **`Todo.txt`** : la page d'accueil avec la navbar, et la page de recherche et d'affichage.
- **`messenger.yaml`** : ajout du commentaire `TODO : Décommenter avant la mise en prod`.

### ⚠️ Points d'attention

1. **L'admin n'est pas protégée.** Dans `security.yaml`, la ligne `- { path: ^/admin, roles: ROLE_ADMIN }` est commentée, et aucun `#[IsGranted]` n'existe dans le code. **N'importe qui peut ouvrir `/admin`** et modifier ou supprimer les données, y compris les utilisateurs. Il suffit de décommenter cette ligne et de donner `ROLE_ADMIN` à ton compte.
2. **On ne peut pas définir de mot de passe depuis l'admin.** Le champ `password` est désactivé, et il n'y a ni formulaire d'inscription ni commande de création d'utilisateur. Les comptes sont donc créés soit en SQL, avec un hash généré par `php bin/console security:hash-password`, soit via « mot de passe oublié ».
3. **L'expéditeur du mail de reset est l'adresse fictive du maker**, `site@mondomain.com` (`ResetPasswordController.php:160`). Il faudra la remplacer avant la mise en production.
4. **Le formulaire de login est encore le modèle brut du maker**, en anglais et sans style.

**Bilan de l'étape :** l'authentification fonctionne (login, logout, reset par mail), mais rien n'est encore réellement protégé.

---

## Étape 7 : front, page d'accueil, polices Tintin et galerie (04/06 et 21/06/2026)

C'est le début de la partie publique du site, « Les Figurines de Tintin ». Les maquettes `visuel_accueil.png` et `visuel_recherche.png` sont ajoutées dans le commit `843d115`, puis déplacées dans `docs_annexes/` au commit `3c8606d`.

### Commit `843d115` « Accueil Gallerie » (04/06)

**1. Le layout commun `templates/base.html.twig`**

- Ajout de la balise `<meta name="viewport">`, indispensable pour le responsive sur mobile.
- Font Awesome est chargé via un **kit** : un script externe `kit.fontawesome.com/…js`.
- Une **navbar** est commune à toutes les pages :
  - un bouton burger `☰` (mobile) ;
  - le logo, avec le texte « Les Figurines de Tintin » ;
  - les liens Accueil et La galerie ;
  - une icône utilisateur qui pointe vers `/test`.
- Les liens utilisent `url('nom_de_route')` : on référence le **nom** de la route, jamais l'URL en dur. Si l'URL change, les liens suivent automatiquement.

**2. Le CSS global `assets/styles/app.css`**

- **Variables CSS** dans `:root` : `--main-color: black` et `--bg-color: #f1efe9` (un beige papier).
- Un **reset** (`margin: 0; padding: 0; box-sizing: border-box`).
- **Deux polices Tintin**, déclarées avec `@font-face` à partir de `assets/fonts/TintinMajuscule.ttf` et `TintinMinuscule.ttf`. On les utilise avec les classes `.PoliceTintin` et `.PoliceTintinMin`. AssetMapper réécrit automatiquement les `url("../fonts/…")` vers les fichiers versionnés.
- **La navbar** :
  - `position: sticky; top: 0` la fixe en haut de l'écran au défilement ;
  - `display: flex` avec `justify-content: space-between` répartit le logo, les liens et l'icône.
- **Responsive** : sous 768px, `@media (max-width: 768px)` masque les liens et affiche le burger. La classe `.active` réaffiche le menu en colonne.

**3. Le JS `assets/js/base.js`**, importé dans `app.js` : un clic sur le burger ajoute ou retire la classe `active` du menu.

**4. La page d'accueil**

- La route `app_home` passe de `/home` à **`/`**.
- Le template `home/index.html.twig` :
  - charge une feuille de style propre à la page (`home.css`) dans `{% block stylesheets %}`, avec `{{ parent() }}` pour garder celles du layout ;
  - affiche un titre « BIENVENUE DANS L'UNIVERS DE TINTIN » en police Tintin et l'image `images/illustrations/accueil.png` ;
  - utilise une grille CSS sur 2 colonnes, qui passe à 1 colonne sur mobile.

**5. Les contrôleurs `GalleryController` (`/gallery`) et `TestController` (`/test`)** sont créés avec le template par défaut du maker.

### Commit `1649558` « Gallerie modif » (21/06)

**1. `GalleryController` : la galerie paginée**, sur la route `/galerie` (renommée). Le code est très commenté. Voici la logique :

```php
$page   = max(1, $request->query->getInt('page', 1)); // ?page=2, jamais < 1
$limit  = 24;                                         // 24 objets par page
$offset = ($page - 1) * $limit;                       // page 3 → on saute 48 objets
$objets = $objetRepository->findAllWithSerie($limit, $offset);
$totalPages = (int) ceil($objetRepository->countAllActive() / $limit);
if ($totalPages > 0 && $page > $totalPages) → redirection vers la dernière page
```

- `Request` et `ObjetRepository` sont **injectés automatiquement** dans les paramètres de la méthode. C'est l'autowiring : Symfony fournit l'objet à partir du type indiqué.
- `getInt()` convertit et sécurise le paramètre d'URL.

**2. Deux méthodes ajoutées dans `ObjetRepository`** (QueryBuilder Doctrine)

- **`findAllWithSerie($limit, $offset)`** :
  - elle ne garde que les objets `actif = true` et les trie par `id` ;
  - `setMaxResults()` et `setFirstResult()` correspondent au `LIMIT` et à l'`OFFSET` SQL ;
  - **`leftJoin('p.serie', 's')->addSelect('s')`** charge la série **dans la même requête**. Sans `addSelect`, Doctrine ferait une requête supplémentaire par objet pour récupérer sa série (le problème du « N+1 »).
- **`countAllActive()`** : un `COUNT` sur les objets actifs, pour calculer le nombre de pages.

**3. Le template `gallery/index.html.twig`**

- **Une grille de cartes** :
  - `grid-template-columns: repeat(auto-fit, minmax(220px, 1fr))` met autant de colonnes que possible, d'au moins 220px chacune, ce qui rend la grille responsive sans media query ;
  - le CSS est dans un nouveau fichier `assets/styles/gallery.css`, nommé façon BEM : `gallery-card__image`, `gallery-card__content`…
- **Chaque carte** :
  - l'**image principale** est celle qui a `ordre == 1`, sinon la première de la collection, sinon une image par défaut `000000.PNG` ;
  - l'éditeur est affiché en surimpression, avec le nom, la série et l'année ;
  - un **crayon** mène à l'édition de l'objet dans l'admin (`/admin/objet/{id}/edit`).
- `{% for … %}{% else %}…{% endfor %}` : la partie `else` s'affiche si la liste est vide.
- **La pagination** :
  - liens `<<` (première page), `<` (−10 pages), une fenêtre de 7 numéros autour de la page courante, `>` (+10 pages) et `>>` (dernière page) ;
  - la page courante a la classe `is-active`.

**4. La navbar et l'admin**

- **Navbar** : ajout d'un lien **Admin**, et l'icône utilisateur passe en `fa-regular`.
- **`DashboardController`** :
  - la route `admin` est renommée **`app_admin`** ;
  - le titre devient « Admin TintinSymfony » ;
  - le **thème sombre** est activé par défaut (`setDefaultColorScheme('dark')`).
- **`dashboard.html.twig`** surcharge le bloc `header_logo` d'EasyAdmin pour que le logo de l'admin ramène à l'accueil du site (`/`).

**5. Les 10 migrations reviennent** dans ce commit (voir étape 5).

### ⚠️ Points d'attention

1. **Le burger ne fonctionne plus après la première navigation.**
   - Turbo Drive est actif (`controllers.json`) : en changeant de page, Turbo remplace le `<body>` sans recharger le JS.
   - Le `addEventListener` de `base.js` reste donc attaché à l'ancien bouton, qui n'existe plus.
   - La solution Symfony est d'en faire un **contrôleur Stimulus** (`data-controller="navbar"`). Stimulus se rebranche automatiquement sur les nouveaux éléments.
2. **Il reste des requêtes « N+1 » dans la galerie.** La série est bien chargée d'un coup, mais pas `objet.editeur` ni `objet.objetImages`. Avec 24 cartes, ça fait jusqu'à 48 requêtes SQL en plus (visible dans la barre de debug, onglet Doctrine). Il suffit d'ajouter `leftJoin` et `addSelect` pour `editeur` et `objetImages`. Attention : avec une collection jointe, `setMaxResults` doit être combiné avec le `Paginator` de Doctrine pour rester juste.
3. **`{{ objet.editeur.nom }}` peut poser problème** si un objet n'a pas d'éditeur (la relation est nullable). Selon la configuration Twig, ça affiche du vide ou ça lève une erreur. Plus sûr : `{{ objet.editeur.nom ?? '' }}`.
4. **Le crayon d'édition et le lien Admin sont visibles par tout le monde.** Le TODO dans le template le note déjà. La solution est `{% if is_granted('ROLE_ADMIN') %}`, à combiner avec la protection de `/admin` vue à l'étape 6.
5. **Il reste des éléments de test** : `TestController` et la page `/test` (template par défaut du maker), vers laquelle pointe l'icône utilisateur. Le `console.log` d'exemple est aussi toujours dans `app.js`.
6. **Les images du site** (`public/images/illustrations/logo.png`, `accueil.png`) sont ignorées par git, comme tout `public/images/`. Les illustrations fixes du site auraient plutôt leur place dans `assets/images/`, versionnées et servies par AssetMapper.

**Bilan de l'étape :** le site public existe, avec une navbar, une page d'accueil et une galerie paginée des objets actifs.

---

## Étape 8 : popup carrousel de la galerie et dernières modifications (12/10 et 14/10/2026)

### Commit `3c8606d` « Docs : ajout du dossier docs_annexes » (12/10)

Les maquettes `visuel_accueil.png` et `visuel_recherche.png`, jusque-là à la racine, sont déplacées dans `docs_annexes/`. On y ajoute aussi le fichier `Historique des actions projet Symfony…md`, le journal de bord des commandes utilisées.

### Commit `deda722` « Galerie : popup avec carrousel d'images (WIP) » (12/10)

**Objectif :** quand on clique sur une carte de la galerie, une fenêtre s'ouvre avec le nom, la série, l'année, la description et **toutes les photos** de l'objet, qu'on fait défiler avec ‹ et ›.

**Le principe : passer les données du serveur (Twig) au JavaScript avec les attributs `data-*`.**

- Chaque carte porte ses informations dans des attributs HTML : `data-description`, `data-serie`, `data-annee` et `data-images` (la liste des URL d'images).
- En JS, `element.dataset.description` lit `data-description`.
- Il n'y a donc ni requête AJAX ni API : tout est déjà dans la page.

**Le HTML de la popup** (`gallery/index.html.twig`) existe **une seule fois** dans la page. Le JS remplit son contenu à chaque clic. Il contient :

- un bouton de fermeture `×` ;
- les zones `modalTitle`, `modalMeta` et `modalDescription` ;
- le carrousel : un bouton ‹, une `<img id="popup-image">` et un bouton ›.
- `aria-hidden` indique aux lecteurs d'écran si la popup est visible.

**Le CSS** (`gallery.css`) :

- `.popup` : `position: fixed; inset: 0`, ce qui couvre tout l'écran avec un fond noir à 70 %. Elle est cachée par défaut (`display: none`).
- `.popup.active` : `display: flex`, qui affiche la popup et centre son contenu. **Ouvrir ou fermer la popup revient à ajouter ou retirer la classe `active`.**
- Le `z-index: 2000` la place au-dessus de la navbar sticky.
- Les flèches sont positionnées en absolu, centrées verticalement avec `top: 50%; transform: translateY(-50%)`.

**Le JS** (`assets/js/gallery.js`, chargé uniquement sur la page galerie via `{% block javascripts %}`) :

- **Tout est enveloppé dans `document.addEventListener('turbo:load', …)`.**
  - Avec Turbo, `DOMContentLoaded` ne se déclenche qu'au tout premier chargement.
  - `turbo:load` se déclenche après **chaque** navigation, ce qui permet de réattacher les clics aux nouvelles cartes.
  - C'est la bonne réponse au problème décrit pour le burger à l'étape 7.
- `if (!popup) return;` : le script ne fait rien sur les autres pages.
- **Les clics sont branchés avec `element.onclick = …`** plutôt qu'avec `addEventListener`. Comme `turbo:load` revient à chaque navigation, `onclick` **remplace** le gestionnaire précédent au lieu d'en ajouter un de plus. On évite ainsi qu'un clic ouvre la popup plusieurs fois.
- `event.target.closest('a')` : un clic sur le crayon d'édition suit le lien au lieu d'ouvrir la popup.
- **Carrousel circulaire** :
  - `(currentIndex + 1) % images.length` revient à 0 après la dernière image ;
  - `(currentIndex - 1 + images.length) % images.length` va à la dernière image avant la première.
- La popup se ferme avec le `×` ou en cliquant sur le fond noir (`event.target === popup`).

Cette version était marquée **WIP** parce qu'elle ne fonctionnait pas encore :

- le JS lisait les `data-*` sur la carte (`<article>`), alors que le template les avait mis sur la zone image ;
- le JSON `data-images` était construit à la main en Twig (crochets, guillemets et virgules dans une boucle). C'est fragile : une virgule mal placée ou un guillemet dans un nom de fichier, et `JSON.parse` plante ;
- l'image d'ordre 1 était exclue du carrousel ;
- il restait du code mort : boucles sur un tableau vide, `popup.style.display` en conflit avec la classe `active`, etc.

### Commit `1652b6c` « modif avec claude » (14/10)

Ce commit corrige la version WIP.

**1. `Objet.php` : tri des images**

```php
#[ORM\OrderBy(['ordre' => 'ASC'])]
private Collection $objetImages;
```

Doctrine renvoie maintenant toujours les images triées par `ordre`. Conséquences :

- l'image principale est simplement `objet.objetImages|first`, et la boucle Twig qui cherchait `ordre == 1` disparaît ;
- le carrousel montre les images dans le bon ordre ;
- l'image principale apparaît aussi en premier dans le carrousel.

**2. Template : un JSON fiable**

```twig
{% set imagesCarrousel = [] %}
{% for objetImage in objet.objetImages %}
    {% set imagesCarrousel = imagesCarrousel|merge([asset('images/objet/' ~ objetImage.image)]) %}
{% endfor %}
…
data-images="{{ imagesCarrousel|json_encode|e('html_attr') }}"
```

- On construit un **vrai tableau Twig**, puis `json_encode` produit un JSON toujours valide.
- `e('html_attr')` échappe les guillemets pour qu'ils ne cassent pas l'attribut HTML.
- `asset()` génère la bonne URL publique de chaque image.

**3. JS : les corrections**

- Les données sont lues sur `.gallery-card__image`, là où elles se trouvent vraiment.
- Le titre est repris du `<h2>` de la carte.
- `[data.serie, data.annee].filter(Boolean).join(' - ')` affiche « Série - 1975 », « 1975 » ou rien, sans tiret orphelin.
- Les flèches sont masquées (`classList.toggle('hidden', images.length <= 1)`) quand il n'y a qu'une image ou aucune.
- Le code mort est supprimé et les classes CSS sont alignées entre le HTML et le CSS (`carousel`, `popup-image`).

**4. `DashboardController` : retour au site**

```php
yield MenuItem::section();
yield MenuItem::linkToUrl('Retour au site', 'fa fa-arrow-left', $this->generateUrl('app_home'));
```

Le menu de l'admin a maintenant un séparateur et un lien de retour vers le site public. Le `TODO` correspondant est supprimé.

> Remarque : ce commit est signé par l'auteur `rogamn <rogamn@gmail.com>`, alors que tous les autres le sont par `morgan <chrysgan@gmail.com>`. La configuration git `user.name` / `user.email` n'est probablement pas la même sur ce poste.

### ⚠️ Points d'attention

1. **La galerie fait encore plus de requêtes.** Avec le carrousel, chaque carte parcourt `objet.objetImages`, ce qui déclenche une requête par objet, en plus de celle de l'éditeur (voir étape 7, point 2).
2. **La popup est placée à l'intérieur de `<section class="gallery-grid">`.** Ça fonctionne, puisqu'elle est en `position: fixed`, mais elle serait plus logique juste avant `</main>`.
3. **`data-id="{{objet.id}}"`** sur l'image n'est utilisé nulle part. C'est peut-être une préparation pour une future page de détail.

---

## Récapitulatif : où en est le projet (14/10/2026)

**Ce qui fonctionne**

- Le back-office EasyAdmin complet : 7 entités, upload d'images, galerie de photos ordonnées par objet.
- L'authentification : login, logout, mot de passe oublié par mail (Mailpit en local).
- Le site public : navbar responsive, page d'accueil, galerie paginée avec popup carrousel.

**Reste à faire (d'après `Todo.txt`, les TODO du code et les maquettes)**

- Finir la page d'accueil (`visuel_accueil.png`).
- Créer la page de **recherche** et d'affichage (`visuel_recherche.png`).
- Faire des requêtes d'administration personnalisées dans le dashboard (TODO dans `DashboardController`).
- Corriger le chevauchement entre la galerie et la navbar (TODO dans le template de la galerie).

**Dette technique et sécurité à traiter, par priorité**

1. 🔴 Protéger `/admin` (`access_control` + `ROLE_ADMIN`), et masquer le crayon et le lien Admin aux non-admins (étapes 6 et 7).
2. 🔴 Remettre les migrations d'aplomb avec une baseline, et **ne pas lancer `migrate` d'ici là** (étape 5).
3. 🟠 Convertir le burger en contrôleur Stimulus pour qu'il marche avec Turbo (étape 7).
4. 🟠 Ajouter les jointures `editeur` et `objetImages` dans la requête de la galerie (étapes 7 et 8).
5. 🟡 Avant la production : remettre l'envoi des mails en `async`, changer l'adresse d'expéditeur du reset, franciser et styliser la page de login.
6. 🟡 Nettoyage : `TestController`, le `console.log` d'exemple, les illustrations à déplacer vers `assets/images/`, et les marqueurs Flex du `.gitignore`.
