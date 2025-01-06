<?php
use Task1\App;

/* handy shortcuts that are used in templates */
function request_uri(): string
{
    return App::getInstance()->getRequest()->getUri()->getPath();
}