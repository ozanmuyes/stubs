<?php

namespace Ozanmuyes\Stubs\Console\Commands;

use Ozanmuyes\Stubs\Contracts\Stub;
use Ozanmuyes\Stubs\Helpers;

trait CreateTargetFile {
  /**
   * Compile the stub, render and write the final output to
   * designated file.
   */
  protected function createTargetFile() : bool {
    /**
     * @var Stub $stub
     */
    $stub = $this->getStub();

    $rendered = \View::make(
      Helpers::getRelativeStubPath($this->getSourceFilePath()),
      $stub->getData()
    )->render();

    // HACK Since Blade renderer could not properly handle PHP opening tag we add it here
    $rendered = "<?php\r\n\r\n" . $rendered;

    return $this->writeContentsToFile($rendered);
  }
}
