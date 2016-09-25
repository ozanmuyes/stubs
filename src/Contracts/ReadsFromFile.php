<?php

namespace Ozanmuyes\Stubs\Contracts;

interface ReadsFromFile {
  /**
   * The absolute file path to load the file including
   * the file name.
   *
   * @return string
   */
  function getSourceFilePath() : string;

  function getFileContents() : string;
}
