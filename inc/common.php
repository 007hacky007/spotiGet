<?php
spl_autoload_register(function ($class_name) {
    include 'class/' . $class_name . '.class.php';
});

$GLOBALS["config"] = parse_ini_file("config.ini");