<?php

namespace Ozanmuyes\Stubs\Contracts\Console\Commands;

use Ozanmuyes\Stubs\Contracts\Stub;

interface ProvidesStub {
  function getStub() : Stub;
}
