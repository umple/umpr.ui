<?php
session_start();
require_once("_autoload.php");


function require_view($viewName,$locals = array())
{
  setg("locals",$locals);
  require $GLOBALS["appDir"] . "/views/{$viewName}.html.php";
}

function redirect_to_error_page($message)
{
  header("Location: error.php?message=$message");
}
