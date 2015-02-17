<?php
require_once("_autoload.php");
require_once($GLOBALS["vendorDir"] . '/simpletest/unit_tester.php');
require_once($GLOBALS["vendorDir"] . '/simpletest/reporter.php');

$test = &new GroupTest('All tests');
if ($handle = opendir($GLOBALS["testsDir"]))
{
  while (false !== ($file = readdir($handle)))
  {
    if (!FileHelper::endsWith($file,"Test.php")) { continue; }

    $test->addTestFile($GLOBALS["testsDir"] . "/{$file}");
  }
  closedir($handle);
}
$test->run(new HtmlReporter());

