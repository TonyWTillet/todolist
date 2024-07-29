# ToDo & Co

ToDo & Co est une application de gestion de tâches quotidienne développée avec le framework Symfony. Ce projet a été initialement créé en tant que Minimum Viable Product (MVP) et a été amélioré pour inclure de nouvelles fonctionnalités, corriger des bugs et ajouter des tests automatisés.

## Table des matières

- [Prérequis](#prérequis)
- [Installation](#installation)
- [Configuration](#configuration)
- [Utilisation](#utilisation)
- [Tests](#tests)
- [Contribuer](#contribuer)
- [Diagrammes UML](#diagrammes-uml)
- [Licence](#licence)

## Prérequis

- PHP 8.1 ou supérieur
- Composer
- MySQL ou tout autre SGBD compatible avec Doctrine
- Node.js et npm pour la gestion des assets

## Installation

1. Clonez le repository :

    ```bash
    git clone https://github.com/username/todo-co.git
    cd todo-co
    ```

2. Installez les dépendances PHP avec Composer :

    ```bash
    composer install
    ```

3. Installez les dépendances JavaScript avec npm :

    ```bash
    npm install
    ```

4. Compilez les assets :

    ```bash
    npm run dev
    ```

## Configuration

1. Copiez le fichier `.env` et renommez-le en `.env.local` :

    ```bash
    cp .env .env.local
    ```

2. Configurez les variables d'environnement dans le fichier `.env.local` :

    ```env
    DATABASE_URL="mysql://db_user:db_password@127.0.0.1:3306/db_name"
    ```

3. Créez la base de données et exécutez les migrations :

    ```bash
    php bin/console doctrine:database:create
    php bin/console doctrine:migrations:migrate
    ```

4. Chargez les fixtures pour créer des utilisateurs et des tâches de test :

    ```bash
    php bin/console doctrine:fixtures:load
    ```

## Utilisation

Démarrez le serveur de développement Symfony :

```bash
php bin/console server:run
```

## Tests

1. Installez PHPUnit si ce n'est pas déjà fait :

```bash
composer require --dev phpunit/phpunit
```

2. Exécutez les tests :

```bash
php bin/phpunit
```
## Contribuer

Les contributions sont les bienvenues ! Pour contribuer :

1. Forkez le repository : Cliquez sur le bouton "Fork" en haut de la page du repository:
   
2. Clonez votre fork : Clonez le repository forké sur votre machine locale:
```bash
git clone https://github.com/votre-utilisateur/ToDo-Co.git
cd ToDo-Co
```
3. Créez une branche pour votre fonctionnalité : Créez une nouvelle branche pour travailler sur votre fonctionnalité ou correction de bug:
```bash
git checkout -b feature/nom-de-la-fonctionnalité
cd ToDo-Co
```
4. Faites vos modifications : Apportez les modifications nécessaires dans le code:

5. Commitez vos changements : Enregistrez vos modifications avec un message de commit descriptif:
```bash
git add .
git commit -m "Description des changements"
```
6. Poussez votre branche : Envoyez vos modifications sur GitHub:
```bash
git push origin feature/nom-de-la-fonctionnalité
```
7. Ouvrez une Pull Request : Ouvrez une Pull Request sur GitHub pour que vos modifications soient examinées et fusionnées:

## Diagrammes-uml

- Diagramme de Cas d'Utilisation

![Diagramme de Cas d'Utilisation](./docs/images/Diagramme%20de%20Cas%20d'Utilisation.png)

- Diagramme de Séquence - Créer une Tâche

![Diagramme de Séquence - Créer une Tâche](./docs/images/Diagramme%20de%20Séquence%20-%20Créer%20une%20Tâche.png)

- Diagramme de Séquence - Modifier une Tâche

![Diagramme de Séquence - Modifier une Tâche](./docs/images/Diagramme%20de%20Séquence%20-%20Modifier%20une%20Tâche.png)

- Diagramme de Séquence - Supprimer une Tâche

![Diagramme de Séquence - Supprimer une Tâche](./docs/images/Diagramme%20de%20Séquence%20-%20Supprimer%20une%20Tâche.png)

- Diagramme de Séquence - Ajouter un Utilisateur

![Diagramme de Séquence -  Ajouter un Utilisateur](./docs/images/Diagramme%20de%20Séquence%20-%20Ajouter%20un%20Utilisateur.png)

- Diagramme de Séquence - Modifier un Utilisateur

![Diagramme de Séquence -  Modifier un Utilisateur](./docs/images/Diagramme%20de%20Séquence%20-%20Modifier%20un%20Utilisateur.png)

- Diagramme de Séquence - Supprimer un Utilisateur

![Diagramme de Séquence -  Supprimer un Utilisateur](./docs/images/Diagramme%20de%20Séquence%20-%20Supprimer%20un%20Utilisateur.png)

- Diagramme de Classe

![Diagramme de Classe](./docs/images/diagramme%20de%20classe.png)

     
## Licence
Ce projet est sous licence MIT. Voir le fichier LICENSE pour plus de détails.
