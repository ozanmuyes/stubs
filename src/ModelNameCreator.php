<?php

namespace Ozanmuyes\Stubs;

class ModelNameCreator implements NameCreator {
  private $prefix = '';

  private $suffix = '';

  function setPrefix(string $namePrefix) {
    $this->prefix = $namePrefix;
  }

  function setSuffix(string $nameSuffix) {
    $this->suffix = $nameSuffix;
  }

  /**
   * Create more suitable name for the stub
   * depending on given name as naming
   * convention states.
   *
   * @param string $rawName
   *
   * @return string
   */
  function create(string $rawName) : string {
    // Remove 'model' word if exists
    $name = str_ireplace(['models', 'model'], '', $rawName);

    // Make the name singular
    $name = str_singular($name);

    // Convert it to title case
    $name = title_case($name);

    // Concatenate prefix and suffix
    $name = "{$this->prefix}{$name}{$this->suffix}";

    return $name;
  }
}
