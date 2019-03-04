<?php
// Load models
require_once ROOT_DIR . '/src/models/Project.php';
require_once ROOT_DIR . '/src/models/Person.php';

/**
 * Visualises the outworked html string and data in the browser
 * The data: list of every project data, including persons in it.
 * @param  string  $listMode  'list'
 * @return string , containing all html tags and data required for visualisation in the browser
 */
function listAction($listMode = 'list')
{
    // Will be used to create the correct url address
    $_SESSION['listmode'] = $listMode;

    // Sets values - strings reprezenting required url addresses - for variables
    $listTemplate = ROOT_DIR . '/src/views/templates/crud/projects_' . $listMode . '.html.php';
    $baseTemplate = ROOT_DIR . '/src/views/templates/base.html.php';
    $trashConfirmTemplate = ROOT_DIR . '/src/views/templates/crud/trash_confirm_modal.html.php';

    // Function projectAll($unAttached, $orderBy) is described in Project.php
    $projects = projectAll(false, ['fields' => ['title ASC']]);

    if (isset($projects['fail'])) {
        $_SESSION['crud']['danger'][] = $projects['fail'];
        $projects = [];
    }

    // Here all formed html (type: string) is setted as a value of a certain variable
    // Function view() is described in Response.php
    $trashModal = view($trashConfirmTemplate);
    $list = view($listTemplate, ['projects' => $projects, 'modal' => $trashModal]);
    return view($baseTemplate, ['list' => $list]);
}

/**
 * Visualises the outworked html string and data in the browser.
 * The data: specified project's data, including persons.
 * @param  int  $projectId
 * @return string , containing all html tags and data required for visualisation in the browser
 */
function editAction($projectId)
{
    // Sets values - strings reprezenting required url addresses - for variables
    $formTemplate = ROOT_DIR . '/src/views/templates/crud/projects_edit_form.html.php';
    $baseTemplate = ROOT_DIR . '/src/views/templates/base.html.php';

    // Function projectById($personId) is described in Project.php
    $project = projectById($projectId);

    // In case of failure is redirected to another html page 
    if (isset($project['fail'])) {
        $_SESSION['crud']['danger'][] = $project['fail'];
        $project = [];
        return redirect(BASE_URL . '/projects' . '/' . $_SESSION['listmode']);
    }

    // In case of no data gives a warning message
    if (empty($project)) {
        require_once ROOT_DIR . '/src/controllers/HttpStatusController.php';
        return httpStatusAction(406);
    }

    // Function personAll() is described in Person.php
    $persons = personAll(true);
    if (isset($departments['fail'])) {
        $_SESSION['crud']['danger'][] = $departments['fail'];
        $departments = [];
    }

    // Function view() is described in Response.php
    $form = view(
        $formTemplate,
        [
            'project' => $project,
            'persons' => $persons,
        ]
    );
    return view($baseTemplate, ['form' => $form]);
}

/**
 * Deletes the data in db then uploads updated information
 * in the browser using url address
 * @return string url address 
 */
function trashAction()
{
    // Function projectTrash is described in Project.php
    $trashed = projectTrash();
    if (isset($trashed['fail'])) {
        $_SESSION['crud']['danger'][] = $trashed['fail'];
    }
    return redirect(BASE_URL . '/projects' . '/' . $_SESSION['listmode']);
}

/**
 * Visualises the outworked html string and data in the browser
 * The data: all projects and newly added project data
 * @return string , containing all html tags and data required for visualisation in the browser
 */
function addAction()
{
    // Sets values - strings reprezenting required url addresses - for variables
    $formTemplate = ROOT_DIR . '/src/views/templates/crud/projects_add_form.html.php';
    $baseTemplate = ROOT_DIR . '/src/views/templates/base.html.php';
    // Function personAll() is described in Person.php
    $persons = personAll(true);
    if (isset($persons['fail'])) {
        $_SESSION['crud']['danger'][] = $persons['fail'];
        $persons = [];
    }
    // Function view() is described in Response.php
    $form = view($formTemplate, ['persons' => $persons]);
    return view($baseTemplate, ['form' => $form]);
}

/**
 * Saves the newly entered/or updated project's data in db then uploads updated information
 * in the browser using url address
 * @return string url address 
 */
function saveAction()
{
    // Function sanitizeProjectInput() is described in Project.php
    // Function validateProjectInput() is described in Project.php
    $sanitized = sanitizeProjectInput();
    $validated = validateProjectInput();
    if (isset($validated['fail'])) {
        $_SESSION['crud']['warning'][] = $validated['fail'];
        return redirect(BASE_URL . '/projects' . '/' . $_SESSION['listmode']);
    }
    // Function projectSave() is described in Project.php
    $save = projectSave();
    if ($save['fail']) {
        $_SESSION['crud']['danger'][] = $save['fail'];
    }
    return redirect(BASE_URL . '/projects' . '/' . $_SESSION['listmode']);
}
