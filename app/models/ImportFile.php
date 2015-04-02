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
  private $successful;
  private $message;

  //ImportFile Associations
  private $importRepository;

  //------------------------
  // CONSTRUCTOR
  //------------------------

  public function __construct($aPath, $aImportType, $aSuccessful, $aMessage, $aImportRepository)
  {
    $this->path = $aPath;
    $this->importType = $aImportType;
    $this->successful = $aSuccessful;
    $this->message = $aMessage;
    $didAddImportRepository = $this->setImportRepository($aImportRepository);
    if (!$didAddImportRepository)
    {
      throw new Exception("Unable to create file due to importRepository");
    }
  }

  //------------------------
  // INTERFACE
  //------------------------

  public function getPath()
  {
    return $this->path;
  }

  public function getImportType()
  {
    return $this->importType;
  }

  public function getSuccessful()
  {
    return $this->successful;
  }

  public function getMessage()
  {
    return $this->message;
  }

  public function isSuccessful()
  {
    return $this->successful;
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
    return $this == $compareTo;
  }

  public function delete()
  {
    $placeholderImportRepository = $this->importRepository;
    $this->importRepository = null;
    $placeholderImportRepository->removeFile($this);
  }

}
?>