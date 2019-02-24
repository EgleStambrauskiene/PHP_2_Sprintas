<?php
$routes['GET'][] = [
    '_name' => 'front',
    '_pattern' => '^/$',
    '_controller' => 'PersonsController',
    '_action' => 'listAction',
    '_args' => false,
];

$routes['GET'][] = [
    '_name' => 'persons',
    '_pattern' => '^/persons(/|$)',
    '_controller' => 'PersonsController',
    '_action' => 'listAction',
    '_args' => false,
];

$routes['GET'][] = [
    '_name' => 'persons_list',
    '_pattern' => '^/persons/list(/|$)',
    '_controller' => 'PersonsController',
    '_action' => 'listAction',
    '_args' => 2,
];

$routes['GET'][] = [
    '_name' => 'persons_grid',
    '_pattern' => '^/persons/grid(/|$)',
    '_controller' => 'PersonsController',
    '_action' => 'listAction',
    '_args' => 2,
];

$routes['GET'][] = [
    '_name' => 'persons_add',
    '_pattern' => '^/persons/add(/|$)',
    '_controller' => 'PersonsController',
    '_action' => 'addAction',
    '_args' => false
];

$routes['GET'][] = [
    '_name' => 'persons_edit',
    '_pattern' => '^/persons/edit/(\d+)(/|$)',
    '_controller' => 'PersonsController',
    '_action' => 'editAction',
    '_args' => 3,
];

$routes['POST'][] = [
    '_name' => 'persons_save',
    '_pattern' => '^/persons/save(/|$)',
    '_controller' => 'PersonsController',
    '_action' => 'saveAction',
    '_args' => false,
];

$routes['POST'][] = [
    '_name' => 'persons_trash',
    '_pattern' => '^/persons/trash(/|$)',
    '_controller' => 'PersonsController',
    '_action' => 'trashAction',
    '_args' => false,
];
