# Projet de partage de mèmes

Application web développée avec Symfony, dédiée à la publication de mèmes, GIFs et images autour d’un thème donné.  
Le projet est auto-hébergeable et fourni avec un environnement Docker.

## Fonctionnalités

- Publication de contenus visuels (mèmes, GIFs, images)
- Système d’inscription utilisateur
- Validation manuelle des comptes par un administrateur
- Gestion des rôles (utilisateur, administrateur)
- Interface d’administration
- Environnement Docker prêt à l’emploi

## Prérequis

- Docker
- Docker Compose

Aucune installation locale de PHP ou Symfony n’est requise.

## Installation

1. Cloner le dépôt  
   ```git clone <repository-url>```

2. Se placer dans le répertoire du projet  
   ```cd <repository>```

3. Démarrer l’environnement Docker  
   ```docker compose up -d```

4. Exécuter les migrations Doctrine  
   ```docker compose exec web.les-joies-de php bin/console doctrine:migration:migrate```

Une fois les conteneurs démarrés, l’application est accessible via l’URL définie dans la configuration Docker.

## Création d’un compte administrateur

Un compte administrateur peut être créé via la commande suivante :

```docker compose exec web.les-joies-de php bin/console app:user:create```

## Gestion des inscriptions

- L’inscription est ouverte aux utilisateurs
- Les comptes sont inactifs par défaut
- Un administrateur doit activer manuellement chaque compte avant la connexion

## Hébergement

Le projet est conçu pour être auto-hébergé :

- Serveur avec Docker et Docker Compose
- Reverse proxy possible (Nginx, Traefik)
- Configuration adaptable selon l’environnement

## Stack technique

- PHP
- Symfony
- Docker / Docker Compose
- Base de données relationnelle (postgresql par défaut)

## Objectif du projet

Mettre à disposition une plateforme simple pour publier et centraliser des mèmes, GIFs et images autour d’un sujet
spécifique, avec une modération et une administration maîtrisées.

## Licence

Ce projet est sous licence MIT
