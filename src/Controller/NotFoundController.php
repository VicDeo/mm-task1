<?php
declare(strict_types=1);

namespace Task1\Controller;

use Task1\Model\Http\Message\Response\Template;

class NotFoundController extends Controller
{
    public function index()
    {
        return new Template('404.php', statusCode: 404);
    }
}
