<?php
/*PLEASE DO NOT EDIT THIS CODE*/
/*This code was generated using the UMPLE 1.22.0.5146 modeling language!*/

/*
*
 * ImportType is a "enumeration" of information for what type of 
 * file was imported. 
 * 
 * @author Kevin Brightwell <kevin.brightwell2@gmail.com>
 * @since 12 Mar 2015
*/
class ImportType
{

  //------------------------
  // STATIC VARIABLES
  //------------------------

  public static $ECORE = new ImportType("ecore", "ECore");
  public static $YUML = new ImportType("yuml", "yUML");
  public static $SCXML = new ImportType("scxml", "State-Chart XML");

  //------------------------
  // MEMBER VARIABLES
  //------------------------

  //ImportType Attributes

  /**
   * 
   * The file type of an import, i.e. the extension.
   */
  private $fileType;

  /**
   * 
   * Longform description of the type.
   */
  private $name;

  //------------------------
  // CONSTRUCTOR
  //------------------------

  public function __construct($aFileType, $aName)
  {
    $this->fileType = $aFileType;
    $this->name = $aName;
  }

  //------------------------
  // INTERFACE
  //------------------------

  public function getFileType()
  {
    return $this->fileType;
  }

  public function getName()
  {
    return $this->name;
  }

  public function equals($compareTo)
  {
    return $this == $compareTo;
  }

  public function delete()
  {}

}
?>