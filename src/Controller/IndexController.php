<?php
declare(strict_types=1);

namespace Task1\Controller;

use Task1\Model\Attribute\Route;
use Task1\Model\Http\Message\Response\Template;

class IndexController extends Controller
{
    #[Route(method: 'GET', path: '/', name: 'root')]
    public function index(): Template
    {
        return new Template('page.php');
    }
}
