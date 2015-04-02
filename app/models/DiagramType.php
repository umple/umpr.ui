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
class DiagramType
{

  //------------------------
  // STATIC VARIABLES
  //------------------------

  public static $STATE = new DiagramType("state");
  public static $STRUCTURE = new DiagramType("structure");
  public static $CLASS = new DiagramType("class");

  //------------------------
  // MEMBER VARIABLES
  //------------------------

  //DiagramType Attributes

  /**
   * 
   * The type of diagram.
   */
  private $type;

  //------------------------
  // CONSTRUCTOR
  //------------------------

  public function __construct($aType)
  {
    $this->type = $aType;
  }

  //------------------------
  // INTERFACE
  //------------------------

  public function getType()
  {
    return $this->type;
  }

  public function equals($compareTo)
  {
    return $this == $compareTo;
  }

  public function delete()
  {}

}
?>