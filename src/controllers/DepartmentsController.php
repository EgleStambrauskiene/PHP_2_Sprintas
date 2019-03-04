<?php
// Load models
require_once ROOT_DIR . '/src/models/Person.php';
require_once ROOT_DIR . '/src/models/Department.php';

/**
 * Visualises the outworked html string and data in the browser
 * The data: list of every department data, including persons.
 * @param  string  $listMode  'list'
 * @return string , containing all html tags and data required for visualisation in the browser
 */
function listAction($listMode = 'list')
{
    // Will be used to create the correct url address
    $_SESSION['listmode'] = $listMode;

    // Sets values - strings reprezenting required url addresses - for variables
    $listTemplate = ROOT_DIR . '/src/views/templates/crud/departments_' . $listMode . '.html.php';
    $baseTemplate = ROOT_DIR . '/src/views/templates/base.html.php';
    $trashConfirmTemplate = ROOT_DIR . '/src/views/templates/crud/trash_confirm_modal.html.php';

    // Function dapartmentAll($orderBy) is described in Department.php
    $departments = departmentAll(['fields' => ['title ASC']]);

    if (isset($departments['fail'])) {
        $_SESSION['crud']['danger'][] = $departments['fail'];
        $departments = [];
    }

    // Function personSelected($unAttached) is described in Person.php
    $persons = personSelected(true);
    if (isset($persons['fail'])) {
        $_SESSION['crud']['danger'][] = $persons['fail'];
        $persons = [];
    }

    // Here all formed html (type: string) is setted as a value of a certain variable
    // Function view() is described in Response.php
    $trashModal = view($trashConfirmTemplate);
    $list = view($listTemplate, ['departments' => $departments, 'modal' => $trashModal, 'persons' => $persons]);
    return view($baseTemplate, ['list' => $list]);
}

/**
 * Visualises the outworked html string and data in the browser
 * The data: specified department's data.
 * @param  int  $departmentId
 * @return string , containing all html tags and data required for visualisation in the browser
 */
function editAction($departmentId)
{
    // Sets values - strings reprezenting required url addresses - for variables
    $formTemplate = ROOT_DIR . '/src/views/templates/crud/departments_edit_form.html.php';
    $baseTemplate = ROOT_DIR . '/src/views/templates/base.html.php';

    // Function departmentById($departmentId) is described in Department.php
    $department = departmentById($departmentId);

    // In case of failure is redirected to another html page 
    if (isset($department['fail'])) {
        $_SESSION['crud']['danger'][] = $department['fail'];
        $department = [];
        return redirect(BASE_URL . '/departments' . '/' . $_SESSION['listmode']);
    }

    // In case of no data gives a warning message
    if (empty($department)) {
        require_once ROOT_DIR . '/src/controllers/HttpStatusController.php';
        return httpStatusAction(406);
    }

    // Function personSelected(true) is described in Person.php
    $persons = personSelected(true);
    if (isset($persons['fail'])) {
        $_SESSION['crud']['danger'][] = $persons['fail'];
        $persons = [];
    }

    // Function view() is described in Response.php
    $form = view(
        $formTemplate,
        [
            'department' => $department,
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
    // Function departmentTrash is described in Department.php
    $trashed = departmentTrash();
    if (isset($trashed['fail'])) {
        $_SESSION['crud']['danger'][] = $trashed['fail'];
    }
    return redirect(BASE_URL . '/departments' . '/' . $_SESSION['listmode']);
}

/**
 * Visualises the outworked html string and data in the browser
 * The data: all departments and newly added department data
 * @return string , containing all html tags and data required for visualisation in the browser
 */
function addAction()
{
    // Sets values - strings reprezenting required url addresses - for variables
    $formTemplate = ROOT_DIR . '/src/views/templates/crud/departments_add_form.html.php';
    $baseTemplate = ROOT_DIR . '/src/views/templates/base.html.php';
    // Function personSelected() is described in Person.php
    $persons = personSelected(true);
    if (isset($persons['fail'])) {
        $_SESSION['crud']['danger'][] = $persons['fail'];
        $persons = [];
    }
    // var_dump($persons);
    
   // Function view() is described in Response.php
    $form = view($formTemplate, ['persons' => $persons,]);
    return view($baseTemplate, ['form' => $form]);
}

/**
 * Saves the newly entered/or updated department's data in db then uploads updated information
 * in the browser using url address
 * @return string url address 
 */
function saveAction()
{
    // Function sanitizePersonInput() is described in Person.php
    // Function validatePersonInput() is described in Person.php
    $sanitized = sanitizeDepartmentInput();
    $validated = validateDepartmentInput();
    if (isset($validated['fail'])) {
        $_SESSION['crud']['warning'][] = $validated['fail'];
        return redirect(BASE_URL . '/departments' . '/' . $_SESSION['listmode']);
    }
    // Function departmentSave() is described in Department.php
    $save = departmentSave();
    if ($save['fail']) {
        $_SESSION['crud']['danger'][] = $save['fail'];
    }
    return redirect(BASE_URL . '/departments' . '/' . $_SESSION['listmode']);
}
