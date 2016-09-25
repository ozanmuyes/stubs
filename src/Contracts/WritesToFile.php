<?php

namespace Ozanmuyes\Stubs\Contracts;

interface WritesToFile {
  /**
   * The absolute file path to save the file including
   * the file name.
   *
   * @return string
   */
  function getTargetFilePath() : string;

  function writeContentsToFile(string $contents) : bool;
}
