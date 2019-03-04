<?php
require_once ROOT_DIR . '/lib/Db.php';

/**
 * Forms an array containing the requested data of all persons
 * @param  bool $unAttached false
 * @param  array $orderBy []
 * @return array  $persons associative array of persons data, 
 * including an array of projects of every person.
 */
function personAll($unAttached = false, $orderBy = [])
{
    // Forming a primary query string
    $query = "SELECT persons.id, name, lastname, title
                FROM staff.persons
                LEFT JOIN staff.departments
                ON persons.department_id = departments.id";
    $order = '';

    // Completing the query string if records should be returned in the certain order
    if (isset($orderBy['fields'])) {
        $order = implode(', ', $orderBy['fields']);
        if ($order) {
            $order = ' ORDER BY ' . $order;
        }
    }
    $query  .= $order;
    // var_dump($query);

    // Function dbQuery($query, $types = [], $args = [], $mode = 'r', $lastId = false) is described in Db.php
    // Here returns an associative array of fetched records
    $persons = dbQuery($query);
    // var_dump($persons);

    if (isset($persons['fail']) or $unAttached) {
        return $persons;
    }

    // Function personSelectProjects($persons) is described in Person.php
    return personSelectProjects($persons);
}

/**
 * Forms an array containing the requested data of all persons
 * @param  bool  $unAttached false
 * @param  array  $orderBy
 * @return array  $persons associative array of all person's data
 */
function personSelected($unAttached = false, $orderBy = ['fields' => ['lastname ASC']])
{
    $query = "SELECT id, name, lastname, department_id
                FROM staff.persons";
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
    return $persons;
}

/**
 * Forms an array containing the requested data of one person, specified by id.
 * @param  int  $personId An id of the selected person
 * @param  bool  $unAttached false
 * @return array  $persons associative array (size = 1) of person's, specified by id, data, 
 * including an array of projects of this person
 */
function personById($personId, $unAttached = false)
{
    // Forming a primary query string
    $query = "SELECT id, name, lastname, department_id
                FROM staff.persons
                WHERE persons.id = ?";
    // Function dbQuery($query, $types = [], $args = [], $mode = 'r', $lastId = false) is described in Db.php
    // Here returns an a. array (size = 1) containing the other a. array with data of person specified by id.
    $persons = dbQuery($query, ['i'], [$personId]);
    //var_dump($persons);

    if (isset($persons['fail']) or $unAttached) {
        return $persons;
    }
    // Function personSelectProjects($persons) is described in Person.php
    //var_dump(personSelectProjects($persons));
    return personSelectProjects($persons);
}

/**
 * Includes the data about every person's projects in to the primary array
 * @param  array  $persons associative array of persons data
 * @return array  $persons associative array of persons data, 
 * now including an array of projects for every person
 */
function personSelectProjects($persons)
{
    foreach ($persons as $index => $person) {
        // Function personProjects($person['id']) is described in Person.php
        $persons[$index]['project_id'] = personProjects($person['id']);
    }
    return $persons;
}

/**
 * Fetches projects records from db for a specific person.
 * @param  int $personId 
 * @return array $projects an associative array of projects of a specific person.
 */
function personProjects($personId)
{
    // var_dump($personId);
    $query = "SELECT id, title
                FROM staff.projects
                INNER JOIN staff.persons_projects
                ON projects.id = persons_projects.project_id
                WHERE persons_projects.person_id = ?";
    // Function dbQuery($query, $types = [], $args = [], $mode = 'r', $lastId = false) is described in Db.php
    $projects = dbQuery($query, ['i'], [$personId]);
    if (isset($projects['fail'])) {
        return [['id' => null, 'title' => $projects['fail'],]];
    }
    // var_dump($projects);
    return $projects;
}

/**
 * Saves newly entered/or updated person's data in db
 * @return bool true in case of success
 */
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

/**
 * Returns the variable which value is html which was formed to trash request
 * @return string , containing all html tags and data required for visualisation in the browser
 */
function personTrash()
{
    if (isset($_POST['trash']) and !empty($_POST['trash'])) {
        // Counting how many persons are selected for deleting
        $idCount = count($_POST['trash']);
    } else {
        return ['fail' =>__('Nothing to trash.')];
    }

    // Tides-up the query string, leaves as many '?' as many persons were selected to delete.
    $query = 'DELETE FROM staff.persons WHERE persons.id IN ('
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
 * @param int $personId
 * @return bool $attach
 */
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

/**
 * Performs modifications for variable $unattach, which is used to maintain
 * many-to-many relations via persons_projects table in db
 * @param int $personId
 * @return bool $unattach
 */
function personUnAttach($personId)
{
    $query = "DELETE FROM staff.persons_projects WHERE persons_projects.person_id = ?";
    $unattach = dbQuery($query, ['i'], [$personId], 'w');
    return $unattach;
}

/**
 * Sanitizes the input of person's data 
 */
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

/**
 * Validates input of person's name and lastname
 */
function validatePersonInput()
{
    if (!$_POST['name'] or !$_POST['lastname']) {
        return ['fail' => __('Name and lastname fields required.')];
    }
    return true;
}
