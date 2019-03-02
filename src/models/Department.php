<?php
require_once ROOT_DIR . '/lib/Db.php';

function departmentAll($unAttached = false, $orderBy = [])
{
    // $query = "SELECT departments.id, title, name, lastname
    $query = "SELECT departments.id, title
                FROM staff.departments";
                // LEFT JOIN staff.persons
                // ON persons.department_id = departments.id";
    $order = '';

    if (isset($orderBy['fields'])) {
        $order = implode(', ', $orderBy['fields']);
        if ($order) {
            $order = ' ORDER BY ' . $order;
        }
    }
    $query  .= $order;
    $departments = dbQuery($query);

    if (isset($departments['fail']) or $unAttached) {
        return $departments;
    }
    // return departmentSelectPersons($departments);
    return $departments;
}

function departmentById($departmentId, $unAttached = false)
{
    $query = "SELECT id, title
                FROM staff.departments
                WHERE departments.id = ?";

    $departments = dbQuery($query, ['i'], [$departmentId]);

    if (isset($departments['fail']) or $unAttached) {
        return $departments;
    }
    return departmentSelectPersons($departments);
}

// Manau, reikia perrašyti iš esmės,
// nes departamentų ir personų nesieja indexas.
function departmentSelectPersons($departments)
{
    foreach ($departments as $index => $department) {
        $departments[$index]['person_id'] = departmentPersons($department['id']);
    }
    return $departments;
}

// Manau, reikia perrašyti iš esmės,
// nes departamentų ir personų nesieja pagalbinė lentelė.
function departmentPersons($departmentId)
{
    $query = "SELECT id, name, lastname
                FROM staff.persons
                WHERE persons.department_id = ?";
    $persons = dbQuery($query, ['i'], [$departmentId]);
    if (isset($persons['fail'])) {
        return [['id' => null, 'title' => $persons['fail'],]];
    }
    return $persons;
}

function departmentSave()
{
    $query = "INSERT INTO staff.departments (title)
                VALUES (?)";
    $insert = true;
    if (isset($_POST['id']) and $_POST['id']) {
        $query = "UPDATE staff.departments
                    SET departments.title = ?
                    WHERE departments.id = ?";
        $insert = false;
    }

    // Insert
    if ($insert) {
        $lastId = dbQuery($query, ['s'], [$_POST['title'],], 'w', true);

        if (isset($lastId['fail'])) {
            return $lastId;
        }
    }
    // Update
    if (!$insert) {
        // $run = dbQuery($query, ['s', 'd', 's', 'i'],
        $run = dbQuery($query, ['s', 'i'],
            [
                $_POST['title'],
                $_POST['id'],
            ], 'w');
            if (isset($run['fail'])) {
                return $run;
            }
            $lastId = $_POST['id'];
    }
    return true;
}

function departmentTrash()
{
    if (isset($_POST['trash']) and !empty($_POST['trash'])) {
        $idCount = count($_POST['trash']);
    } else {
        return ['fail' =>__('Nothing to trash.')];
    }
    $query = 'DELETE FROM staff.departments WHERE departments.id IN ('
        . rtrim(str_repeat('?, ', $idCount), ', ') . ')';
    $types = explode('-', rtrim(str_repeat('i-', $idCount),'-'));
    $trash = dbQuery($query, $types, $_POST['trash'], 'w');
    return $trash;
}

function sanitizeDepartmentInput()
{
    if (isset($_POST['title'])) {
        $_POST['title'] = filter_var($_POST['title'], FILTER_SANITIZE_STRING);
    }

    if (isset($_POST['id'])) {
        $_POST['id'] = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);
    }

    if (isset($_POST['trash'])) {
        $_POST['id'] = filter_var_array($_POST['trash'], FILTER_SANITIZE_NUMBER_INT);
    }

    if (isset($_POST['person_id'])) {
        $_POST['person_id'] = filter_var_array($_POST['person_id'], FILTER_SANITIZE_NUMBER_INT);
    }
}

function validateDepartmentInput()
{
    if (!$_POST['title']) {
        return ['fail' => __('Title field required.')];
    }
    return true;
}
