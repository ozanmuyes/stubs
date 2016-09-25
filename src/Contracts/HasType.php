<?php

namespace Ozanmuyes\Stubs\Contracts;

interface HasType {
  function getType() : string;
  function setType(string $type);
}
