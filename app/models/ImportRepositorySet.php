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
    $this->repositories = array();
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
  
  // line 134 ../../../../Data.ump
  public static function fromFile ($path) 
  {
    $jsonData = file_get_contents($path);
    $data = json_decode($jsonData, true);

    return self::fromJson($data);
  }

// line 141 ../../../../Data.ump
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