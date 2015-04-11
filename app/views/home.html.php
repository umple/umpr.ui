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
setg('IMPORT_STATES', array(
  "StateFetch"    => 0,
  "Fetch"         => 0,

  "StateImport"   => 1,
  "Import"        => 1,

  "StateModel"    => 2,
  "Model"         => 2,

  "StateComplete" => 3,
  "Complete"      => 3
));

?>

<div id="files-pane" class="container">
  <?php require_view('files'); ?>
</div>

<div id="repository-pane" class="container">
  <?php require_view('repositories'); ?>
</div>
