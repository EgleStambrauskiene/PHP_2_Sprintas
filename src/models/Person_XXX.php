<?php
require_once ROOT_DIR . '/lib/Db.php';

function personAll($unAttached = false, $orderBy = [])
{
    $query = "SELECT persons.id, name, lastname, title
                FROM staff.persons
                LEFT JOIN staff.departments
                ON persons.department_id = departments.id";
    $order = '';
    
    if (isset($orderBy['fields'])) {
        $order = implode(', ', $orderBy['fields']);
        if ($order) {
            $order = ' ORDER BY ' . $order;
        }
    }
    $query  .= $order;
    $persons = dbQuery($query);

    if (isset($persons['fail']) or $unAttached) {
        return $persons;
    }
    return personSelectProjects($persons);
}

function personById($personId, $unAttached = false)
{
    $query = "SELECT id, name, lastname, department_id
                FROM staff.persons
                WHERE persons.id = ?";

    $persons = dbQuery($query, ['i'], [$personId]);

    if (isset($persons['fail']) or $unAttached) {
        return $persons;
    }
    return personSelectProjects($persons);
}

function personSelectProjects($persons)
{
    foreach ($persons as $index => $person) {
        $persons[$index]['project_id'] = personProjects($person['id']);
    }
    return $persons;
}

function personProjects($personId)
{
    $query = "SELECT id, title
                FROM staff.projects
                INNER JOIN staff.persons_projects
                ON projects.id = persons_projects.project_id
                WHERE persons_projects.person_id = ?";
    $projects = dbQuery($query, ['i'], [$personId]);
    if (isset($projects['fail'])) {
        return [['id' => null, 'title' => $projects['fail'],]];
    }
    return $projects;
}

function personSave()
{
    $query = "INSERT INTO staff.persons (name, lastname, department_id)
                VALUES (?, ?, ?)";
    $insert = true;
    if (isset($_POST['id']) and $_POST['id']) {
        $query = "UPDATE staff.persons
                    SET persons.name = ?, persons.lastname = ?,
                    persons.department_id = ?
                    WHERE persons.id = ?";
        $insert = false;
    }
    // Insert
    if ($insert) {
        $lastId = dbQuery($query, ['s', 's', 'i'],
            [
                $_POST['name'],
                $_POST['lastname'],
                $_POST['department_id'],
            ], 'w', true);
        if (isset($lastId['fail'])) {
            return $lastId;
        }
    }
    // Update
    if (!$insert) {
        $run = dbQuery($query, ['s', 's', 'i', 'i'],
            [
                $_POST['name'],
                $_POST['lastname'],
                $_POST['department_id'],
                $_POST['id'],
            ], 'w');
            if (isset($run['fail'])) {
                return $run;
            }
            $lastId = $_POST['id'];
    }
    // Maintain many-to-many relation to projects
    $unattach = personUnAttach($lastId);
    $attach = personAttach($lastId);

    if (isset($unattach['fail']) or isset($attach['fail'])) {
        return ['fail' =>__('Many To Many relation to projects maintenance failed.')];
    }
    return true;
}

function personTrash()
{
    if (isset($_POST['trash']) and !empty($_POST['trash'])) {
        $idCount = count($_POST['trash']);
    } else {
        return ['fail' =>__('Nothing to trash.')];
    }
    $query = 'DELETE FROM staff.persons WHERE persons.id IN ('
        . rtrim(str_repeat('?, ', $idCount), ', ') . ')';
    $types = explode('-', rtrim(str_repeat('i-', $idCount),'-'));
    $trash = dbQuery($query, $types, $_POST['trash'], 'w');
    return $trash;
}

function personAttach($personId)
{
    $query = "INSERT INTO staff.persons_projects (person_id, project_id) VALUES (?, ?)";
    $attach = true;
    if (isset($_POST['project_id']) and !empty($_POST['project_id'])) {
        foreach ($_POST['project_id'] as $projectId) {
            $attach = dbQuery($query, ['i', 'i'], [$personId, $projectId], 'w');
            if (isset($attach['fail'])) {
                return $attach;
            }
        }
    }
    return $attach;
}

function personUnAttach($personId)
{
    $query = "DELETE FROM staff.persons_projects WHERE persons_projects.person_id = ?";
    $unattach = dbQuery($query, ['i'], [$personId], 'w');
    return $unattach;
}

function sanitizePersonInput()
{
    if (isset($_POST['name'])) {
        //$_POST['name'] = mb_convert_case(filter_var($_POST['name'], FILTER_SANITIZE_STRING),  MB_CASE_TITLE);
    }

    if (isset($_POST['lastname'])) {
        $_POST['lastname'] = mb_convert_case(filter_var($_POST['lastname'], FILTER_SANITIZE_STRING),  MB_CASE_TITLE);
    }

    if (isset($_POST['department_id'])) {
        $_POST['department_id'] = filter_var($_POST['department_id'], FILTER_SANITIZE_STRING);
        if (!$_POST['department_id']) {
            $_POST['department_id'] = NULL;
        }
    }

    if (isset($_POST['id'])) {
        $_POST['id'] = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);
    }

    if (isset($_POST['trash'])) {
        $_POST['id'] = filter_var_array($_POST['trash'], FILTER_SANITIZE_NUMBER_INT);
    }

    if (isset($_POST['project_id'])) {
        $_POST['project_id'] = filter_var_array($_POST['project_id'], FILTER_SANITIZE_NUMBER_INT);
    }
}

function validatePersonInput()
{
    if (!$_POST['name'] or !$_POST['lastname']) {
        return ['fail' => __('Name and lastname fields required.')];
    }
    return true;
}
