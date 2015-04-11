<?php

if (function_exists('date_default_timezone_set')) date_default_timezone_set('America/New_York');

$GLOBALS["appDir"] = resolve_path("app");
$GLOBALS["viewables"] = array();
$GLOBALS["configDir"] = resolve_path("config");
$GLOBALS["baseDir"]   = realpath($_SERVER["DOCUMENT_ROOT"] . "/..");
$GLOBALS["vendorDir"] = realpath($GLOBALS['baseDir'] . "/vendor");
$GLOBALS["logDir"]    = realpath($_SERVER["DOCUMENT_ROOT"] . '/../app/logs');
$GLOBALS["testsDir"] = realpath($_SERVER["DOCUMENT_ROOT"] . "/../app/tests");
$GLOBALS["fixtures"] = realpath($_SERVER["DOCUMENT_ROOT"] . "/../app/fixtures");

require_once $GLOBALS['baseDir'] . '/vendor/autoload.php';
require_once $GLOBALS['appDir'] . '/models/autoload.php';

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


spl_autoload_register(function ($class_name) {
  if (file_exists($GLOBALS["appDir"] . "/models/{$class_name}.php"))
  {
    require_once $GLOBALS["appDir"] . "/models/{$class_name}.php";
  }
});

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

function srv($lookup, $default = null) {
  if (isset($_SERVER[$lookup]))
  {
    return $_SERVER[$lookup];
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

//$worker = new GitWorker($GLOBALS["umprRepo"]["git"]["url"], $GLOBALS["umprRepo"]["git"]["path"],
//    $GLOBALS["umprRepo"]["git"]["remote"], $GLOBALS["umprRepo"]["git"]["branch"]);
//$worker->start(PTHREADS_INHERIT_NONE);
//
//$GLOBALS["GitWorker"] = $worker;
