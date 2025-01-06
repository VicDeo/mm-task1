<?php
use Task1\App;

/* handy shortcuts that are used in templates */
function link_to_route(string $name): string
{
    $router = App::getInstance()->getRouter();
    $baseUrl = (string) App::getInstance()
        ->getConfig()
        ->getValue('BASE_URL');
    return "{$baseUrl}{$router->getPathByName($name)}";
}

function request_uri(): string
{
    return App::getInstance()->getRequest()->getUri()->getPath();
}