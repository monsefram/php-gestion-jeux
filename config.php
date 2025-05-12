<?php
require_once "$_SERVER[DOCUMENT_ROOT]/autoloader.php";

const NOM_BD = "pointfinal";
const NOM_UTILISATEUR_BD = "etd";
const MDP_BD = "VOTRE_MOT_DE_PASSE_BD";

$config = new ConfigDao(NOM_BD, NOM_UTILISATEUR_BD, MDP_BD);