🌱 AgriCompta – Système de Gestion Agricole (Laravel + Oracle)

📌 Description du projet :

AgriCompta est une application web développée avec Laravel et une base de données Oracle.
Elle permet de gérer l’ensemble du cycle de production agricole :

🔹Gestion des produits

🔹Gestion des variétés

🔹Suivi des récoltes

🔹Gestion des ventes

🔹Gestion des pertes

Calcul automatique des statistiques et indicateurs

Le projet met l’accent sur :

🔹la cohérence des données

🔹l’utilisation de PL/SQL (fonctions, triggers)

🔹le respect des contraintes d’intégrité

🔹une architecture claire et normalisée

🏗️ Architecture du projet

* Base de données (Oracle)

La base est organisée autour des entités suivantes :

🔹PRODUIT

🔹VARIETE

🔹RECOLTE

🔹VENTE

🔹PERTE

🔗 Relations principales

🔹Un produit possède plusieurs variétés

🔹Une variété peut avoir plusieurs récoltes

🔹Une récolte peut générer plusieurs ventes

🔹Une variété peut subir plusieurs pertes

🔹Cette modélisation respecte le cycle réel de production agricole :

       Produit → Variété → Récolte → Vente

⚙️ Logique métier implémentée
🔹 Triggers Oracle

 Les triggers permettent :

🔹Mise en majuscule automatique des noms (normalisation)

🔹Interdiction des dates futures

🔹Vérification des quantités positives

🔹Contrôle de cohérence des données

Exemple :

Empêcher une date de récolte future

Empêcher une quantité négative

Fonctions PL/SQL

Des fonctions ont été développées pour :

Calculer le total des ventes par période

Calculer le total des récoltes

Calculer les statistiques du dashboard

Les calculs sont réalisés côté base de données, garantissant précision et performance.

📊 Dashboard

Le tableau de bord affiche :

🔹Total des ventes

🔹Total des récoltes

🔹Statistiques par période

Indicateurs globaux

Les invendus sont calculés dynamiquement :

Invendus = Quantité récoltée - Quantité vendue

Aucune redondance n’est stockée afin de garantir la cohérence.

🛠️ Technologies utilisées

🔹Laravel 12

🔹PHP 8.2

🔹Oracle Database

🔹PL/SQL

🔹DataGrip (gestion et inspection de la base)

🎯 Objectifs pédagogiques

Ce projet a permis de :

🔹Concevoir un modèle relationnel cohérent

🔹Implémenter des contraintes d’intégrité avancées

🔹Utiliser des triggers et fonctions Oracle

🔹Connecter Laravel à Oracle

🔹Séparer la logique métier de la couche présentation
