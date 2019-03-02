<?php
// Load models
// require_once ROOT_DIR . '/src/models/Project.php';
require_once ROOT_DIR . '/src/models/Person.php';
require_once ROOT_DIR . '/src/models/Department.php';

function listAction($listMode = 'list')
{
    $_SESSION['listmode'] = $listMode;

    $listTemplate = ROOT_DIR . '/src/views/templates/crud/departments_' . $listMode . '.html.php';
    $baseTemplate = ROOT_DIR . '/src/views/templates/base.html.php';
    $trashConfirmTemplate = ROOT_DIR . '/src/views/templates/crud/trash_confirm_modal.html.php';

    $departments = departmentAll(false, ['fields' => ['title ASC']]);

    if (isset($departments['fail'])) {
        $_SESSION['crud']['danger'][] = $departments['fail'];
        $departments = [];
    }

    // Naujas
    $persons = personSelected(true);
    if (isset($persons['fail'])) {
        $_SESSION['crud']['danger'][] = $persons['fail'];
        $persons = [];
    }
    //

    $trashModal = view($trashConfirmTemplate);
    $list = view($listTemplate, ['departments' => $departments, 'modal' => $trashModal, 'persons' => $persons]);
    return view($baseTemplate, ['list' => $list]);
}

function editAction($departmentId)
{
    $formTemplate = ROOT_DIR . '/src/views/templates/crud/departments_edit_form.html.php';
    $baseTemplate = ROOT_DIR . '/src/views/templates/base.html.php';

    $department = departmentById($departmentId);

    if (isset($department['fail'])) {
        $_SESSION['crud']['danger'][] = $department['fail'];
        $department = [];
        return redirect(BASE_URL . '/departments' . '/' . $_SESSION['listmode']);
    }

    if (empty($department)) {
        require_once ROOT_DIR . '/src/controllers/HttpStatusController.php';
        return httpStatusAction(406);
    }

    // Naujas
    $persons = personSelected(true);
    if (isset($persons['fail'])) {
        $_SESSION['crud']['danger'][] = $persons['fail'];
        $persons = [];
    }
    //
    $form = view(
        $formTemplate,
        [
            'department' => $department,
            'persons' => $persons,
        ]
    );
    return view($baseTemplate, ['form' => $form]);
}

function trashAction()
{
    $trashed = departmentTrash();
    if (isset($trashed['fail'])) {
        $_SESSION['crud']['danger'][] = $trashed['fail'];
    }
    return redirect(BASE_URL . '/departments' . '/' . $_SESSION['listmode']);
}

function addAction()
{
    $formTemplate = ROOT_DIR . '/src/views/templates/crud/departments_add_form.html.php';
    $baseTemplate = ROOT_DIR . '/src/views/templates/base.html.php';
    // Naujas
    $persons = personSelected(true);
    if (isset($persons['fail'])) {
        $_SESSION['crud']['danger'][] = $persons['fail'];
        $persons = [];
    }
    // var_dump($persons);
    
    //$form = view($formTemplate, ['projects' => $projects, 'departments' => $departments,]);
    $form = view($formTemplate, ['persons' => $persons,]);
    return view($baseTemplate, ['form' => $form]);
}

function saveAction()
{
    $sanitized = sanitizeDepartmentInput();
    $validated = validateDepartmentInput();
    if (isset($validated['fail'])) {
        $_SESSION['crud']['warning'][] = $validated['fail'];
        return redirect(BASE_URL . '/departments' . '/' . $_SESSION['listmode']);
    }
    $save = departmentSave();
    if ($save['fail']) {
        $_SESSION['crud']['danger'][] = $save['fail'];
    }
    return redirect(BASE_URL . '/departments' . '/' . $_SESSION['listmode']);
}
