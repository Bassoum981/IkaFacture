# IkaFacture

IkaFacture est une application Laravel de génération rapide de factures PDF. Elle permet de saisir un client, des prestations, une TVA, une remise, une échéance, des conditions de règlement et des notes, puis de télécharger une facture cohérente avec l'aperçu.

Une facture peut être créée sans compte. Après le téléchargement, l'utilisateur peut créer un compte afin de rattacher cette facture à son espace personnel.

## Prérequis

- PHP 8.3 ou supérieur
- Composer
- Node.js et npm
- SQLite (ou une base compatible Laravel)

## Installation

```bash
git clone <url-du-depot>
cd IkaFacture
composer install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
```

Configurez `DB_CONNECTION` et `DB_DATABASE` dans `.env`, puis exécutez :

```bash
php artisan migrate
npm install
npm run build
```

## Utilisation

Lancez l'application avec :

```bash
php artisan serve
```

Ouvrez ensuite `http://127.0.0.1:8000`. Remplissez le formulaire, ajoutez les lignes de prestation et téléchargez le PDF. Le numéro de facture suit le format `FAC-AAAA-NNNN` et est protégé contre les collisions concurrentes par une séquence annuelle et une contrainte unique.

Après la création d’un compte, le tableau de bord `/tableau-de-bord` permet de consulter l’historique des factures et de régénérer le PDF d’une facture enregistrée.

La page d’accueil explique le service sur `/`. L’éditeur est disponible sur `/creer-facture`, avec un favicon IkaFacture (`IF`), des métadonnées SEO dynamiques, des balises Open Graph et une URL canonique. Les routes publiques sont exposées dans `/sitemap.xml`; `/robots.txt` autorise l’indexation en production et la bloque dans les autres environnements.

## Déploiement Docker

L’image Docker contient uniquement Laravel avec Apache et PHP. MySQL reste un service externe : renseignez son hôte, son port, sa base, son utilisateur et son mot de passe via les variables `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME` et `DB_PASSWORD`.

```bash
docker build -t ikafacture .
docker run --rm -p 8080:80 --env-file .env ikafacture
```

Avant le démarrage, configurez au minimum `APP_KEY`, `APP_ENV=production`, `APP_DEBUG=false`, `DB_CONNECTION=mysql` et les paramètres de connexion MySQL. Exécutez les migrations depuis le conteneur ou votre pipeline de déploiement :

```bash
docker exec <nom-du-conteneur> php artisan migrate --force
```

Les fichiers persistants de `storage/` doivent être montés sur un volume en production si les logos ou les journaux doivent survivre au remplacement du conteneur.

## Tests et qualité

```bash
php artisan test
vendor/bin/pint --dirty --format agent
```

Les tests couvrent notamment la validation des données JSON, les calculs HT/TVA/remise, la création des lignes, le rattachement d'une facture invitée à un compte et la numérotation séquentielle.

## Structure utile

- `app/Http/Controllers/FactureController.php` : validation, calculs, création et téléchargement.
- `resources/views/factures/create.blade.php` : formulaire et aperçu interactif.
- `resources/views/factures/pdf.blade.php` : rendu PDF.
- `database/migrations/` : schéma des factures, clients, lignes et séquences.
- `tests/Feature/` : scénarios métier.
