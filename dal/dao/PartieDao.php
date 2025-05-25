<?php

class PartieDao extends BaseDao
{
    private UtilisateurDao $utilisateurDao;
    private JeuDao $jeuDao;

    public function __construct(ConfigDao $config)
    {
        parent::__construct($config);
        $this->utilisateurDao = new UtilisateurDao($config);
        $this->jeuDao = new JeuDao($config);
    }

    public function selectAll(int $limite = 0): array
    {
        $connexion = $this->getConnexion();

        $sql = "SELECT * FROM partie ORDER BY date_creation DESC";
        if ($limite > 0)
        {
            $sql .= " LIMIT :limite";
        }

        $requete = $connexion->prepare($sql);
        if ($limite > 0)
        {
            $requete->bindValue(":limite", $limite, PDO::PARAM_INT);
        }

        $requete->execute();

        $parties = [];

        while ($enregistrement = $requete->fetch())
        {
            $partie = new Partie(
                new DateTime($enregistrement["date_creation"]),
                $enregistrement["j1_id"],
                $enregistrement["j2_id"],
                $enregistrement["j1_score"],
                $enregistrement["j2_score"],
                $enregistrement["jeu_id"],
                $enregistrement["id"]
            );

            $partie->setJoueur1($this->utilisateurDao->select($enregistrement["j1_id"]));
            $partie->setJoueur2($this->utilisateurDao->select($enregistrement["j2_id"]));
            $partie->setJeu($this->jeuDao->select($enregistrement["jeu_id"]));

            $parties[] = $partie;
        }

        return $parties;
    }


    public function selectParJoueur(int $joueurId): array
    {
        $sql = "SELECT * FROM partie WHERE j1_id = :id OR j2_id = :id ORDER BY date_creation DESC";
        $stmt = $this->getConnexion()->prepare($sql);
        $stmt->bindValue(":id", $joueurId, PDO::PARAM_INT);
        $stmt->execute();

        $parties = [];

        while ($enregistrement = $stmt->fetch())
        {
            $partie = new Partie(
                new DateTime($enregistrement["date_creation"]),
                $enregistrement["j1_id"],
                $enregistrement["j2_id"],
                $enregistrement["j1_score"],
                $enregistrement["j2_score"],
                $enregistrement["jeu_id"],
                $enregistrement["id"]
            );

            $partie->setJoueur1($this->utilisateurDao->select($enregistrement["j1_id"]));
            $partie->setJoueur2($this->utilisateurDao->select($enregistrement["j2_id"]));
            $partie->setJeu($this->jeuDao->select($enregistrement["jeu_id"]));

            $parties[] = $partie;
        }

        return $parties;
    }

    public function insert(Partie $partie): void
    {
        $sql = "INSERT INTO partie (date_creation, j1_id, j2_id, j1_score, j2_score, jeu_id)
            VALUES (:date_creation, :j1, :j2, :s1, :s2, :jeu)";

        $stmt = $this->getConnexion()->prepare($sql);
        $stmt->bindValue(":date_creation", $partie->getDateCreation()->format("Y-m-d H:i:s"));
        $stmt->bindValue(":j1", $partie->getJoueur1Id(), PDO::PARAM_INT);
        $stmt->bindValue(":j2", $partie->getJoueur2Id(), PDO::PARAM_INT);
        $stmt->bindValue(":s1", $partie->getScoreJoueur1(), PDO::PARAM_INT);
        $stmt->bindValue(":s2", $partie->getScoreJoueur2(), PDO::PARAM_INT);
        $stmt->bindValue(":jeu", $partie->getJeuId(), PDO::PARAM_INT);
        $stmt->execute();
    }

    public function delete(int $id): void
    {
        $sql = "DELETE FROM partie WHERE id = :id";
        $stmt = $this->getConnexion()->prepare($sql);
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
    }
}
