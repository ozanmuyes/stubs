<?php

namespace Ozanmuyes\Stubs;

use Illuminate\Console\AppNamespaceDetectorTrait;
use Ozanmuyes\Stubs\Contracts\Stub;

class ClassStub implements Stub {
  use AppNamespaceDetectorTrait;

  /**
   * @var NameCreator $nameCreator
   */
  private $nameCreator;

  /**
   * The type of class being generated, e.g. 'model', 'controller' etc.
   * This attribute must be set by the class that extends this
   * class to properly determine the corresponding stubs path.
   *
   * @var string $type
   */
  private $type;

  /**
   * The raw name of the class.
   *
   * @var string $rawName
   * @see ClassStub::getName()
   */
  private $name;

  /**
   * @var string $namespace
   */
  private $namespace;

  /**
   * @var array $imports
   */
  protected $imports = [];

  /**
   * @var string $extends
   */
  protected $extends = '';

  /**
   * @var array $implements
   */
  protected $implements = [];

  /**
   * @var array $traits
   */
  protected $traits = [];

  private $isDataPrepared = false;

  public function getType() : string {
    return $this->type;
  }

  public function setType(string $type) {
    $this->type = $type;

    $this->resolveNameCreator();
  }

  public function setNamespace(string $namespace = null) {
    if (null === $namespace) {
      $namespaceOfType = config('stubs.namespaces.' . $this->getType(), null);

      if (null === $namespaceOfType) {
        throw new \Exception(
          'Either Stubs config was not published or the value of \'stubs.namespaces.' . $this->type .
          '\' is not valid. Because of this it is not possible to get correct namespace for ' . $this->type .
          ' type of stub.'
        );
      }

      $namespace = trim($this->getAppNamespace() . '\\' . $namespaceOfType, '\\');
      $namespace = str_replace_last('\\', '', $namespace);
    }

    $this->namespace = $namespace;
  }

  /**
   * Get name with prefix and suffix concatenated.
   *
   * @return string
   */
  public function getName() : string {
    return $this->name;
  }

  public function setName(string $name) {
    $this->name = $this->nameCreator->create($name);
  }

  /**
   * The command class which extends this class MUST call this method
   * after changing any of the class' attributes (i.e. imports,
   * extends, implements, traits)
   */
  public function invalidateData() {
    $this->isDataPrepared = false;
  }

  public function getData() {
    if (!$this->isDataPrepared) {
      $this->prepareData();
    }

    return [
      'namespace' => $this->namespace,
      'imports' => $this->imports,
      'name' => $this->getName(),
      'extends' => $this->extends,
      'implements' => $this->implements,
      'traits' => $this->traits,
    ];
  }

  private function resolveNameCreator() {
    $this->nameCreator = app('stubs.name_creator.' . $this->getType());
  }

  private function prepareData() {
    $this->imports = Helpers::truncateFullyQualifiedNamespace($this->namespace, $this->imports, true);

    $namespaceAndImports = array_merge([$this->namespace], $this->imports);

    $this->extends = Helpers::truncateFullyQualifiedNamespace($namespaceAndImports, $this->extends);
    $this->implements = Helpers::truncateFullyQualifiedNamespace($namespaceAndImports, $this->implements, true);
    $this->traits = Helpers::truncateFullyQualifiedNamespace($namespaceAndImports, $this->traits, true);

    $this->isDataPrepared = true;
  }
}
