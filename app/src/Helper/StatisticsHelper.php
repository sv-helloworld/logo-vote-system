<?php
namespace App\Helper;

final class StatisticsHelper
{
    private $db;

    public function __construct(\MysqliDb $db)
    {
        $this->db = $db;
    }

    /**
     * Returns the vote statistics
     *
     * @return array The vote statistics
     */
    public function getVoteStatistics()
    {
        // Set up vote statistics
        $statistics = array();

        // Get total vote count
        $total = $this->db->getValue('vote', 'count(id)');
        $statistics['total'] = $total;

        // Get statistics from database
        $this->db->groupBy('logo');
        $result = $this->db->get('vote', null, 'logo, count(id) AS count');

        foreach($result as $row) {
            $statistics['results'][$row['logo']] = array(
                'logo' => $row['logo'],
                'count' => $row['count']
            );

            $statistics['chart']['labels'][] = 'Logo ' . $row['logo'];
            $statistics['chart']['data'][] = $row['count'];
        }

        return $statistics;
    }
}