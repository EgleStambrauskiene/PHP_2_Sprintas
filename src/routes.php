<?php
// ORIGINALUS
$routes['GET'][] = [
    '_name' => 'front',
    '_pattern' => '^/$',
    '_controller' => 'PersonsController',
    '_action' => 'listAction',
    '_args' => false,
];

// Parasytas naujai
$routes['GET'][] = [
    '_name' => 'front',
    '_pattern' => '^/$',
    '_controller' => 'ProjectsController',
    '_action' => 'listAction',
    '_args' => false,
];

// Parasytas naujai
$routes['GET'][] = [
    '_name' => 'front',
    '_pattern' => '^/$',
    '_controller' => 'DepartmentsController',
    '_action' => 'listAction',
    '_args' => false,
];

// ORIGINALUS
$routes['GET'][] = [
    '_name' => 'persons',
    '_pattern' => '^/persons(/|$)',
    '_controller' => 'PersonsController',
    '_action' => 'listAction',
    '_args' => false,
];

// Parasytas naujai
$routes['GET'][] = [
    '_name' => 'projects',
    '_pattern' => '^/projects(/|$)',
    '_controller' => 'ProjectsController',
    '_action' => 'listAction',
    '_args' => false,
];

// Parasytas naujai
$routes['GET'][] = [
    '_name' => 'departments',
    '_pattern' => '^/departments(/|$)',
    '_controller' => 'DepartmentsController',
    '_action' => 'listAction',
    '_args' => false,
];

// ORIGINALUS
$routes['GET'][] = [
    '_name' => 'persons_list',
    '_pattern' => '^/persons/list(/|$)',
    '_controller' => 'PersonsController',
    '_action' => 'listAction',
    '_args' => 2,
];

// Parasytas naujai
$routes['GET'][] = [
    '_name' => 'projects_list',
    '_pattern' => '^/projects/list(/|$)',
    '_controller' => 'ProjectsController',
    '_action' => 'listAction',
    '_args' => 2,
];

// Parasytas naujai
$routes['GET'][] = [
    '_name' => 'departments_list',
    '_pattern' => '^/departments/list(/|$)',
    '_controller' => 'DepartmentsController',
    '_action' => 'listAction',
    '_args' => 2,
];

// ORIGINALUS
$routes['GET'][] = [
    '_name' => 'persons_grid',
    '_pattern' => '^/persons/grid(/|$)',
    '_controller' => 'PersonsController',
    '_action' => 'listAction',
    '_args' => 2,
];

// Parasytas naujai
$routes['GET'][] = [
    '_name' => 'projects_grid',
    '_pattern' => '^/projects/grid(/|$)',
    '_controller' => 'ProjectsController',
    '_action' => 'listAction',
    '_args' => 2,
];

// Parasytas naujai
$routes['GET'][] = [
    '_name' => 'departments_grid',
    '_pattern' => '^/departments/grid(/|$)',
    '_controller' => 'DepartmentsController',
    '_action' => 'listAction',
    '_args' => 2,
];

// ORIGINALUS
$routes['GET'][] = [
    '_name' => 'persons_add',
    '_pattern' => '^/persons/add(/|$)',
    '_controller' => 'PersonsController',
    '_action' => 'addAction',
    '_args' => false
];

// Parasytas naujai
$routes['GET'][] = [
    '_name' => 'projects_add',
    '_pattern' => '^/projects/add(/|$)',
    '_controller' => 'ProjectsController',
    '_action' => 'addAction',
    '_args' => false
];

// Parasytas naujai
$routes['GET'][] = [
    '_name' => 'departments_add',
    '_pattern' => '^/departments/add(/|$)',
    '_controller' => 'DepartmentsController',
    '_action' => 'addAction',
    '_args' => false
];

// ORIGINALUS
$routes['GET'][] = [
    '_name' => 'persons_edit',
    '_pattern' => '^/persons/edit/(\d+)(/|$)',
    '_controller' => 'PersonsController',
    '_action' => 'editAction',
    '_args' => 3,
];

// Parasytas naujai
$routes['GET'][] = [
    '_name' => 'projects_edit',
    '_pattern' => '^/projects/edit/(\d+)(/|$)',
    '_controller' => 'ProjectsController',
    '_action' => 'editAction',
    '_args' => 3,
];

// Parasytas naujai
$routes['GET'][] = [
    '_name' => 'departments_edit',
    '_pattern' => '^/departments/edit/(\d+)(/|$)',
    '_controller' => 'DepartmentsController',
    '_action' => 'editAction',
    '_args' => 3,
];

// ORIGINALUS
$routes['POST'][] = [
    '_name' => 'persons_save',
    '_pattern' => '^/persons/save(/|$)',
    '_controller' => 'PersonsController',
    '_action' => 'saveAction',
    '_args' => false,
];

// Parasytas naujai
$routes['POST'][] = [
    '_name' => 'projects_save',
    '_pattern' => '^/projects/save(/|$)',
    '_controller' => 'ProjectsController',
    '_action' => 'saveAction',
    '_args' => false,
];

// Parasytas naujai
$routes['POST'][] = [
    '_name' => 'departments_save',
    '_pattern' => '^/departments/save(/|$)',
    '_controller' => 'DepartmentsController',
    '_action' => 'saveAction',
    '_args' => false,
];

// ORIGINALUS
$routes['POST'][] = [
    '_name' => 'persons_trash',
    '_pattern' => '^/persons/trash(/|$)',
    '_controller' => 'PersonsController',
    '_action' => 'trashAction',
    '_args' => false,
];

// Parasytas naujai
$routes['POST'][] = [
    '_name' => 'projects_trash',
    '_pattern' => '^/projects/trash(/|$)',
    '_controller' => 'ProjectsController',
    '_action' => 'trashAction',
    '_args' => false,
];

// Parasytas naujai
$routes['POST'][] = [
    '_name' => 'departments_trash',
    '_pattern' => '^/departments/trash(/|$)',
    '_controller' => 'DepartmentsController',
    '_action' => 'trashAction',
    '_args' => false,
];
