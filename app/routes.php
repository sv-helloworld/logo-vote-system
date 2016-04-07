<?php
// Routes

$app->get('/', App\Action\IndexAction::class)
    ->setName('homepage');
