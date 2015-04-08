<?php
session_start();
require_once("_autoload.php");

// site-wide defaults
setg('meta-description', 'Umpr Imported Umple Model Repository');
setg('meta-keywords', 'umple,model,repository');

setg('umpr-repos', '/data/umpr_repos');

function require_view($viewName,$locals = array())
{
  setg("locals",$locals);
  require $GLOBALS["appDir"] . "/views/{$viewName}.html.php";
}

function redirect_to_error_page($message)
{
  header("Location: error.php?message=$message");
}
