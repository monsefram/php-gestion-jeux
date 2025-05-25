<?php
require_once "config.php";
require_once "session.php";
require_once "dal/dao/PartieDao.php";

require_once "session.php";

$utilisateur = getUtilisateurConnecte();

if (!$utilisateur) {
    header("Location: connexion.phtml");
    exit;
}
if (!$utilisateur || $utilisateur->getRole()->getNom() !== "Administrateur") {
    header("Location: index.phtml");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["id_partie"])) {
    $id = intval($_POST["id_partie"]);

    $dao = new PartieDao($config);
    $dao->delete($id);
}

header("Location: parties.phtml");
exit;
