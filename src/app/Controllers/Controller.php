<?php

namespace Hea\Controllers;

class Controller
{
    public static function view($viewName, $params)
    {
        if (file_exists(dirname(dirname(__FILE__))."/Views/$viewName.php")) {
            // Extract the variables
            extract($params);

            // Start output buffering
            ob_start();

            // Include the template file
            require_once dirname(dirname(__FILE__))."/Views/$viewName.php";

            // End buffering and return its contents
            $output = ob_get_clean();
        }
        // Display layout
        print $output;
    }
}