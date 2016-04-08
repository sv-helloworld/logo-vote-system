<?php
namespace App\Action;

use App\Helper\StatisticsHelper;
use Psr\Log\LoggerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

final class VoteAction
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
        // Check request data
        if(isset($args['id'])) {

            try {
                $this->vote($args['id']);
            }
            catch (\Exception $e) {
                $this->logger->error('Could not add vote for ID ' . $args['id']);
            }
        }

        // Check if email address is set
        if(isset($args['email']) && !empty($args['email'])) {
            $voteId = $this->db->getInsertId();

            try {
                $this->registerEmailAddress($voteId, $args['email']);
            }
            catch (\Exception $e) {
                $this->logger->error('Could not register email address \'' . $args['email'] . '\'');
            }
        }

        // Get latest vote data
        $data = $this->statisticsHelper->getVoteStatistics();
        $response->withJson($data);

        return $response;
    }

    /**
     * Votes the given logo ID
     *
     * @param null $id The ID of the logo
     * @return bool True if the vote was added, false if not
     * @throws \Exception If the ID is not numeric
     */
    private function vote($id = null)
    {
        if($id == null || !is_numeric($id)) {
            throw new \Exception('The ID must be numeric.');
        }

        // Add vote
        $vote = $this->db->insert('vote', array(
            'logo' => $id,
            'timestamp' => time()
        ));

        return $vote;
    }

    /**
     * Registers the given email address
     *
     * @param $voteId int The vote id where the email address belongs to
     * @param $email string The email address to register
     * @return bool Returns true if the email address was registered, false when not
     * @throws \Exception If the Vote ID or email address is empty or the email address is not valid
     */
    private function registerEmailAddress($voteId, $email)
    {
        // Check if vote id and email is set
        if(!$voteId || !$email) {
            throw new \Exception('Vote ID and email address can not be empty.');
        }

        // Validate email and subscribe if valid
        if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $subscribe = $this->db->insert('email', array(
                'vote_id' => $voteId,
                'email' => $email
            ));

            return $subscribe;
        }
        else {
            throw new \Exception('"' .$email . '" is not a valid email address.');
        }
    }
}
