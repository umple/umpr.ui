<?php
/*PLEASE DO NOT EDIT THIS CODE*/
/*This code was generated using the UMPLE 1.19.0.3287 modeling language!*/

class Project
{

  //------------------------
  // MEMBER VARIABLES
  //------------------------

  //------------------------
  // CONSTRUCTOR
  //------------------------

  public function __construct()
  {}

  //------------------------
  // INTERFACE
  //------------------------

  public function equals($compareTo)
  {
    return $this == $compareTo;
  }

  public function delete()
  {}

  //------------------------
  // DEVELOPER CODE - PROVIDED AS-IS
  //------------------------
  
  // line 6 project.ump
  public static function listOwners ($umplifyDir = null) 
  {
    if ($umplifyDir == null) { $umplifyDir = getenv("UMPLIFY_DIR"); }
    if ($umplifyDir == null) { $umplifyDir = "/data/umplify_projects"; }
    $allDirs = scandir($umplifyDir);
    $owners = array_filter($allDirs, function($d) { return !in_array($d, array(".","..",".DS_Store")); });
    return array_values($owners);
  }

}
?>