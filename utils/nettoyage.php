<?php
function nettoyer(string $valeur): string
{
    return htmlspecialchars(trim($valeur));
}
