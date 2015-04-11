<?php
/*PLEASE DO NOT EDIT THIS CODE*/
/*This code was generated using the UMPLE 1.22.0.5146 modeling language!*/

class ImportFile
{

  //------------------------
  // MEMBER VARIABLES
  //------------------------

  //ImportFile Attributes
  private $path;
  private $importType;
  private $message;
  private $successful;

  //ImportFile State Machines
  private static $StateFetch = 1;
  private static $StateImport = 2;
  private static $StateModel = 3;
  private static $StateCompleted = 4;
  private $state;

  //ImportFile Associations
  private $attrib;
  private $importRepository;

  //Helper Variables
  private $cachedHashCode;
  private $canSetPath;

  //------------------------
  // CONSTRUCTOR
  //------------------------

  public function __construct($aPath = null, $aImportType = null, $aMessage = null, $aSuccessful = null, $aAttrib = null, $aImportRepository = null)
  {
    if (func_num_args() == 0) { return; }

    $this->cachedHashCode = -1;
    $this->canSetPath = true;
    $this->path = $aPath;
    $this->importType = $aImportType;
    $this->message = $aMessage;
    $this->successful = $aSuccessful;
    if ($aAttrib == null || $aAttrib->getImportFile() != null)
    {
      throw new Exception("Unable to create ImportFile due to aAttrib");
    }
    $this->attrib = $aAttrib;
    $didAddImportRepository = $this->setImportRepository($aImportRepository);
    if (!$didAddImportRepository)
    {
      throw new Exception("Unable to create file due to importRepository");
    }
    $this->setState(self::$StateFetch);
  }
  public static function newInstance($aPath, $aImportType, $aMessage, $aSuccessful, $aRemoteLocForAttrib, $aImportRepository)
  {
    $thisInstance = new ImportFile();
    $thisInstance->path = $aPath;
    $thisInstance->importType = $aImportType;
    $thisInstance->message = $aMessage;
    $thisInstance->successful = $aSuccessful;
    $thisInstance->attrib = new ImportAttrib($aRemoteLocForAttrib, $thisInstance);
    $thisInstance->setImportRepository($aImportRepository);
    return $thisInstance;
  }

  //------------------------
  // INTERFACE
  //------------------------

  public function setPath($aPath)
  {
    $wasSet = false;
    if (!$this->canSetPath) { return false; }
    $this->path = $aPath;
    $wasSet = true;
    return $wasSet;
  }

  public function getPath()
  {
    return $this->path;
  }

  public function getImportType()
  {
    return $this->importType;
  }

  public function getMessage()
  {
    return $this->message;
  }

  public function getSuccessful()
  {
    return $this->successful;
  }

  public function isSuccessful()
  {
    return $this->successful;
  }

  public function getStateFullName()
  {
    $answer = $this->getState();
    return $answer;
  }

  public function getState()
  {
    if ($this->state == self::$StateFetch) { return "StateFetch"; }
    elseif ($this->state == self::$StateImport) { return "StateImport"; }
    elseif ($this->state == self::$StateModel) { return "StateModel"; }
    elseif ($this->state == self::$StateCompleted) { return "StateCompleted"; }
    return null;
  }

  public function setState($aState)
  {
    if ($aState == "StateFetch" || $aState == self::$StateFetch)
    {
      $this->state = self::$StateFetch;
      return true;
    }
    elseif ($aState == "StateImport" || $aState == self::$StateImport)
    {
      $this->state = self::$StateImport;
      return true;
    }
    elseif ($aState == "StateModel" || $aState == self::$StateModel)
    {
      $this->state = self::$StateModel;
      return true;
    }
    elseif ($aState == "StateCompleted" || $aState == self::$StateCompleted)
    {
      $this->state = self::$StateCompleted;
      return true;
    }
    else
    {
      return false;
    }
  }

  public function getAttrib()
  {
    return $this->attrib;
  }

  public function getImportRepository()
  {
    return $this->importRepository;
  }

  public function setImportRepository($aImportRepository)
  {
    $wasSet = false;
    if ($aImportRepository == null)
    {
      return $wasSet;
    }
    
    $existingImportRepository = $this->importRepository;
    $this->importRepository = $aImportRepository;
    if ($existingImportRepository != null && $existingImportRepository != $aImportRepository)
    {
      $existingImportRepository->removeFile($this);
    }
    $this->importRepository->addFile($this);
    $wasSet = true;
    return $wasSet;
  }

  public function equals($compareTo)
  {
    if ($compareTo == null) { return false; }
    if (get_class($this) != get_class($compareTo)) { return false; }

    if ($this->path != $compareTo->path)
    {
      return false;
    }

    return true;
  }

  public function hashCode()
  {
    if ($this->cachedHashCode != -1)
    {
      return $this->cachedHashCode;
    }
    $this->cachedHashCode = 17;
    if ($this->path != null)
    {
      $this->cachedHashCode = $this->cachedHashCode * 23 + spl_object_hash($this->path);
    }
    else
    {
      $this->cachedHashCode = $this->cachedHashCode * 23;
    }

    $this->canSetPath = false;
    return $this->cachedHashCode;
  }

  public function delete()
  {
    $existingAttrib = $this->attrib;
    $this->attrib = null;
    if ($existingAttrib != null)
    {
      $existingAttrib->delete();
    }
    $placeholderImportRepository = $this->importRepository;
    $this->importRepository = null;
    $placeholderImportRepository->removeFile($this);
  }

  //------------------------
  // DEVELOPER CODE - PROVIDED AS-IS
  //------------------------
  
  // line 56 ../../../../Data.ump
  public function setStateSimple ($state) 
  {
    $this->setState("State" . $state);
  }

// line 60 ../../../../Data.ump
  public static function fromJson ($obj, $parent) 
  {
    if (array_key_exists("attrib", $obj)) {
      $remote = $obj["attrib"]["url"];
      $type = $obj["attrib"]["type"];
    } else {
      $remote = null;
      $type = null;
    }

    $out = self::newInstance($obj["path"], $obj["type"], 
      array_key_exists("message", $obj) ? $obj["message"] : null,
      $obj["successful"], $remote, $parent);

    $out->setStateSimple($obj["lastState"]);
    if ($type != null) {
      $out->getAttrib()->setType("Type" . $type);
    }

    return $out;
  }

}
?>