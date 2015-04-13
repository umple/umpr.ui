<?php
/*PLEASE DO NOT EDIT THIS CODE*/
/*This code was generated using the UMPLE 1.22.0.5146 modeling language!*/

class ImportRepository
{

  //------------------------
  // MEMBER VARIABLES
  //------------------------

  //ImportRepository Attributes
  private $name;
  private $description;
  private $path;

  /**
   * 
   * Remotely accessible location for the Repository.
   */
  private $remoteLoc;
  private $license;
  private $successRate;
  private $failRate;

  //ImportRepository State Machines
  private static $DiagramTypeState = 1;
  private static $DiagramTypeStructure = 2;
  private static $DiagramTypeClass = 3;
  private $diagramType;

  //ImportRepository Associations
  private $files;
  private $importRepositorySet;

  //Helper Variables
  private $cachedHashCode;
  private $canSetPath;

  //------------------------
  // CONSTRUCTOR
  //------------------------

  public function __construct($aName, $aDescription, $aPath, $aRemoteLoc, $aLicense, $aSuccessRate, $aFailRate, $aImportRepositorySet)
  {
    $this->cachedHashCode = -1;
    $this->canSetPath = true;
    $this->name = $aName;
    $this->description = $aDescription;
    $this->path = $aPath;
    $this->remoteLoc = $aRemoteLoc;
    $this->license = $aLicense;
    $this->successRate = $aSuccessRate;
    $this->failRate = $aFailRate;
    $this->files = array();
    $didAddImportRepositorySet = $this->setImportRepositorySet($aImportRepositorySet);
    if (!$didAddImportRepositorySet)
    {
      throw new Exception("Unable to create repository due to importRepositorySet");
    }
    $this->setDiagramType(self::$DiagramTypeState);
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

  public function getName()
  {
    return $this->name;
  }

  public function getDescription()
  {
    return $this->description;
  }

  public function getPath()
  {
    return $this->path;
  }

  public function getRemoteLoc()
  {
    return $this->remoteLoc;
  }

  public function getLicense()
  {
    return $this->license;
  }

  public function getSuccessRate()
  {
    return $this->successRate;
  }

  public function getFailRate()
  {
    return $this->failRate;
  }

  public function getDiagramTypeFullName()
  {
    $answer = $this->getDiagramType();
    return $answer;
  }

  public function getDiagramType()
  {
    if ($this->diagramType == self::$DiagramTypeState) { return "DiagramTypeState"; }
    elseif ($this->diagramType == self::$DiagramTypeStructure) { return "DiagramTypeStructure"; }
    elseif ($this->diagramType == self::$DiagramTypeClass) { return "DiagramTypeClass"; }
    return null;
  }

  public function setDiagramType($aDiagramType)
  {
    if ($aDiagramType == "DiagramTypeState" || $aDiagramType == self::$DiagramTypeState)
    {
      $this->diagramType = self::$DiagramTypeState;
      return true;
    }
    elseif ($aDiagramType == "DiagramTypeStructure" || $aDiagramType == self::$DiagramTypeStructure)
    {
      $this->diagramType = self::$DiagramTypeStructure;
      return true;
    }
    elseif ($aDiagramType == "DiagramTypeClass" || $aDiagramType == self::$DiagramTypeClass)
    {
      $this->diagramType = self::$DiagramTypeClass;
      return true;
    }
    else
    {
      return false;
    }
  }

  public function getFile_index($index)
  {
    $aFile = $this->files[$index];
    return $aFile;
  }

  public function getFiles()
  {
    $newFiles = $this->files;
    return $newFiles;
  }

  public function numberOfFiles()
  {
    $number = count($this->files);
    return $number;
  }

  public function hasFiles()
  {
    $has = $this->numberOfFiles() > 0;
    return $has;
  }

  public function indexOfFile($aFile)
  {
    $wasFound = false;
    $index = 0;
    foreach($this->files as $file)
    {
      if ($file->equals($aFile))
      {
        $wasFound = true;
        break;
      }
      $index += 1;
    }
    $index = $wasFound ? $index : -1;
    return $index;
  }

  public function getImportRepositorySet()
  {
    return $this->importRepositorySet;
  }

  public static function minimumNumberOfFiles()
  {
    return 0;
  }

  public function addFileVia($aPath, $aImportType, $aMessage, $aSuccessful, $aAttrib)
  {
    return new ImportFile($aPath, $aImportType, $aMessage, $aSuccessful, $aAttrib, $this);
  }

  public function addFile($aFile)
  {
    $wasAdded = false;
    if ($this->indexOfFile($aFile) !== -1) { return false; }
    $existingImportRepository = $aFile->getImportRepository();
    $isNewImportRepository = $existingImportRepository != null && $this !== $existingImportRepository;
    if ($isNewImportRepository)
    {
      $aFile->setImportRepository($this);
    }
    else
    {
      $this->files[] = $aFile;
    }
    $wasAdded = true;
    return $wasAdded;
  }

  public function removeFile($aFile)
  {
    $wasRemoved = false;
    //Unable to remove aFile, as it must always have a importRepository
    if ($this !== $aFile->getImportRepository())
    {
      unset($this->files[$this->indexOfFile($aFile)]);
      $this->files = array_values($this->files);
      $wasRemoved = true;
    }
    return $wasRemoved;
  }

  public function addFileAt($aFile, $index)
  {  
    $wasAdded = false;
    if($this->addFile($aFile))
    {
      if($index < 0 ) { $index = 0; }
      if($index > $this->numberOfFiles()) { $index = $this->numberOfFiles() - 1; }
      array_splice($this->files, $this->indexOfFile($aFile), 1);
      array_splice($this->files, $index, 0, array($aFile));
      $wasAdded = true;
    }
    return $wasAdded;
  }

  public function addOrMoveFileAt($aFile, $index)
  {
    $wasAdded = false;
    if($this->indexOfFile($aFile) !== -1)
    {
      if($index < 0 ) { $index = 0; }
      if($index > $this->numberOfFiles()) { $index = $this->numberOfFiles() - 1; }
      array_splice($this->files, $this->indexOfFile($aFile), 1);
      array_splice($this->files, $index, 0, array($aFile));
      $wasAdded = true;
    } 
    else 
    {
      $wasAdded = $this->addFileAt($aFile, $index);
    }
    return $wasAdded;
  }

  public function setImportRepositorySet($aImportRepositorySet)
  {
    $wasSet = false;
    if ($aImportRepositorySet == null)
    {
      return $wasSet;
    }
    
    $existingImportRepositorySet = $this->importRepositorySet;
    $this->importRepositorySet = $aImportRepositorySet;
    if ($existingImportRepositorySet != null && $existingImportRepositorySet != $aImportRepositorySet)
    {
      $existingImportRepositorySet->removeRepository($this);
    }
    $this->importRepositorySet->addRepository($this);
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
    foreach ($this->files as $aFile)
    {
      $aFile->delete();
    }
    $placeholderImportRepositorySet = $this->importRepositorySet;
    $this->importRepositorySet = null;
    $placeholderImportRepositorySet->removeRepository($this);
  }

  //------------------------
  // DEVELOPER CODE - PROVIDED AS-IS
  //------------------------
  
  // line 109 ../../../../Data.ump
  public static function fromJson ($obj, $parent) 
  {
    $out = new self($obj["name"], $obj["description"], $obj["path"], 
      array_key_exists("remote", $obj) ? $obj["remote"] : null, $obj["license"], $obj["successRate"], $obj["failRate"], $parent);
    $out->setDiagramType("DiagramType" . ucfirst($obj["diagramType"]));

    foreach ($obj["files"] as $file) {
      $out->addFile(ImportFile::fromJson($file, $out));
    }

    return $out;
  }

}
?>