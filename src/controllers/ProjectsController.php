<?php
// Load models
require_once ROOT_DIR . '/src/models/Project.php';
require_once ROOT_DIR . '/src/models/Person.php';
require_once ROOT_DIR . '/src/models/Department.php';

function listAction($listMode = 'list')
{
    $_SESSION['listmode'] = $listMode;

    $listTemplate = ROOT_DIR . '/src/views/templates/crud/projects_' . $listMode . '.html.php';
    $baseTemplate = ROOT_DIR . '/src/views/templates/base.html.php';
    $trashConfirmTemplate = ROOT_DIR . '/src/views/templates/crud/trash_confirm_modal.html.php';

    $projects = projectAll(false, ['fields' => ['title ASC']]);

    if (isset($projects['fail'])) {
        $_SESSION['crud']['danger'][] = $projects['fail'];
        $projects = [];
    }

    $trashModal = view($trashConfirmTemplate);
    $list = view($listTemplate, ['projects' => $projects, 'modal' => $trashModal]);
    return view($baseTemplate, ['list' => $list]);
}

function editAction($projectId)
{
    $formTemplate = ROOT_DIR . '/src/views/templates/crud/projects_edit_form.html.php';
    $baseTemplate = ROOT_DIR . '/src/views/templates/base.html.php';

    $project = projectById($projectId);

    if (isset($project['fail'])) {
        $_SESSION['crud']['danger'][] = $project['fail'];
        $project = [];
        return redirect(BASE_URL . '/projects' . '/' . $_SESSION['listmode']);
    }

    if (empty($project)) {
        require_once ROOT_DIR . '/src/controllers/HttpStatusController.php';
        return httpStatusAction(406);
    }

    $departments = departmentAll(true);
    if (isset($departments['fail'])) {
        $_SESSION['crud']['danger'][] = $departments['fail'];
        $departments = [];
    }
    $persons    = personAll(true);
    if (isset($departments['fail'])) {
        $_SESSION['crud']['danger'][] = $departments['fail'];
        $departments = [];
    }
    $form = view(
        $formTemplate,
        [
            'projects' => $projects,
            'person' => $person,
            'departments' => $departments,
        ]
    );
    return view($baseTemplate, ['form' => $form]);
}

function trashAction()
{
    $trashed = personTrash();
    if (isset($trashed['fail'])) {
        $_SESSION['crud']['danger'][] = $trashed['fail'];
    }
    return redirect(BASE_URL . '/projects' . '/' . $_SESSION['listmode']);
}

function addAction()
{
    $formTemplate = ROOT_DIR . '/src/views/templates/crud/projects_add_form.html.php';
    $baseTemplate = ROOT_DIR . '/src/views/templates/base.html.php';
    $departments = departmentAll(true);
    if (isset($departments['fail'])) {
        $_SESSION['crud']['danger'][] = $departments['fail'];
        $departments = [];
    }
    $persons = personAll(true);
    if (isset($departments['fail'])) {
        $_SESSION['crud']['danger'][] = $persons['fail'];
        $persons = [];
    }
    $form = view($formTemplate, ['persons' => $persons, 'departments' => $departments,]);
    return view($baseTemplate, ['form' => $form]);
}

function saveAction()
{
    $sanitized = sanitizeProjectInput();
    $validated = validateProjectInput();
    if (isset($validated['fail'])) {
        $_SESSION['crud']['warning'][] = $validated['fail'];
        return redirect(BASE_URL . '/projects' . '/' . $_SESSION['listmode']);
    }
    $save = projectSave();
    if ($save['fail']) {
        $_SESSION['crud']['danger'][] = $save['fail'];
    }
    return redirect(BASE_URL . '/projects' . '/' . $_SESSION['listmode']);
}
