<?php

$GLOBALS["appDir"] = realpath($_SERVER["DOCUMENT_ROOT"] . "/../app");

$GLOBALS["viewables"] = array();
$GLOBALS["viewables"]['title'] = "Umplify Online";
$GLOBALS["viewables"]['meta-description'] = "An online view of umplified projects";
$GLOBALS["viewables"]['meta-keywords'] = "umple,umpleonline,umplifyonline,umplifer,cruise";

function __autoload($class_name)
{
  if (file_exists($GLOBALS["appDir"] . "/models/{$class_name}.php"))
  {
    require_once $GLOBALS["appDir"] . "/models/{$class_name}.php";
  }
}

function require_view($viewName)
{
  require_once $GLOBALS["appDir"] . "/views/{$viewName}.html.php";
}
