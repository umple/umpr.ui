<?php
if (function_exists('date_default_timezone_set')) date_default_timezone_set('America/New_York');

$GLOBALS["appDir"] = resolve_path("app");
$GLOBALS["viewables"] = array();
$GLOBALS["configDir"] = resolve_path("config");
$GLOBALS["vendorDir"] = realpath($_SERVER["DOCUMENT_ROOT"] . "/../app/vendor");
$GLOBALS["baseDir"] = realpath($_SERVER["DOCUMENT_ROOT"] . "/..");
$GLOBALS["testsDir"] = realpath($_SERVER["DOCUMENT_ROOT"] . "/../app/tests");

setg("pageName",basename($_SERVER['PHP_SELF']));

function resolve_path($name)
{
  if ($name == ".")
  {
    $publicRoot = $_SERVER["DOCUMENT_ROOT"] . "/..";
    $appRoot = $_SERVER["DOCUMENT_ROOT"];
  }
  else if ($_SERVER["DOCUMENT_ROOT"] != "")
  {
    $publicRoot = $_SERVER["DOCUMENT_ROOT"] . "/../$name";
    $appRoot = $_SERVER["DOCUMENT_ROOT"] . "/$name";
  }
  else
  {
    return "../{$name}";
  }

  return file_exists($publicRoot) ? realpath($publicRoot) : realpath($appRoot);
}

function __autoload($class_name)
{
  if (file_exists($GLOBALS["appDir"] . "/models/{$class_name}.php"))
  {
    require_once $GLOBALS["appDir"] . "/models/{$class_name}.php";
  }
}

function setg($lookup,$val)
{
  $GLOBALS["viewables"][$lookup] = $val;
}

function r($lookup,$default = null)
{
  if (isset($_REQUEST[$lookup]))
  {
    return $_REQUEST[$lookup];
  }
  else
  {
    return $default;
  }
}

function s($lookup,$default = null)
{
  if (isset($_SESSION[$lookup]))
  {
    return $_SESSION[$lookup];
  }
  else
  {
    return $default;
  }
}


function setr($lookup,$val)
{
  $_REQUEST[$lookup] = $val;
}

function l($lookup,$default = "")
{
  $locals = g("locals",array());
  if (isset($locals[$lookup]))
  {
    return $locals[$lookup];
  }
  else
  {
    return $default;
  }
}

function g($lookup,$default = "")
{
  if (isset($GLOBALS["viewables"][$lookup]))
  {
    return $GLOBALS["viewables"][$lookup];
  }
  else
  {
    return $default;
  }
}
