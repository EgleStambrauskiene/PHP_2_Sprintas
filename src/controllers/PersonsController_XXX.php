<?php
// Load models
require_once ROOT_DIR . '/src/models/Project.php';
require_once ROOT_DIR . '/src/models/Person.php';
require_once ROOT_DIR . '/src/models/Department.php';

function listAction($listMode = 'list')
{
    $_SESSION['listmode'] = $listMode;

    $listTemplate = ROOT_DIR . '/src/views/templates/crud/persons_' . $listMode . '.html.php';
    $baseTemplate = ROOT_DIR . '/src/views/templates/base.html.php';
    $trashConfirmTemplate = ROOT_DIR . '/src/views/templates/crud/trash_confirm_modal.html.php';

    $persons = personAll(false, ['fields' => ['lastname ASC']]);

    if (isset($persons['fail'])) {
        $_SESSION['crud']['danger'][] = $persons['fail'];
        $persons = [];
    }

    $trashModal = view($trashConfirmTemplate);
    $list = view($listTemplate, ['persons' => $persons, 'modal' => $trashModal]);
    return view($baseTemplate, ['list' => $list]);
}

function editAction($personId)
{
    $formTemplate = ROOT_DIR . '/src/views/templates/crud/persons_edit_form.html.php';
    $baseTemplate = ROOT_DIR . '/src/views/templates/base.html.php';

    $person = personById($personId);

    if (isset($person['fail'])) {
        $_SESSION['crud']['danger'][] = $person['fail'];
        $person = [];
        return redirect(BASE_URL . '/persons' . '/' . $_SESSION['listmode']);
    }

    if (empty($person)) {
        require_once ROOT_DIR . '/src/controllers/HttpStatusController.php';
        return httpStatusAction(406);
    }

    $departments = departmentAll(true);
    if (isset($departments['fail'])) {
        $_SESSION['crud']['danger'][] = $departments['fail'];
        $departments = [];
    }

    $projects    = projectAll(true);
    if (isset($departments['fail'])) {
        $_SESSION['crud']['danger'][] = $departments['fail'];
        $departments = [];
    }

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

function trashAction()
{
    $trashed = personTrash();
    if (isset($trashed['fail'])) {
        $_SESSION['crud']['danger'][] = $trashed['fail'];
    }
    return redirect(BASE_URL . '/persons' . '/' . $_SESSION['listmode']);
}

function addAction()
{
    $formTemplate = ROOT_DIR . '/src/views/templates/crud/persons_add_form.html.php';
    $baseTemplate = ROOT_DIR . '/src/views/templates/base.html.php';
    $departments = departmentAll(true);
    if (isset($departments['fail'])) {
        $_SESSION['crud']['danger'][] = $departments['fail'];
        $departments = [];
    }
    $projects = projectAll(true);
    if (isset($projects['fail'])) { //$departments pakeičiau į $projects
        $_SESSION['crud']['danger'][] = $projects['fail'];
        $projects = [];
    }
    $form = view($formTemplate, ['projects' => $projects, 'departments' => $departments,]);
    return view($baseTemplate, ['form' => $form]);
}

function saveAction()
{
    $sanitized = sanitizePersonInput();
    $validated = validatePersonInput();
    if (isset($validated['fail'])) {
        $_SESSION['crud']['warning'][] = $validated['fail'];
        return redirect(BASE_URL . '/persons' . '/' . $_SESSION['listmode']);
    }
    $save = personSave();
    if ($save['fail']) {
        $_SESSION['crud']['danger'][] = $save['fail'];
    }
    return redirect(BASE_URL . '/persons' . '/' . $_SESSION['listmode']);
}
