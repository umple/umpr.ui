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
  private $diagramType;

  //ImportRepository Associations
  private $files;
  private $importRepositorySet;

  //------------------------
  // CONSTRUCTOR
  //------------------------

  public function __construct($aName, $aDescription, $aPath, $aDiagramType, $aImportRepositorySet)
  {
    $this->name = $aName;
    $this->description = $aDescription;
    $this->path = $aPath;
    $this->diagramType = $aDiagramType;
    $this->files = array();
    $didAddImportRepositorySet = $this->setImportRepositorySet($aImportRepositorySet);
    if (!$didAddImportRepositorySet)
    {
      throw new Exception("Unable to create repository due to importRepositorySet");
    }
  }

  //------------------------
  // INTERFACE
  //------------------------

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

  public function getDiagramType()
  {
    return $this->diagramType;
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

  public function addFileVia($aPath, $aImportType, $aSuccessful, $aMessage)
  {
    return new ImportFile($aPath, $aImportType, $aSuccessful, $aMessage, $this);
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
    return $this == $compareTo;
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

}
?>