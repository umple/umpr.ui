<?php

class ProjectTest extends UnitTestCase
{

  public function setUp()
  {

  }

  public function tearDown()
  {
  }

  public function test_defaultListOwners()
  {
    // Must start server with UMPLIFY_DIR=/blah/blah/umplifyonline/app/fixtures/umplify
    $owners = Project::listOwners();
    $expected = array("myorg", "yourname");
    $this->assertEqual($expected, $owners);
  }


  public function test_listOwners()
  {
    $owners = Project::listOwners($GLOBALS["fixtures"] . "/umplify");
    $expected = array("myorg", "yourname");
    $this->assertEqual($expected, $owners);
  }

}
