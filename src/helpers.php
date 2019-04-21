<?php

if (!function_exists('autoloader')) {

    /**
     * @param $classname
     */
    function autoloader($classname)
    {
        $lastSlash = strpos($classname, '\\') + 1;
        $classname = substr($classname, $lastSlash);
        $directory = str_replace('\\', '/', $classname);
        $filename = __DIR__ . getenv('src_path') . $directory . '.php';
        require_once($filename);
    }
}

if (!function_exists('init_env_file')) {

    function init_env_file()
    {
        if (file_exists('.env')) {
            foreach (explode("\n", file_get_contents('.env')) as $line) {
                if (empty($line)) continue;
                list($var, $val) = explode("=", $line, 2);
                //remove \r \n from $val
                $val = trim(preg_replace('/\s\s+/', '', $val));
                putenv("$var=$val");
            }
        } else {
            die('Could not file .env file');
        }
    }
}

if (!function_exists('is_int_number')) {
    /**
     * @param $input
     * @return bool
     */
    function is_int_number($input)
    {
        return (ctype_digit(strval($input)));
    }
}
