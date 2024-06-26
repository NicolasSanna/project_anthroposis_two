# Projet Anthroposis II

## Résumé du projet et contexte

Dans le cadre de rapports conservés avec des étudiants et de jeunes professeurs dans le milieu universitaire partout en France avant mon arrivée en formation, diverses ressources relatives aux sciences humaines ont été produites et conservées sur l’application Discord qui sert d’espace de discussion. Ces contenus sont divers : histoire, sociologie, économie, philosophie, art, etc L’évolution de cet espace et des publications ont rendu nécessaire la création d’une application web permettant une plus grande visibilité ainsi qu’une meilleure gestion des différents contenus. Il s’agit de passer à l’étape supérieure et de rendre accessible et public l’ensemble des productions réalisées depuis plus de 3 ans. Cette application sera utilisée ensuite par les différents membres inscrits, qui pourront publier de manière autonome du contenu. Les membres seront gérés par des administrateurs : plusieurs niveaux d’autorisations sont prévus.

### Front-end

- Concevoir la maquette des différentes interfaces utilisateur sur l’ensemble du projet en lien avec la charte graphique établie au préalable avec les personnes concernées.
- A partir de la maquette validée précédemment, produire l’ensemble des codes HTML et CSS pour afficher les interfaces statiques et adaptables comme le back-office d’administration et les formulaires d’ajouts d’articles.
- Mettre en place les différents comportements dynamiques en JavaScript comme l’affichage du menu, la suppression de catégorie ou la suppression d'article.

### Back-end

Modéliser et créer une base de données directement avec le langage SQL en utilisant le Système de Gestion de Base de Données Relationnelles (SGBDR) MySQL via l'ORM de Symfony appelé Doctrine permettant de créer les entités nécessaires.
- Créer une interface d’administration à l’usage des administrateurs et des auteurs de contenus (articles).
- Créer une interface de gestion de catégories.
- Mettre en place un système d’authentification (inscription, connexion), d’autorisation et de droits d’accès pour les commentaires et la publication d’articles.

## Spécifications techniques

L’ensemble des interfaces utilisateurs sont alignées sur la charte graphique définie lors du maquettage de l’application. L’affichage doit s’adapter aux différentes tailles d’écrans et permettre un bon rendu de l’ensemble des différentes pages sur différents navigateurs web. Ce projet est présent sur un dépôt Git et stocké sur mon compte personnel GitHub.

## Objectifs et fonctionnalités attendues

L’objectif du projet est de pouvoir déployer et publier sur une application web l’ensemble des divers contenus actuellement présents sur la plateforme de discussion et permettre ainsi une plus grande accessibilité et visibilité.

- Un menu de navigation présent sur toutes les pages du site pour :
  - Accéder à tous les articles ;
  - Accéder à toutes les catégories ;
  - Rechercher un article ;
  - Accéder au back-office d’administration où sont présents les articles en lecture et en écriture en effectuant les quatre opérations du CRUD (*Create Read, Update, Delete*) pour l’utilisateur connecté ;
  - Accéder à la gestion des catégories et des articles (uniquement pour les administrateurs du site) ;
  - Modifier ses informations personnelles (email, nom, prénom, pseudo) ;
- Une page d’accueil avec une présentation des objectifs du site ainsi que la possibilité de consulter les quatre derniers articles publiés récemment.
- Une page pour créer un compte utilisateur et un lien de déconnexion placé au niveau du menu visible pour l’utilisateur connecté.
- Une page de connexion (formulaire).

## Langages utilisés

- PHP 8.1
- SQL
- HTML 5
- CSS 3
- JavaScript

Pour la robustesse du code et sa maintenabilité à terme, j'ai choisi de me servir du framework PHP Symfony.

## Technologies utilisées

- Symfony 6.3
- MySQL 8.x
- Préprocesseur SASS (SCSS)
- Mailhog
- Moteur de template Twig (fourni par Symfony)
- Éditeur de texte pour les articles : TinyMCE

## Outils

- Maquettage : Figma
- Environnement de développement (IDE) : VIsual Studio Code
- Diagramme de classes, d'entités/associations et de relations et MCD : Lopping
- Interface(s) de gestion de bases de données : MySQL Workbench/phpMyAdmin
- Outil de versionning : Git/GitHub
- Serveur de développement : WAMP
- Versioning : Git/Github
