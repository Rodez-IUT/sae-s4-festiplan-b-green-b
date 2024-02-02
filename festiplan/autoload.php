<?php

/**
 * Manage automatic import of classes
 * with their namespaces.
 */
class Autoloader {

    /**
     * Define the __autoload function on
     * Autoloader::festiplan_autoloader()
     */
    public static function autoload() {
        spl_autoload_register(array(__CLASS__, "festiplan_autoloader"));
    }

    /**
     * Manages autoload by including files
     * with their path defined in the namespace
     */
    public static function festiplan_autoloader($namespace) {
        $path = str_replace("\\","/", $namespace);
        $path = str_replace("festiplan", "", $path);
        $path = $path[0] == "/" ? substr($path, 1) : $path;
        include $path . ".php";
    }

}

Autoloader::autoload();