# Historique des actions projet Symfony

- création du projet web
    
    ```bash
    symfony new tintinSymfony --webapp
    ```
    
- installation easyAdmin
- paramétrage de la connection web dans le fichier env et création de la bdd
    
    ```php
    DATABASE_URL="mysql://web:web@127.0.0.1:3306/tintinSymfony?serverVersion=8.0.45&charset=utf8mb4"
    
    # Puis éxecuter la commande ci-dessous pour créer la base
    php bin/console doctrine:database:create
    ```
    
- script pour copier des tables de tintin vers tintinSymfony
    
    ```sql
    CREATE TABLE tintinSymfony.pays LIKE tintin.pays;
    INSERT INTO tintinSymfony.pays SELECT * FROM tintin.pays;
    ```
    
- Creation des entités et des crudCrontroller liés
- Règles de migrations
    - l’id de la table devient le champ id : INT AI NN
    - les noms des champs sont raccourcis et francisés
    - les clés étrangères sont se nommer id+nom_table_referencée ex: idpays
    - essayer de mettre des booléens
    - mettre des champs created_at updated_at : correspondance camelCase (symfony) snake_case (mysql)
    - les champs AI ne doivent pas avoir d’id = 0
    - Créer les entités et valider la correspondance

```php
# Créer l'entité correspondant à la table (commencer par les entités sasn clés étrangères)
symfony console make:entity

# Verifier les valeurs par défaut s'il y en a

# Controler le mapping
php bin/console doctrine:schema:validate

# Si echec pour connaitre les modif à faire 
php bin/console doctrine:schema:update --dump-sql

# Si Ok Faire une migration et l'appliquer !! Bien vérifier les modifs qui vont être effectuée !!
php bin/console make:migration
php bin/console doctrine:migrations:migrate
```

- Pays
    - renommage de l’id 0 en 242
    - le code est utilisé dans la table editeur
    - ajout de la méthode pour les jointures et l’affichage dans easyadmin
        
        ```php
        public function __toString():string
        {    
        	return $this->getNomFr();
        }
        ```
        
    - pas de vrai jointure sur l’id
    - création du crud controller
        
        ```php
        symfony console make:crud
        ```
        
        - ajout dans le dashboard : `yield MenuItem::linkTo(PaysCrudController::class, 'Pays', 'fa-duotone fa-solid fa-earth-europe');`
        - modif du pays crud
- Categorie (types)
    - Renommage des champs pour normalisation id / code / nom / image
    - Ajouter une colonne updated_at
    - création crudcontroller et des yield
        
        `yield ImageField::new('image')    ->setUploadDir('images/categorie/')    ->setBasePath('images/categorie/');`
        
    - ajout dans le dashboard
        
        `yield MenuItem::linkTo(CategorieCrudController::class, 'Catégorie', 'fa-duotone fa-solid fa-layer-group');`
        
- Personnage
    - Renommage des champs pour normalisation id / code / nom / image
    - Ajout de la méthode __toString pour prépare l’affichage dans objet
        
        ```php
        public function __toString()
            {
                if($this->getAlias() == ''){
                    return $this->getPrenom().' '.$this->getNom();
                }
                else {
                    return $this->getAlias() ?? '';
                }
            }
        ```
        
    - création crudcontroller et des yield
    - ajout dans le dashboard
    `yield MenuItem::linkTo(PersonnageCrudController::class, 'Personnage', 'fa-duotone fa-solid fa-people-group');`
    - Ajouter une colonne updated_at
- Serie
    - Renommage des champs pour normalisation id / code / nom / image
    - création crudcontroller et des yield
    - ajout dans le dashboard
    `yield MenuItem::linkTo(SerieCrudController::class, 'Série', 'fa-duotone fa-solid fa-layer-group');`
- Editeur
    - Renommage sauf edipays
    - Ajouter une colonne updated_at
    - Ajout de la colonne pays_id
    
    ```php
    update tintinSymfony.editeur editeur
    join tintinSymfony.pays pays on pays.alpha2 = editeur.edipays
    set pays_id = pays.id
    ```
    
    - suppression de la colonne edipays
    - création du crud crontroller et des yield
    - ajout dans le dashboard
        
        `yield MenuItem::linkTo(EditeurCrudController::class, 'Editeur', 'fa-duotone fa-solid fa-industry');`
        
- Objet_Personnage
    - renommage des champs en entite_id
- Objet
    - creation de l’entite
    - renommage et les clés etrangères entite_id
    - création de la realtion avec personnages make entity Objet : personnages / relation / ManyToMany
    - Ajout de sur le ManyToMany côté objet `#[ORM\JoinTable(name: 'objet_personnage')]`
    - Ajout dans objetcrudcontroller `yield AssociationField::new('personnages');`
