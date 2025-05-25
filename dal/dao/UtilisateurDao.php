<?php

class UtilisateurDao extends BaseDao
{
    private RoleDao $roleDao;



    public function __construct(ConfigDao $config)
    {
        parent::__construct($config);
        $this->roleDao = new RoleDao($config);
    }

    public function select(int $id): ?Utilisateur
    {
        $connexion = $this->getConnexion();

        $requete = $connexion->prepare("SELECT * FROM utilisateur WHERE id=:id");
        $requete->bindValue(":id", $id);
        $requete->execute();

        $utilisateur = null;
        if ($enregistrement = $requete->fetch())
        {
            $utilisateur = $this->construireUtilisateur($enregistrement);
            $utilisateur->setRole($this->roleDao->select($utilisateur->getRoleId()));
        }

        return $utilisateur;
    }

    public function selectParNomUtilisateur(string $nomUtilisateur): ?Utilisateur
    {
        $connexion = $this->getConnexion();

        $requete = $connexion->prepare("SELECT * FROM utilisateur WHERE nom_utilisateur=:nom_utilisateur");
        $requete->bindValue(":nom_utilisateur", $nomUtilisateur);
        $requete->execute();

        $utilisateur = null;
        if ($enregistrement = $requete->fetch())
        {
            $utilisateur = $this->construireUtilisateur($enregistrement);
            $utilisateur->setRole($this->roleDao->select($utilisateur->getRoleId()));
        }

        return $utilisateur;
    }

    private function construireUtilisateur($enregistrement): ?Utilisateur
    {
        return new Utilisateur(
            $enregistrement['nom_utilisateur'],
            $enregistrement['prenom'],
            $enregistrement['nom'],
            $enregistrement['bio'],
            new DateTime($enregistrement['date_creation']),
            $enregistrement['role_id'],
            $enregistrement['url_avatar'],
            $enregistrement['hash'],
            $enregistrement['id']
        );
    }

    public function selectAll(): array
    {
        $connexion = $this->getConnexion();
        $requete = $connexion->prepare("SELECT * FROM utilisateur ORDER BY nom_utilisateur ASC");
        $requete->execute();

        $utilisateurs = [];

        while ($enregistrement = $requete->fetch())
        {
            $utilisateur = $this->construireUtilisateur($enregistrement);
            $utilisateur->setRole($this->roleDao->select($utilisateur->getRoleId()));
            $utilisateurs[] = $utilisateur;
        }

        return $utilisateurs;
    }

    public function insert(Utilisateur $utilisateur): void
    {
        $connexion = $this->getConnexion();

        $requete = $connexion->prepare("
        INSERT INTO utilisateur (nom_utilisateur, prenom, nom, bio, date_creation, role_id, url_avatar, hash)
        VALUES (:nom_utilisateur, :prenom, :nom, :bio, :date_creation, :role_id, :url_avatar, :hash)
    ");

        $requete->bindValue(":nom_utilisateur", $utilisateur->getNomUtilisateur());
        $requete->bindValue(":prenom", $utilisateur->getPrenom());
        $requete->bindValue(":nom", $utilisateur->getNom());
        $requete->bindValue(":bio", $utilisateur->getBio());
        $requete->bindValue(":date_creation", $utilisateur->getDateCreation()->format("Y-m-d H:i:s"));
        $requete->bindValue(":role_id", $utilisateur->getRoleId());
        $requete->bindValue(":url_avatar", $utilisateur->getUrlAvatar());
        $requete->bindValue(":hash", $utilisateur->getHash());

        $requete->execute();

        // Mise à jour de l'ID après insertion
        $utilisateur->setId($connexion->lastInsertId());
    }


    public function update(Utilisateur $u): void
    {
        $sql = "UPDATE utilisateur 
            SET prenom = :prenom, nom = :nom, bio = :bio, url_avatar = :url, hash = :hash 
            WHERE id = :id";

        $stmt = $this->getConnexion()->prepare($sql);
        $stmt->bindValue(":prenom", $u->getPrenom());
        $stmt->bindValue(":nom", $u->getNom());
        $stmt->bindValue(":bio", $u->getBio());
        $stmt->bindValue(":url", $u->getUrlAvatar());
        $stmt->bindValue(":hash", $u->getHash());
        $stmt->bindValue(":id", $u->getId());
        $stmt->execute();
    }

    public function selectAllParRole(int $roleId): array
    {
        $sql = "SELECT * FROM utilisateur WHERE role_id = :roleId";
        $stmt = $this->getConnexion()->prepare($sql);
        $stmt->bindValue(":roleId", $roleId, PDO::PARAM_INT);
        $stmt->execute();

        $utilisateurs = [];

        while ($ligne = $stmt->fetch())
        {
            $utilisateur = new Utilisateur(
                $ligne["nom_utilisateur"],
                $ligne["prenom"],
                $ligne["nom"],
                $ligne["bio"],
                new DateTime($ligne["date_creation"]),
                $ligne["role_id"], // on passe l'ID, pas l'objet
                $ligne["url_avatar"],
                $ligne["hash"],
                $ligne["id"]
            );

            // si tu veux, tu peux attacher l'objet Role ici :
            $utilisateur->setRole($this->roleDao->select($ligne["role_id"]));

            $utilisateurs[] = $utilisateur;
        }

        return $utilisateurs;
    }
}
