<?php

namespace Ozanmuyes\Stubs\Console\Commands;

class StubCommand extends Command {
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = '
  stub
  {type=class : Type of the class to be created}
  {name? : Name of the created class}
  {base=default : Base stub file name under the type folder}
  {--named : Name of the created class}
  ';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Fluently create various classes';

  /**
   * Since `handle` method is `final`, execute the command's
   * functionality here - i.e. gather arguments and options
   * and configure the stub accordingly etc.
   */
  protected function beforeHandle() {
    $stub = $this->getStub();

    // Set stub type
    $stub->setType($this->argument('type'));

    // Set stub (class) name
    $className = $this->argument('name') ?: $this->option('named');

    // TODO Write more specific exception class
    if (null === $className) {
      /**
       * This kind of exception caused by the developer of the package.
       * Check all the classes that extends this class and ensure
       * the class that extends requires `name` as argument.
       */
      throw new \Exception('Class name was not specified');
    }

    $stub->setName($className);

    // TODO Check all required arguments
  }
}
