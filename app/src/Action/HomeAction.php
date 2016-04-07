<?php
namespace App\Action;

use Slim\Views\Twig;
use Psr\Log\LoggerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

final class HomeAction
{
    private $view;
    private $db;
    private $logger;

    public function __construct(Twig $view, \MysqliDb $db, LoggerInterface $logger)
    {
        $this->view = $view;
        $this->db = $db;
        $this->logger = $logger;
    }

    public function __invoke(Request $request, Response $response, $args)
    {
        $this->logger->info("Home page action dispatched");
        
        $this->view->render($response, 'home.twig');
        return $response;
    }
}
