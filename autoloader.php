<?php
register_autoloaders();

function register_autoloaders()
{
    spl_autoload_register('autoload_dao');
    spl_autoload_register('autoload_modeles');
}

function autoload_dao($class)
{
    $fichier = __DIR__ . "/dal/dao/".$class.".php";
    if (is_readable($fichier))
    {
        require_once $fichier;
    }
}

function autoload_modeles($class)
{
    $fichier = __DIR__ . "/dal/modeles/".$class.".php";
    if (is_readable($fichier))
    {
        require_once $fichier;
    }
}
