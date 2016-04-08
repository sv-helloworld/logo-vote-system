<?php
// DIC configuration

$container = $app->getContainer();

// -----------------------------------------------------------------------------
// Service providers
// -----------------------------------------------------------------------------

// Twig
$container['view'] = function ($c) {
    $settings = $c->get('settings');
    $view = new Slim\Views\Twig($settings['view']['template_path'], $settings['view']['twig']);

    // Add extensions
    $view->addExtension(new Slim\Views\TwigExtension($c->get('router'), $c->get('request')->getUri()));
    $view->addExtension(new Twig_Extension_Debug());

    return $view;
};

// Database
$container['database'] = function ($c) {
    return new MysqliDb(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);
};

// Flash messages
$container['flash'] = function ($c) {
    return new Slim\Flash\Messages;
};

// -----------------------------------------------------------------------------
// Service factories
// -----------------------------------------------------------------------------

// monolog
$container['logger'] = function ($c) {
    $settings = $c->get('settings');
    $logger = new Monolog\Logger($settings['logger']['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['logger']['path'], Monolog\Logger::DEBUG));
    return $logger;
};

// -----------------------------------------------------------------------------
// Helper factories
// -----------------------------------------------------------------------------
$container[App\Helper\StatisticsHelper::class] = function ($c) {
    return new App\Helper\StatisticsHelper($c->get('database'));
};

// -----------------------------------------------------------------------------
// Action factories
// -----------------------------------------------------------------------------

$container[App\Action\IndexAction::class] = function ($c) {
    return new App\Action\IndexAction($c->get('view'), $c->get('database'), $c->get('logger'));
};

$container[App\Action\VoteAction::class] = function ($c) {
    return new App\Action\VoteAction($c->get('database'), $c->get('logger'), $c->get(App\Helper\StatisticsHelper::class));
};

$container[App\Action\StatisticsAction::class] = function ($c) {
    return new App\Action\StatisticsAction($c->get('database'), $c->get('logger'), $c->get(App\Helper\StatisticsHelper::class));
};