<?php
namespace App\Action;

use App\Helper\StatisticsHelper;
use Psr\Log\LoggerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

final class StatisticsAction
{
    private $db;
    private $logger;
    private $statisticsHelper;

    public function __construct(\MysqliDb $db, LoggerInterface $logger, StatisticsHelper $statisticsHelper)
    {
        $this->db = $db;
        $this->logger = $logger;
        $this->statisticsHelper = $statisticsHelper;
    }

    public function __invoke(Request $request, Response $response, $args)
    {
        // Get latest vote data
        $data = $this->statisticsHelper->getVoteStatistics();
        $response->withJson($data);

        return $response;
    }
}
