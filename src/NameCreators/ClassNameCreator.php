<?php

namespace Ozanmuyes\Stubs\NameCreators;

use Ozanmuyes\Stubs\Contracts\NameCreators\NameCreator;

class ClassNameCreator implements NameCreator {
  private $prefix = '';

  private $suffix = '';

  public function setPrefix(string $namePrefix) {
    $this->prefix = $namePrefix;
  }

  public function setSuffix(string $nameSuffix) {
    $this->suffix = $nameSuffix;
  }

  /**
   * Do nothing here. The classes extends this class
   * may override this function to fine tune.
   *
   * @param string $rawName
   *
   * @return string
   */
  protected function beforeCreate(string $rawName) : string {
    return $rawName;
  }

  /**
   * Create more suitable name for the stub
   * depending on given raw name, considering
   * naming convention states.
   *
   * @param string $rawName
   *
   * @return string
   */
  function create(string $rawName) : string {
    $name = $this->beforeCreate($rawName);

    // Convert it to title case
    $name = title_case($name);

    // Concatenate prefix and suffix
    $name = "{$this->prefix}{$name}{$this->suffix}";

    return $name;
  }
}
