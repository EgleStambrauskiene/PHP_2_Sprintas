<?php
require_once ROOT_DIR . '/lib/Db.php';

/**
 * Forms an array containing the requested data of all projects.
 * @param  bool $unAttached false
 * @param  array $orderBy []
 * @return array  $projects associative array of all projects data,
 * including an array of persons in every project.
 */
function projectAll($unAttached = false, $orderBy = [])
{
    // Forming a primary query string
    $query = "SELECT projects.id, title, budget, description
                FROM staff.projects";
    $order = '';

    // Completing the query string if records should be returned in the certain order
    if (isset($orderBy['fields'])) {
        $order = implode(', ', $orderBy['fields']);
        if ($order) {
            $order = ' ORDER BY ' . $order;
        }
    }
    $query  .= $order;
    // Function dbQuery($query, $types = [], $args = [], $mode = 'r', $lastId = false) is described in Db.php
    $projects = dbQuery($query);

    if (isset($projects['fail']) or $unAttached) {
        return $projects;
    }
    // Function projectSelectPersons($projects) is described in Project.php
    return projectSelectPersons($projects);
}

/**
 * Forms an array containing the requested data of one project, specified by id.
 * @param  int  $projectId An id of the selected project.
 * @param  bool  $unAttached false
 * @return array  $projects associative array (size = 1) of project's, specified by id, data, 
 * including an array of persons of this project
 */
function projectById($projectId, $unAttached = false)
{
    // Forming a primary query string
    $query = "SELECT id, title, budget, description
                FROM staff.projects
                WHERE projects.id = ?";
    // Function dbQuery($query, $types = [], $args = [], $mode = 'r', $lastId = false) is described in Db.php
    // Here returns an a. array (size = 1) containing the other a. array with data of project specified by id.
    $projects = dbQuery($query, ['i'], [$projectId]);

    if (isset($projects['fail']) or $unAttached) {
        return $projects;
    }
    // Function projectSelectPersons($projects) is described in Project.php
    return projectSelectPersons($projects);
}

/**
 * Includes the data about every project's persons in to the primary array
 * @param  array  $projects associative array of projects data
 * @return array  $projects associative array of projects data, 
 * now including an array of persons in every project.
 */
function projectSelectPersons($projects)
{
    foreach ($projects as $index => $project) {
        // Function projectPersons($project['id']) is described in Project.php
        $projects[$index]['person_id'] = projectPersons($project['id']);
    }
    return $projects;
}

/**
 * Fetches persons records from db for a specific project.
 * @param  int $projectId 
 * @return array $persons an associative array of persons in a specific project.
 */
function projectPersons($projectId)
{
    $query = "SELECT id, name, lastname
                FROM staff.persons
                INNER JOIN staff.persons_projects
                ON persons.id = persons_projects.person_id
                WHERE persons_projects.project_id = ?";
    // Function dbQuery($query, $types = [], $args = [], $mode = 'r', $lastId = false) is described in Db.php            
    $persons = dbQuery($query, ['i'], [$projectId]);
    if (isset($persons['fail'])) {
        return [['id' => null, 'name' => $persons['fail'],]];
    }
    return $persons;
}

/**
 * Saves newly entered/or updated project's data in db
 * @return bool true in case of success
 */
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

/**
 * Returns the variable which value is html which was formed to trash request
 * @return string , containing all html tags and data required for visualisation in the browser
 */
function projectTrash()
{
    if (isset($_POST['trash']) and !empty($_POST['trash'])) {
        // Counting how many projects are selected for deleting
        $idCount = count($_POST['trash']);
    } else {
        return ['fail' =>__('Nothing to trash.')];
    }
    // Tides-up the query string, leaves as many '?' as many persons were selected to delete.
    $query = 'DELETE FROM staff.projects WHERE projects.id IN ('
        . rtrim(str_repeat('?, ', $idCount), ', ') . ')';
    // $types - formed array of strings 'i', array size is the same as quantity persons selected for deleting. 
    $types = explode('-', rtrim(str_repeat('i-', $idCount),'-'));
    // Here all formed html (type: string) is setted as a value of a certain variable
    $trash = dbQuery($query, $types, $_POST['trash'], 'w');
    return $trash;
}

/**
 * Performs modifications for variable $attach, which is used to maintain
 * many-to-many relations via persons_projects table in db
 * @param int $projectId
 * @return bool $attach
 */
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

/**
 * Performs modifications for variable $unattach, which is used to maintain
 * many-to-many relations via persons_projects table in db
 * @param int $projectId
 * @return bool $unattach
 */
function projectUnAttach($projectId)
{
    $query = "DELETE FROM staff.persons_projects WHERE persons_projects.project_id = ?";
    $unattach = dbQuery($query, ['i'], [$projectId], 'w');
    return $unattach;
}

/**
 * Sanitizes the input of project's data 
 */
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

/**
 * Validates input of project's title
 */
function validateProjectInput()
{
    if (!$_POST['title']) {
        return ['fail' => __('Title field required.')];
    }
    return true;
}
