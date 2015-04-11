<?php
/*PLEASE DO NOT EDIT THIS CODE*/
/*This code was generated using the UMPLE 1.22.0.5146 modeling language!*/

class ImportRepositorySet
{

  //------------------------
  // MEMBER VARIABLES
  //------------------------

  //ImportRepositorySet Attributes
  private $date;
  private $time;
  private $umplePath;
  private $srcPath;
  private $repositoryNames;
  private $fileTypes;
  private $diagramTypes;

  //ImportRepositorySet Associations
  private $repositories;

  //Helper Variables
  private $cachedHashCode;
  private $canSetUmplePath;

  //------------------------
  // CONSTRUCTOR
  //------------------------

  public function __construct($aDate, $aTime, $aUmplePath)
  {
    $this->cachedHashCode = -1;
    $this->canSetUmplePath = true;
    $this->date = $aDate;
    $this->time = $aTime;
    $this->umplePath = $aUmplePath;
    $this->resetSrcPath();
    $this->repositoryNames = array();
    $this->fileTypes = array();
    $this->diagramTypes = array();
    $this->repositories = array();
    // line 137 "../../../..//Data.ump"
    $repoNames = array();
        $fileTypes = array();
        $diagramTypes = array();
    
        foreach ($this->getRepositories() as $repo) {
          array_push($repoNames, $repo->getName());
          array_push($diagramTypes, $repo->getDiagramType());
    
          foreach ($repo->getFiles() as $file) {
            array_push($fileTypes, $file->getType());
          }
        }
    
        $this->repositoryNames = array_unique($repoNames, SORT_STRING);
        $this->fileTypes = array_unique($fileTypes, SORT_STRING);
        $this->diagramTypes = array_unique($diagramTypes, SORT_STRING);
  }

  //------------------------
  // INTERFACE
  //------------------------

  public function setUmplePath($aUmplePath)
  {
    $wasSet = false;
    if (!$this->canSetUmplePath) { return false; }
    $this->umplePath = $aUmplePath;
    $wasSet = true;
    return $wasSet;
  }

  public function setSrcPath($aSrcPath)
  {
    $wasSet = false;
    $this->srcPath = $aSrcPath;
    $wasSet = true;
    return $wasSet;
  }

  public function resetSrcPath()
  {
    $wasReset = false;
    $this->srcPath = $this->getDefaultSrcPath();
    $wasReset = true;
    return $wasReset;
  }

  public function getDate()
  {
    return $this->date;
  }

  public function getTime()
  {
    return $this->time;
  }

  public function getUmplePath()
  {
    return $this->umplePath;
  }

  public function getSrcPath()
  {
    return $this->srcPath;
  }

  public function getDefaultSrcPath()
  {
    return null;
  }

  public function getRepositoryName($index)
  {
    $aRepositoryName = $this->repositoryNames[$index];
    return $aRepositoryName;
  }

  public function getRepositoryNames()
  {
    $newRepositoryNames = $this->repositoryNames;
    return $newRepositoryNames;
  }

  public function numberOfRepositoryNames()
  {
    $number = count($this->repositoryNames);
    return $number;
  }

  public function hasRepositoryNames()
  {
    $has = repositoryNames.size() > 0;
    return $has;
  }

  public function indexOfRepositoryName($aRepositoryName)
  {
    $rawAnswer = array_search($aRepositoryName,$this->repositoryNames);
    $index = $rawAnswer == null && $rawAnswer !== 0 ? -1 : $rawAnswer;
    return $index;
  }

  public function getFileType($index)
  {
    $aFileType = $this->fileTypes[$index];
    return $aFileType;
  }

  public function getFileTypes()
  {
    $newFileTypes = $this->fileTypes;
    return $newFileTypes;
  }

  public function numberOfFileTypes()
  {
    $number = count($this->fileTypes);
    return $number;
  }

  public function hasFileTypes()
  {
    $has = fileTypes.size() > 0;
    return $has;
  }

  public function indexOfFileType($aFileType)
  {
    $rawAnswer = array_search($aFileType,$this->fileTypes);
    $index = $rawAnswer == null && $rawAnswer !== 0 ? -1 : $rawAnswer;
    return $index;
  }

  public function getDiagramType($index)
  {
    $aDiagramType = $this->diagramTypes[$index];
    return $aDiagramType;
  }

  public function getDiagramTypes()
  {
    $newDiagramTypes = $this->diagramTypes;
    return $newDiagramTypes;
  }

  public function numberOfDiagramTypes()
  {
    $number = count($this->diagramTypes);
    return $number;
  }

  public function hasDiagramTypes()
  {
    $has = diagramTypes.size() > 0;
    return $has;
  }

