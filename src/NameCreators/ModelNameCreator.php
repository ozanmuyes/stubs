<?php

namespace Ozanmuyes\Stubs\NameCreators;

class ModelNameCreator extends ClassNameCreator {
  /**
   * Create more suitable name for the stub
   * depending on given name as naming
   * convention states.
   *
   * @param string $rawName
   *
   * @return string
   */
  function beforeCreate(string $rawName) : string {
    // Remove 'model*' word if exists
    $name = str_ireplace(['models', 'model'], '', $rawName);

    // Make the name singular
    $name = str_singular($name);

    return $name;
  }
}
