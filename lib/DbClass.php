<?php
namespace DbC;

use ConfigClass\ConfigClass;

class DbClass
{
    protected $args = [];
    protected $db = false;
    protected $lastId = false;
    protected $mode = 'r';
    protected $query;
    protected $result;
    protected $run;
    protected $types = [];

    /**
     * Connects DbClass mysqli
     */
    private function connectDbClass()
    {
        $db = @new mysqli(ConfigClass::DB_HOST, ConfigClass::DB_USER, 
            ConfigClass::DB_PASS, ConfigClass::DB_DATA);
        if ($db->connect_errno) {
            $this->db = false;
            die('Connect Error: ' . $db->connect_errno);
            //return false;
        }
        $this->db = $db;
        return $this;
    }

    public function dbQueryClass($query, $types, $args, $mode, $lastId)
    {
        // Check if number of types and arguments is eq
        if (count($this->types) !== count($this->args)) {
            $this->result = ['fail' =>  'Query parameters count failed.'];
            return $this;
        }

        // Most used steps to perform any query to db
        $this->db->connectDbClass();
        if (!$this->db) {
            $this->result = ['fail' => 'DB connection failed.'];
            return $this;
        }

        $run = $this->db->prepare($this->query);
        if (!$run) {
            $this->result = ['fail' => 'Bad query.'];
            return $this;
        }

        // If we have a parameterized query, then bind values
        if (!empty($this->types) and !empty($this->args)) {
            $typeString = implode('', $this->types);
            @$bind = $this->run->bind_param($typeString, ...$this->args);
            if (!$bind) {
                $this->result = ['fail' => 'Bad query.'];
                return $this;
            }
        }

        // ... and execute query
        $exec = $this->run->execute();
        if (!$exec) {
            $this->result = ['fail' => 'Query execution failed.'];
            return $this;
        }

        // We have SELECT
        if ($this->mode === 'r') {
            $this->result = $this->run->get_result();
            $fetch  = $this->result->fetch_all(MYSQLI_ASSOC);
            $this->run = null;
            $this->db = null;
            return $fetch;
        }

        // We have INSERT or UPDATE and need last inserted id
        if ($this->lastId) {
            $this->lastId = $this->run->insert_id;
            $this->run = null;
            $this->db = null;
            $this->result = $lastId;
            return $this;
        }

        // We have INSERT, UPDATE, DELETE and do not need last inserted id
        $this->result = true;
        return $this;
    }

    public function get()
    {
        return $this->result;
    }

    public function __set($property, $value)
    {
        $this->$property = $value;
        return $this;
    }

}
/**
 * Accumulates most methods, using with db
 * @param  string  $query  Query string to execute
 * @param  array   $types  Query binding types
 * @param  array   $args   Query binding values
 * @param  string  $mode   read – 'r', write – 'w'
 * @param  boolean $lastId last inserted id – optional. Use with INSERT queries which required last_id
 * @return mixed           associative array of fetched rows in mode r
 *                         array with fail description in failrue of query building and executing stages
 *                         true if all ok in mode w
 * @see all model files in src/models
 */