<?php
require_once "session.php";

detruireSession();
header("Location: connexion.phtml");
exit;
