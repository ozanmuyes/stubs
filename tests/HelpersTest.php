<?php

class HelpersTest extends PHPUnit_Framework_TestCase {
  private static $originalDirectorySeparator;

  /**
   * This method is called before the first test of this test class is run.
   *
   * @since Method available since Release 3.4.0
   */
  public static function setUpBeforeClass() {
    parent::setUpBeforeClass();

    // Backup and change the directory separator to ease the test methods
    self::$originalDirectorySeparator = \Ozanmuyes\Stubs\Helpers::$directorySeparator;
    \Ozanmuyes\Stubs\Helpers::$directorySeparator = '/';
  }

  /**
   * This method is called after the last test of this test class is run.
   *
   * @since Method available since Release 3.4.0
   */
  public static function tearDownAfterClass() {
    parent::tearDownAfterClass();

    // Restore the directory separator
    \Ozanmuyes\Stubs\Helpers::$directorySeparator = self::$originalDirectorySeparator;
  }


  public function testGetStubsDirectory_1() {
    $expected = realpath(__DIR__ . '..' . DIRECTORY_SEPARATOR . 'resources' . DIRECTORY_SEPARATOR . 'stubs');
    $actual = \Ozanmuyes\Stubs\Helpers::getStubsDirectory();

    $this->assertEquals($expected, $actual);
  }


  public function testGetRelativePath_UnixWithoutExtension() {
    $absolutePath = '/foo/bar';
    $relativeTo = '/foo';

    $expected = 'bar';
    $actual = \Ozanmuyes\Stubs\Helpers::getRelativePath($absolutePath, $relativeTo);

    $this->assertEquals($expected, $actual);
  }

  public function testGetRelativePath_UnixWithExtensionInFile() {
    $absolutePath = '/foo/bar.baz';
    $relativeTo = '/foo';

    $expected = 'bar.baz';
    $actual = \Ozanmuyes\Stubs\Helpers::getRelativePath($absolutePath, $relativeTo);

    $this->assertEquals($expected, $actual);
  }

  public function testGetRelativePath_UnixWithExtensionInDirectory() {
    $absolutePath = '/foo/bar.baz/qux';
    $relativeTo = '/foo';

    $expected = 'bar.baz/qux';
    $actual = \Ozanmuyes\Stubs\Helpers::getRelativePath($absolutePath, $relativeTo);

    $this->assertEquals($expected, $actual);
  }

  public function testGetRelativePath_UnixWithExtensionInFileAndDirectory() {
    $absolutePath = '/foo/bar.baz/qux.quux';
    $relativeTo = '/foo';

    $expected = 'bar.baz/qux.quux';
    $actual = \Ozanmuyes\Stubs\Helpers::getRelativePath($absolutePath, $relativeTo);

    $this->assertEquals($expected, $actual);
  }

  public function testGetRelativePath_Windows() {
    $absolutePath = 'C:/foo/bar';
    $relativeTo = 'C:/foo';

    $expected = 'bar';
    $actual = \Ozanmuyes\Stubs\Helpers::getRelativePath($absolutePath, $relativeTo);

    $this->assertEquals($expected, $actual);
  }

  // Since testGetRelativePath_UnixWith* methods are pass, there is no need to test them under Windows


  public function testConstructPath_Directory() {
    $expected = 'foo/bar/baz/qux/';
    $actual = \Ozanmuyes\Stubs\Helpers::constructPath(null, 'foo', 'bar', 'baz', 'qux');

    $this->assertEquals($expected, $actual);
  }

  public function testConstructPath_File() {
    $expected = 'foo/bar/baz/qux.php';
    $actual = \Ozanmuyes\Stubs\Helpers::constructPath('php', 'foo', 'bar', 'baz', 'qux');

    $this->assertEquals($expected, $actual);
  }
}
