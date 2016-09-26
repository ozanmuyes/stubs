<?php

namespace Ozanmuyes\Stubs\Contracts\NameCreators;

interface NameCreator {
  function setPrefix(string $namePrefix);

  function setSuffix(string $nameSuffix);

  /**
   * Create more suitable name for the stub
   * depending on given raw name, considering
   * naming convention states.
   *
   * @param string $rawName
   *
   * @return string
   */
  function create(string $rawName) : string;
}
