<?php
// Routes

$app->get('/', App\Action\IndexAction::class)
    ->setName('index');

$app->get('/vote/{id:[0-9]+}[/{email}]', App\Action\VoteAction::class)
    ->setName('vote');

$app->get('/vote/statistics', App\Action\StatisticsAction::class)
    ->setName('statistics');