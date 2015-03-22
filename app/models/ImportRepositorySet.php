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
  private $rootPath;

  //ImportRepositorySet Associations
  private $repositories;

  //------------------------
  // CONSTRUCTOR
  //------------------------

  public function __construct($aDate, $aTime, $aRootPath)
  {
    $this->date = $aDate;
    $this->time = $aTime;
    $this->rootPath = $aRootPath;
    $this->repositories = array();
  }

  //------------------------
  // INTERFACE
  //------------------------

  public function getDate()
  {
    return $this->date;
  }

  public function getTime()
  {
    return $this->time;
  }

  public function getRootPath()
  {
    return $this->rootPath;
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

  public function addRepositoryVia($aName, $aDescription, $aPath, $aDiagramType)
  {
    return new ImportRepository($aName, $aDescription, $aPath, $aDiagramType, $this);
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
    return $this == $compareTo;
  }

  public function delete()
  {
    foreach ($this->repositories as $aRepository)
    {
      $aRepository->delete();
    }
  }

}
?>