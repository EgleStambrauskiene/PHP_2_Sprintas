<?php
require_once ROOT_DIR . '/lib/Db.php';

/**
 * Forms an array containing the requested data of all departments
 * @param  array $orderBy [] 
 * @return array $departments associative array of departments data.
 */
function departmentAll($orderBy = [])
{
    // Forming a primary query string
    $query = "SELECT departments.id, title
                FROM staff.departments";
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
    // Here returns an associative array of fetched records.
    $departments = dbQuery($query);

    if (isset($departments['fail'])) {
        return $departments;
    }
    //var_dump($departments);
    return $departments;
}

/**
 * Forms an array containing the requested data of one department, specified by id.
 * @param  int  $departmentId An id of the selected department.
 * @return array  $persons associative array (size = 1) of person's, specified by id, data, 
 * including an array of projects of this person
 */
function departmentById($departmentId)
{
    // Forming a primary query string
    $query = "SELECT id, title
                FROM staff.departments
                WHERE departments.id = ?";
    // Function dbQuery($query, $types = [], $args = [], $mode = 'r', $lastId = false) is described in Db.php
    // Here returns an a. array (size = 1) containing the other a. array with data of department specified by id.
    $departments = dbQuery($query, ['i'], [$departmentId]);

    if (isset($departments['fail'])) {
        return $departments;
    }
    // Function departmentSelectPersons($departments) is described in Department.php
    return departmentSelectPersons($departments);
}

/**
 * Includes the data about every department's persons in to the primary array
 * @param  array  $departments associative array of departments data
 * @return array  $departments associative array of departments data, 
 * now including an array of persons for every departments.
 */
function departmentSelectPersons($departments)
{
    foreach ($departments as $index => $department) {
        // Function departmentPersons($department['id']) is described in Department.php
        $departments[$index]['person_id'] = departmentPersons($department['id']);
    }
    return $departments;
}

/**
 * Fetches persons records from db for a specific department.
 * @param  int $departmentId 
 * @return array $persons an associative array of persons of a specific department.
 */
function departmentPersons($departmentId)
{
    $query = "SELECT id, name, lastname
                FROM staff.persons
                WHERE persons.department_id = ?";
    // Function dbQuery($query, $types = [], $args = [], $mode = 'r', $lastId = false) is described in Db.php
    $persons = dbQuery($query, ['i'], [$departmentId]);
    if (isset($persons['fail'])) {
        return [['id' => null, 'title' => $persons['fail'],]];
    }
    return $persons;
}

/**
 * Saves newly entered/or updated department's data in db
 * @return bool true in case of success
 */
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

/**
 * Returns the variable which value is html which was formed to trash request
 * @return string , containing all html tags and data required for visualisation in the browser
 */
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

/**
 * Sanitizes the input of department's data 
 */
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

/**
 * Validates input of department's title.
 */
function validateDepartmentInput()
{
    if (!$_POST['title']) {
        return ['fail' => __('Title field required.')];
    }
    return true;
}
