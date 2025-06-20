<?php

namespace Logic\Repository;
class DB
{
    private static $mysql;

    public static function initialization($host = 'localhost', $username = 'root', $password = '', $dbname = '')
    {
        self::$mysql = new \mysqli($host, $username, $password, $dbname);

        if (self::$mysql->connect_error) {
            throw new \Exception("Connection failed: " . self::$mysql->connect_error);
        }
    } 

    public static function query($sql)
    {
        return self::$mysql->query($sql);
    }

    public static function prepare($query)
    {
        return self::$mysql->prepare($query);
    }

    public static function real_escape_string($string)
    {
        return self::$mysql->real_escape_string($string);
    }

    public static function begin_transaction()
    {
        return self::$mysql->begin_transaction();
    }

    public static function commit()
    {
        return self::$mysql->commit();
    }

    public static function rollback()
    {
        return self::$mysql->rollback();
    }

    public static function close()
    {
        return self::$mysql->close();
    }

    public static function set_charset($charset)
    {
        return self::$mysql->set_charset($charset);
    }

    public static function get_errno()
    {
        return self::$mysql->errno;
    }

    public static function get_error()
    {
        return self::$mysql->error;
    }

    public static function affected_rows()
    {
        return self::$mysql->affected_rows;
    }

    public static function insert_id()
    {
        return self::$mysql->insert_id;
    }

    public static function ping()
    {
        return self::$mysql->ping();
    }

    public static function select_db($dbname)
    {
        return self::$mysql->select_db($dbname);
    }

    public static function stat()
    {
        return self::$mysql->stat();
    }

    public static function more_results()
    {
        return self::$mysql->more_results();
    }

    public static function next_result()
    {
        return self::$mysql->next_result();
    }

    public static function multi_query($query)
    {
        return self::$mysql->multi_query($query);
    }

    public static function store_result()
    {
        return self::$mysql->store_result();
    }

    public static function use_result()
    {
        return self::$mysql->use_result();
    }

    public static function escape_string($string)
    {
        return self::$mysql->escape_string($string);
    }

    public static function thread_id()
    {
        return self::$mysql->thread_id;
    }

    public static function kill($thread_id)
    {
        return self::$mysql->kill($thread_id);
    }
}