  public function indexOfDiagramType($aDiagramType)
  {
    $rawAnswer = array_search($aDiagramType,$this->diagramTypes);
    $index = $rawAnswer == null && $rawAnswer !== 0 ? -1 : $rawAnswer;
    return $index;
  }

  public function getRepository_index($index)
  {
    $aRepository = $this->repositories[$index];
    return $aRepository;
  }

  public function getRepositories()
  {
    $newRepositories = $this->repositories;
    return $newRepositories;
  }

  public function numberOfRepositories()
  {
    $number = count($this->repositories);
    return $number;
  }

  public function hasRepositories()
  {
    $has = $this->numberOfRepositories() > 0;
    return $has;
  }

  public function indexOfRepository($aRepository)
  {
    $wasFound = false;
    $index = 0;
    foreach($this->repositories as $repository)
    {
      if ($repository->equals($aRepository))
      {
        $wasFound = true;
        break;
      }
      $index += 1;
    }
    $index = $wasFound ? $index : -1;
    return $index;
  }

  public static function minimumNumberOfRepositories()
  {
    return 0;
  }

  public function addRepositoryVia($aName, $aDescription, $aPath, $aRemoteLoc, $aLicense, $aSuccessRate, $aFailRate)
  {
    return new ImportRepository($aName, $aDescription, $aPath, $aRemoteLoc, $aLicense, $aSuccessRate, $aFailRate, $this);
  }

  public function addRepository($aRepository)
  {
    $wasAdded = false;
    if ($this->indexOfRepository($aRepository) !== -1) { return false; }
    $existingImportRepositorySet = $aRepository->getImportRepositorySet();
    $isNewImportRepositorySet = $existingImportRepositorySet != null && $this !== $existingImportRepositorySet;
    if ($isNewImportRepositorySet)
    {
      $aRepository->setImportRepositorySet($this);
    }
    else
    {
      $this->repositories[] = $aRepository;
    }
    $wasAdded = true;
    return $wasAdded;
  }

  public function removeRepository($aRepository)
  {
    $wasRemoved = false;
    //Unable to remove aRepository, as it must always have a importRepositorySet
    if ($this !== $aRepository->getImportRepositorySet())
    {
      unset($this->repositories[$this->indexOfRepository($aRepository)]);
      $this->repositories = array_values($this->repositories);
      $wasRemoved = true;
    }
    return $wasRemoved;
  }

  public function addRepositoryAt($aRepository, $index)
  {  
    $wasAdded = false;
    if($this->addRepository($aRepository))
    {
      if($index < 0 ) { $index = 0; }
      if($index > $this->numberOfRepositories()) { $index = $this->numberOfRepositories() - 1; }
      array_splice($this->repositories, $this->indexOfRepository($aRepository), 1);
      array_splice($this->repositories, $index, 0, array($aRepository));
      $wasAdded = true;
    }
    return $wasAdded;
  }

  public function addOrMoveRepositoryAt($aRepository, $index)
  {
    $wasAdded = false;
    if($this->indexOfRepository($aRepository) !== -1)
    {
      if($index < 0 ) { $index = 0; }
      if($index > $this->numberOfRepositories()) { $index = $this->numberOfRepositories() - 1; }
      array_splice($this->repositories, $this->indexOfRepository($aRepository), 1);
      array_splice($this->repositories, $index, 0, array($aRepository));
      $wasAdded = true;
    } 
    else 
    {
      $wasAdded = $this->addRepositoryAt($aRepository, $index);
    }
    return $wasAdded;
  }

  public function equals($compareTo)
  {
    if ($compareTo == null) { return false; }
    if (get_class($this) != get_class($compareTo)) { return false; }

    if ($this->umplePath != $compareTo->umplePath)
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
    if ($this->umplePath != null)
    {
      $this->cachedHashCode = $this->cachedHashCode * 23 + spl_object_hash($this->umplePath);
    }
    else
    {
      $this->cachedHashCode = $this->cachedHashCode * 23;
    }

    $this->canSetUmplePath = false;
    return $this->cachedHashCode;
  }

  public function delete()
  {
    foreach ($this->repositories as $aRepository)
    {
      $aRepository->delete();
    }
  }

  //------------------------
  // DEVELOPER CODE - PROVIDED AS-IS
  //------------------------
  
  // line 156 ../../../../Data.ump
  public static function fromFile ($path) 
  {
    $jsonData = file_get_contents($path);
    $data = json_decode($jsonData, true);

    return self::fromJson($data);
  }

// line 163 ../../../../Data.ump
  public static function fromJson ($obj) 
  {
    $out = new self($obj["date"], $obj["time"], $obj["umple"]);
    $out->setSrcPath($obj["src"]);

    foreach ($obj["repositories"] as $repo) {
      $out->addRepository(ImportRepository::fromJson($repo, $out));
    }

    return $out;
  }

}
?>