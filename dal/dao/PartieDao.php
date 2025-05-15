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
}
