<?php
session_start();
require_once("_autoload.php");


$fileLoc = $_SERVER["DOCUMENT_ROOT"] . "/data/umpr_repos";

$GLOBALS["umprRepo"] = array(
    "dir" => $fileLoc,
    "git" => array(
        "url"    => "https://github.com/umple-ucosp/umpr.data.git",
        "path"   => $fileLoc,
        "remote" => "origin",
        "branch" => "master"
    )
);

// site-wide defaults
setg('meta-description', 'Umpr Imported Umple Model Repository');
setg('meta-keywords', 'umple,model,repository');

setg('umpr-repos', '/data/umpr_repos');
setg('umple-online-url', 'http://try.umple.org/?filename=%s&diagramtype=%s');

function require_view($viewName,$locals = array())
{
  setg("locals",$locals);
  require $GLOBALS["appDir"] . "/views/{$viewName}.html.php";
}

function redirect_to_error_page($message)
{
  header("Location: error.php?message=$message");
}
