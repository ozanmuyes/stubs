<?php

namespace Ozanmuyes\Stubs\Console\Commands;

use Illuminate\Console\Command as IlluminateCommand;
use Ozanmuyes\Stubs\Contracts\{
  InteractsWithFile,
  Stub
};
use Ozanmuyes\Stubs\Contracts\Console\Commands\ProvidesStub;
use Ozanmuyes\Stubs\Helpers;

/**
 * This is the class that all other command on the package
 * MUST extend. Common functionality were gathered in here.
 *
 * @package Ozanmuyes\Stubs\Console\Commands
 */
abstract class Command extends IlluminateCommand implements ProvidesStub, InteractsWithFile  {
  use CreateTargetFile;

  /**
   * @var Stub $stub
   */
  private $stub;

  /**
   * Create a new command instance.
   *
   * @param Stub $stub
   */
  public function __construct(Stub $stub) {
    parent::__construct();

    $this->stub = $stub;
  }

  function getStub() : Stub {
    return $this->stub;
  }

  /**
   * Since `handle` method is `final`, execute the command's
   * functionality here - i.e. gather arguments and options
   * and configure the stub accordingly etc.
   */
  protected abstract function beforeHandle();

  /**
   * Execute the console command.
   */
  public function handle() {
    $this->beforeHandle();

    if ($this->createTargetFile()) {
      // TODO Print success message
    } else {
      // TODO Print error message
    }

    // After this command's handling process is finish, look for further command calls.
    $this->afterHandle();
  }

  /**
   * Execute the consequent console command(s) if any (e.g. create migration,
   * seeder for model).
   * Since this function is optional do NOT make it abstract, the command
   * classes willing to have it, they MAY override it.
   */
  protected function afterHandle() {
    //
  }

  /**
   * The absolute file path to load the file including
   * the filename and extension.
   * E.g. '[PROJECT_PATH]/app/resources/stubs/model/default.blade.php'
   *
   * @return string
   */
  function getSourceFilePath() : string {
    return Helpers::constructPath(
      '', // not null
      Helpers::getStubsDirectory(),
      $this->getStub()->getType(),
      $this->getBaseStubFilename()
    );
  }

  function getFileContents() : string {
    return file_get_contents($this->getSourceFilePath());
  }

  protected function getBaseStubFilename() {
    try {
      return $this->option('base');
    } catch (\Exception $exception) {
      return 'default';
    }
  }

  /**
   * The absolute file path to save the file including
   * the file name.
   *
   * @return string
   * @throws \Exception
   */
  function getTargetFilePath() : string {
    $stubType = $this->getStub()->getType();
    $relativePathToTypeDirectory = config('stubs.paths.targets.' . $stubType, null);

    if (null === $relativePathToTypeDirectory) {
      throw new \Exception(
        'Either Stubs config was not published or the value of \'stubs.paths.targets.' . $stubType .
        '\' is not valid. Because of this it is not possible to get target file path for ' . $stubType .
        ' type of stub.'
      );
    }

    $a = Helpers::constructPath(
      'php',
      base_path(),
      $relativePathToTypeDirectory,
      $this->getBaseStubFilename() // TODO bunun command name göre yazılması lazım
    );

    return $a;
  }

  function writeContentsToFile(string $contents) : bool {
    try {
      file_put_contents($this->getTargetFilePath(), $contents);
    } catch (\Exception $e) {
      // TODO Maybe log the exception

      return false;
    }

    return true;
  }
}
