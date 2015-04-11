<?php
/*PLEASE DO NOT EDIT THIS CODE*/
/*This code was generated using the UMPLE 1.22.0.5146 modeling language!*/

/*
*
 * 
 *
 * @author Kevin Brightwell <kevin.brightwell2@gmail.com>
 *
 * @since Apr 9, 2015
*/
class ImportAttrib
{

  //------------------------
  // MEMBER VARIABLES
  //------------------------

  //ImportAttrib Attributes
  private $remoteLoc;

  //ImportAttrib State Machines
  private static $TypeRAW = 1;
  private static $TypeREFERENCE = 2;
  private $Type;

  //ImportAttrib Associations
  private $importFile;

  //------------------------
  // CONSTRUCTOR
  //------------------------

  public function __construct($aRemoteLoc = null, $aImportFile = null)
  {
    if (func_num_args() == 0) { return; }

    $this->remoteLoc = $aRemoteLoc;
    if ($aImportFile == null || $aImportFile->getAttrib() != null)
    {
      throw new Exception("Unable to create ImportAttrib due to aImportFile");
    }
    $this->importFile = $aImportFile;
    $this->setType(self::$TypeRAW);
  }
  public static function newInstance($aRemoteLoc, $aPathForImportFile, $aImportTypeForImportFile, $aMessageForImportFile, $aIsSuccessfulForImportFile, $aImportRepositoryForImportFile)
  {
    $thisInstance = new ImportAttrib();
    $thisInstance->remoteLoc = $aRemoteLoc;
    $thisInstance->importFile = new ImportFile($aPathForImportFile, $aImportTypeForImportFile, $aMessageForImportFile, $aIsSuccessfulForImportFile, $thisInstance, $aImportRepositoryForImportFile);
    return $thisInstance;
  }

  //------------------------
  // INTERFACE
  //------------------------

  public function getRemoteLoc()
  {
    return $this->remoteLoc;
  }

  public function getTypeFullName()
  {
    $answer = $this->getType();
    return $answer;
  }

  public function getType()
  {
    if ($this->Type == self::$TypeRAW) { return "TypeRAW"; }
    elseif ($this->Type == self::$TypeREFERENCE) { return "TypeREFERENCE"; }
    return null;
  }

  public function setType($aType)
  {
    if ($aType == "TypeRAW" || $aType == self::$TypeRAW)
    {
      $this->Type = self::$TypeRAW;
      return true;
    }
    elseif ($aType == "TypeREFERENCE" || $aType == self::$TypeREFERENCE)
    {
      $this->Type = self::$TypeREFERENCE;
      return true;
    }
    else
    {
      return false;
    }
  }

  public function getImportFile()
  {
    return $this->importFile;
  }

  public function equals($compareTo)
  {
    return $this == $compareTo;
  }

  public function delete()
  {
    $existingImportFile = $this->importFile;
    $this->importFile = null;
    if ($existingImportFile != null)
    {
      $existingImportFile->delete();
    }
  }

}
?>