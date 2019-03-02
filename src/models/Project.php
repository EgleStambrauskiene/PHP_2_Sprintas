<?php
require_once ROOT_DIR . '/lib/Db.php';

function projectAll($unAttached = false, $orderBy = [])
{
    $query = "SELECT projects.id, title, budget, description
                FROM staff.projects";
    $order = '';

    if (isset($orderBy['fields'])) {
        $order = implode(', ', $orderBy['fields']);//implode - Join array elements (= strigs) with a string (glue string = ', ').
        if ($order) {
            $order = ' ORDER BY ' . $order;
        }
    }
    $query  .= $order;
    $projects = dbQuery($query);

    if (isset($projects['fail']) or $unAttached) {
        return $projects;
    }
    
    return projectSelectPersons($projects);
}

function projectById($projectId, $unAttached = false)
{
    $query = "SELECT id, title, budget, description
                FROM staff.projects
                WHERE projects.id = ?";

    $projects = dbQuery($query, ['i'], [$projectId]);

    if (isset($projects['fail']) or $unAttached) {
        return $projects;
    }
    return projectSelectPersons($projects);
}

function projectSelectPersons($projects)
{
    foreach ($projects as $index => $project) {
        $projects[$index]['person_id'] = projectPersons($project['id']);
    }
    return $projects;
}

function projectPersons($projectId)
{
    $query = "SELECT id, name, lastname
                FROM staff.persons
                INNER JOIN staff.persons_projects
                ON persons.id = persons_projects.person_id
                WHERE persons_projects.project_id = ?";
    $persons = dbQuery($query, ['i'], [$projectId]);
    if (isset($persons['fail'])) {
        return [['id' => null, 'name' => $persons['fail'],]];
    }
    return $persons;
}

function projectSave()
{
    $query = "INSERT INTO staff.projects (title, budget, description)
                VALUES (?, ?, ?)";
    $insert = true;
    if (isset($_POST['id']) and $_POST['id']) {
        $query = "UPDATE staff.projects
                    SET projects.title = ?, projects.budget = ?, projects.description = ?
                    WHERE projects.id = ?";
        $insert = false;
    }
    // Insert
    if ($insert) {
        $lastId = dbQuery($query, ['s', 'd', 's'],
            [
                $_POST['title'],
                $_POST['budget'],
                $_POST['description'],
            ], 'w', true);
            // var_dump($lastId, $query);
            // die();
            if (isset($lastId['fail'])) {
            return $lastId;
        }

    }

    // Update
    if (!$insert) {
        $run = dbQuery($query, ['s', 'd', 's', 'i'],
            [
                $_POST['title'],
                $_POST['budget'],
                $_POST['description'],
                $_POST['id'],
            ], 'w');
            if (isset($run['fail'])) {
                return $run;
            }
            $lastId = $_POST['id'];
    }
    // Maintain many-to-many relation to projects
    $unattach = projectUnAttach($lastId);
    $attach = projectAttach($lastId);

    if (isset($unattach['fail']) or isset($attach['fail'])) {
        return ['fail' => __('Many To Many relation maintenance failed.')];
    }
    return true;
}

function projectTrash()
{
    if (isset($_POST['trash']) and !empty($_POST['trash'])) {
        $idCount = count($_POST['trash']);
    } else {
        return ['fail' =>__('Nothing to trash.')];
    }
    $query = 'DELETE FROM staff.projects WHERE projects.id IN ('
        . rtrim(str_repeat('?, ', $idCount), ', ') . ')';
    $types = explode('-', rtrim(str_repeat('i-', $idCount),'-'));//reikia tikrinti
    $trash = dbQuery($query, $types, $_POST['trash'], 'w');
    return $trash;
}

function projectAttach($projectId)
{
    $query = "INSERT INTO staff.persons_projects (person_id, project_id) VALUES (?, ?)";
    $attach = true;
    if (isset($_POST['person_id']) and !empty($_POST['person_id'])) {
        foreach ($_POST['person_id'] as $personId) {
            $attach = dbQuery($query, ['i', 'i'], [$personId, $projectId], 'w');
            if (isset($attach['fail'])) {
                return $attach;
            }
        }
    }
    return $attach;
}

function projectUnAttach($projectId)
{
    $query = "DELETE FROM staff.persons_projects WHERE persons_projects.project_id = ?";
    $unattach = dbQuery($query, ['i'], [$projectId], 'w');
    return $unattach;
}

function sanitizeProjectInput()
{
    if (isset($_POST['title'])) {
        $_POST['title'] = filter_var($_POST['title'], FILTER_SANITIZE_STRING);
    }

    if (isset($_POST['budget'])) {
        $_POST['budget'] = filter_var($_POST['budget'], FILTER_SANITIZE_NUMBER_FLOAT);
    }

    if (isset($_POST['description'])) {
        $_POST['description'] = filter_var($_POST['description'], FILTER_SANITIZE_STRING);
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

function validateProjectInput()
{
    if (!$_POST['title']) {
        return ['fail' => __('Title field required.')];
    }
    return true;
}
