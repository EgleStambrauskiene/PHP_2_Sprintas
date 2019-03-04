<?php
// Load models
require_once ROOT_DIR . '/src/models/Project.php';
require_once ROOT_DIR . '/src/models/Person.php';
require_once ROOT_DIR . '/src/models/Department.php';

/**
 * Visualises the outworked html string and data in the browser
 * The data: list of every person data, including projects.
 * @param  string  $listMode  'list'
 * @return string , containing all html tags and data required for visualisation in the browser
 */
function listAction($listMode = 'list')
{
    // Will be used to create the correct url address
    $_SESSION['listmode'] = $listMode;

    // Sets values - strings reprezenting required url addresses - for variables
    $listTemplate = ROOT_DIR . '/src/views/templates/crud/persons_' . $listMode . '.html.php';
    $baseTemplate = ROOT_DIR . '/src/views/templates/base.html.php';
    $trashConfirmTemplate = ROOT_DIR . '/src/views/templates/crud/trash_confirm_modal.html.php';

    // Function personAll($unAttached, $orderBy) is described in Person.php
    $persons = personAll(false, ['fields' => ['lastname ASC']]);

    if (isset($persons['fail'])) {
        $_SESSION['crud']['danger'][] = $persons['fail'];
        $persons = [];
    }

    // Here all formed html (type: string) is setted as a value of a certain variable
    // Function view() is described in Response.php
    $trashModal = view($trashConfirmTemplate);
    $list = view($listTemplate, ['persons' => $persons, 'modal' => $trashModal]);
    return view($baseTemplate, ['list' => $list]);
}

/**
 * Visualises the outworked html string and data in the browser
 * The data: specified person's data, including person's projects.
 * @param  int  $personId
 * @return string , containing all html tags and data required for visualisation in the browser
 */
function editAction($personId)
{
    // Sets values - strings reprezenting required url addresses - for variables
    $formTemplate = ROOT_DIR . '/src/views/templates/crud/persons_edit_form.html.php';
    $baseTemplate = ROOT_DIR . '/src/views/templates/base.html.php';

    // Function personById($personId) is described in Person.php
    $person = personById($personId);

    // In case of failure is redirected to another html page 
    if (isset($person['fail'])) {
        $_SESSION['crud']['danger'][] = $person['fail'];
        $person = [];
        return redirect(BASE_URL . '/persons' . '/' . $_SESSION['listmode']);
    }

    // In case of no data gives a warning message
    if (empty($person)) {
        require_once ROOT_DIR . '/src/controllers/HttpStatusController.php';
        return httpStatusAction(406);
    }

    // Function departmentAll() is described in Department.php
    $departments = departmentAll();
    if (isset($departments['fail'])) {
        $_SESSION['crud']['danger'][] = $departments['fail'];
        $departments = [];
    }

    // Function projectAll($unAttached, $orderBy) is described in Project.php
    $projects = projectAll(true, ['fields' => ['title ASC']]);
    if (isset($projects['fail'])) {
        $_SESSION['crud']['danger'][] = $projects['fail'];
        $projects = [];
    }
    
    // Function view() is described in Response.php
    $form = view(
        $formTemplate,
        [
            'person' => $person,
            'projects' => $projects,
            'departments' => $departments,
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
    // Function personTrash is described in Person.php
    $trashed = personTrash();
    if (isset($trashed['fail'])) {
        $_SESSION['crud']['danger'][] = $trashed['fail'];
    }
    return redirect(BASE_URL . '/persons' . '/' . $_SESSION['listmode']);
}

/**
 * Visualises the outworked html string and data in the browser
 * The data: all persons and newly added person data
 * @return string , containing all html tags and data required for visualisation in the browser
 */
function addAction()
{
    // Sets values - strings reprezenting required url addresses - for variables
    $formTemplate = ROOT_DIR . '/src/views/templates/crud/persons_add_form.html.php';
    $baseTemplate = ROOT_DIR . '/src/views/templates/base.html.php';
    // Function departmentAll() is described in Department.php
    $departments = departmentAll(true);
    if (isset($departments['fail'])) {
        $_SESSION['crud']['danger'][] = $departments['fail'];
        $departments = [];
    }
    // Function projectAll($unAttached, $orderBy) is described in Project.php
    $projects = projectAll(true);
    if (isset($departments['fail'])) {
        $_SESSION['crud']['danger'][] = $projects['fail'];
        $projects = [];
    }
    // Function view() is described in Response.php
    $form = view($formTemplate, ['projects' => $projects, 'departments' => $departments,]);
    return view($baseTemplate, ['form' => $form]);
}

/**
 * Saves the newly entered/or updated person's data in db then uploads updated information
 * in the browser using url address
 * @return string url address 
 */
function saveAction()
{
    // Function sanitizePersonInput() is described in Person.php
    // Function validatePersonInput() is described in Person.php
    $sanitized = sanitizePersonInput();
    $validated = validatePersonInput();
    if (isset($validated['fail'])) {
        $_SESSION['crud']['warning'][] = $validated['fail'];
        return redirect(BASE_URL . '/persons' . '/' . $_SESSION['listmode']);
    }
    // Function personSave() is described in Person.php
    $save = personSave();
    if ($save['fail']) {
        $_SESSION['crud']['danger'][] = $save['fail'];
    }
    return redirect(BASE_URL . '/persons' . '/' . $_SESSION['listmode']);
}
