# php-gestion-jeux — « Point Final »

**FR** — Site web **PHP** de gestion de **parties de jeux** et de **classements** : comptes utilisateurs, enregistrement des parties, statistiques et classement global. Architecture en couches avec une **couche d'accès aux données (DAL/DAO)**.

**EN** — A **PHP** website for managing **game matches** and **leaderboards**: user accounts, match records, statistics and a global ranking. Layered architecture with a **data-access layer (DAL/DAO)**.

---

## Fonctionnalités / Features

- **FR**
  - Comptes utilisateurs : inscription, connexion, gestion du compte.
  - Enregistrement et suppression de parties, statistiques par joueur.
  - Classement global des joueurs.
  - Couche DAO (`dal/`) séparant l'accès à la base de données de la présentation.
  - Interface responsive (Bootstrap 5).
- **EN**
  - User accounts: sign-up, login, account management.
  - Recording and deleting matches, per-player statistics.
  - Global player leaderboard.
  - DAO layer (`dal/`) separating database access from presentation.
  - Responsive UI (Bootstrap 5).

## Stack

PHP · MySQL/MariaDB · Bootstrap 5.

## Configuration & lancement / Setup & run

**FR** : renseignez les identifiants de votre base de données dans `config.php`
(`NOM_BD`, `NOM_UTILISATEUR_BD`, `MDP_BD` — la valeur `VOTRE_MOT_DE_PASSE_BD` est un exemple),
puis servez le dossier avec un serveur PHP.

**EN**: set your database credentials in `config.php`
(`NOM_BD`, `NOM_UTILISATEUR_BD`, `MDP_BD` — the `VOTRE_MOT_DE_PASSE_BD` value is a placeholder),
then serve the folder with a PHP server.

```bash
php -S localhost:8000
#   → http://localhost:8000
```