- Objet_image
    - renommer objid en objet_id
    - création de l’entite id image ordre objet /relation/manytoone
    - Ajout dans le objetCrudController : `yield AssociationField::new('objetImages');`
    - Créeation de la methode __toString getImage
- installation de vichuploader
- Adapter pour chaque classe / controleur l’affichage des images et le telechargement
    
    ```php
    #paramétrer le fichier YAML config/packages/vich_uploader.yaml
    
    vich_uploader:
        db_driver: orm
        mappings:
            editeur:
                uri_prefix: /images/editeur
                upload_destination: '%kernel.project_dir%/public/images/editeur'
                namer: Vich\UploaderBundle\Naming\SmartUniqueNamer
    ```
    
    ```php
    #Cas simple de gestion d'image 
    #Ajouter dans la classe 
    
    use Vich\UploaderBundle\Mapping\Annotation as Vich;
    #[Vich\Uploadable]
    Class Editeur
    
    #Au niveau de l'image (plus facile à lire)
    /* mapping est le paramétrage correspondant dans le fichier vich_uploader.yaml et image la prop associé dans la classe*/
    #[Vich\UploadableField(mapping: 'editeur', fileNameProperty: 'image')]
    private ?File $imageFile = null;
    
    #Ajouter les 2 methodes
    public function getImageFile(): ?File
        {
            return $this->imageFile;
        }
    
        public function setImageFile(?File $imageFile): void
        {
            $this->imageFile = $imageFile;
            $this->updatedAt = new \DateTimeImmutable();
        }
    
    #Ajouter dans le crud controller
    use Vich\UploaderBundle\Form\Type\VichImageType;
    
    yield Field::new('imageFile', 'Fichier')
                ->setFormType(VichImageType::class)
                ->onlyOnForms()
                ->setFormTypeOptions([
                    'required' => false,
                    'allow_delete' => true,
                    'download_uri' => false,
                    'image_uri' => true,
    ]);
    # l'ancien image field peut etre utile pour voir le nom du fichier actuel, l'ideal est de le remplacer par un textfield setdisabled
    yield TextField::new('image')
                ->setDisabled();
    
    ```
    
    - Mise à jour des crud pour un bel affichage avec formatage des données
    - Intégration ObjetImage dans crud Objet
        - Modification de la relation OnteToMany dans Objet ajout
            
            ```jsx
            cascade: ['persist', 'remove'], orphanRemoval: true
            ```
            
        - Ajout dans ObjetImage
            
            ```jsx
            #[Vich\UploadableField(mapping: 'editeur', fileNameProperty: 'image')]
            private ?File $imageFile = null;
            ```
            
        - 
- Authentification
    - creation via les commandes classiques
        
        ```php
        # bundles à installer
        composer require symfony/security-bundle
        composer require symfony/maker-bundle --dev
        composer require symfonycasts/reset-password-bundle
        # Creation de la gestion des USERS
        php bin/console make:user
        symfony console make:entity user
        # ajout des champs alias + newsletter + createdAt
        
        # Creation du formulaire de login
        php bin/console make:security:form-login
        
        #Creation du formulaire de reset
        php bin/console make:reset-password
        
        #Sauvegarde en base
        php bin/console make:migration
        php bin/console doctrine:migrations:migrate
        
        # Dans le fichier templates/security/login.html.twig ajouter
        <a href="{{ path('app_forgot_password_request') }}">    Mot de passe oublié ? </a>
        ```
        
    - création du serveur smtp local
        - [https://mailpit.axllent.org/docs/install/](https://mailpit.axllent.org/docs/install/)
        
        ```php
        wget https://github.com/axllent/mailpit/releases/latest/download/mailpit-linux-amd64.tar.gz
        tar -xzf mailpit-linux-amd64.tar.gz
        chmod +x mailpit
        ./mailpit
        
        # reponse
        SMTP listening on 0.0.0.0:1025
        HTTP listening on 0.0.0.0:8025
        
        # MailPit web
        http://localhost:8025
        
        #env
        
        MAILER_DSN=smtp://127.0.0.1:1025
        ```
        
    - Pour pouvoir faire recevoir le mail reset dans mailpit il faut descativer messenger mqueue le temps du dev
        
        ```php
        # commenter la ligne ci-dessous dans le fichier /config/packages/messenger.yaml
        Symfony\Component\Mailer\Messenger\SendEmailMessage: async
        ```