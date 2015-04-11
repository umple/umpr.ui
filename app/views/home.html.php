<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 10/04/15
 * Time: 10:15 PM
 */

// utilities

function unicodeString($str, $encoding=null) {
  if (is_null($encoding)) $encoding = ini_get('mbstring.internal_encoding');
  return preg_replace_callback('/\\\\u([0-9a-fA-F]{4})/u', create_function('$match', 'return mb_convert_encoding(pack("H*", $match[1]), '.var_export($encoding, true).', "UTF-16BE");'), $str);
}

// http://stackoverflow.com/a/834355
function str_starts_with($haystack, $needle)
{
  $length = strlen($needle);
  return (substr($haystack, 0, $length) === $needle);
}

function str_ends_with($haystack, $needle)
{
  $length = strlen($needle);
  if ($length == 0) {
    return true;
  }

  return (substr($haystack, -$length) === $needle);
}

/** @var $IMPORT_STATES Constant mapping */
$GLOBALS['IMPORT_STATES'] = array(
    "Fetch"    => 0,
    "Import"   => 1,
    "Model"    => 2,
    "Complete" => 3
);


$jsonData = file_get_contents($GLOBALS['umprRepo']['dir'] . '/meta.json');
$data = json_decode($jsonData, true);

$repoNames = array();
$fileTypes = array();
$diagramTypes = array();

foreach ($data["repositories"] as $repo) {
  array_push($repoNames, $repo["name"]);
  array_push($diagramTypes, $repo["diagramType"]);

  foreach ($repo["files"] as $file) {
    array_push($fileTypes, $file["type"]);
  }
}

$repoNames = array_unique($repoNames, SORT_STRING);
$fileTypes = array_unique($fileTypes, SORT_STRING);
$diagramTypes = array_unique($diagramTypes, SORT_STRING);

?>

<div class="files-container">
  <?php require_view('files'); ?>
</div>

<div class="repositories-container">
  <?php require_view('repositories'); ?>
</div>
