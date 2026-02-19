# SportStats Auth Service - R4.01

Module d'authentification et de sécurisation pour l'écosystème SportStats. Ce micro-service gère l'identité des utilisateurs (coachs, administrateurs) et sécurise les échanges avec l'API Back-end.

## 🔐 Fonctionnalités de Sécurité
* **Gestion des Utilisateurs :** Inscription, connexion et déconnexion.
* **Authentification JWT :** Génération de tokens sécurisés (JSON Web Tokens) pour les sessions.
* **Protection des Routes :** Middleware de vérification pour restreindre l'accès aux données sensibles.
* **Hashage des Mots de Passe :** Utilisation d'algorithmes de hashage (ex: bcrypt) pour la protection des données.

## 🏗️ Architecture Inter-services
Ce service agit comme une passerelle de sécurité :
1. L'utilisateur s'authentifie via ce module.
2. Un **Token** est délivré en cas de succès.
3. Ce token doit être inclus dans les headers des requêtes vers le Service Back-end (https://github.com/ALFA47392/R4.01_Back.git).

## 🛠️ Stack Technique
* **Runtime :** Node.js / Express (ou PHP/Symfony selon votre implémentation).
* **Sécurité :** JWT (JsonWebToken), Bcrypt.
* **Base de données :** Stockage des identifiants utilisateurs.

## 📂 Organisation du Code
* `/routes` : Endpoints `/login`, `/register`, `/logout`.
* `/middleware` : Logique de vérification du token (`isAuth`).
* `/services` : Logique d'émission et de rafraîchissement des clés de sécurité.
