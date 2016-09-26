<?php

namespace Ozanmuyes\Stubs\Providers;

use Illuminate\Support\ServiceProvider;
use Ozanmuyes\Stubs\Console\Commands\StubCommand;
use Ozanmuyes\Stubs\Contracts\Stub;
use Ozanmuyes\Stubs\ClassStub;
use Ozanmuyes\Stubs\Helpers;
use Ozanmuyes\Stubs\NameCreators\ModelNameCreator;

class StubsServiceProvider extends ServiceProvider {
  /**
   * List of console commands that this package provides.
   *
   * @var array $commands
   */
  protected $commands = [
    StubCommand::class,

    // Add more command registration here
  ];

  /**
   * Bootstrap the application services.
   *
   * @return void
   */
  public function boot() {
    // The method MUST be called here, after Blade specific
    // registrations had been done. Do NOT move somewhere else.
    $this->registerBladeDirectives();

    // TODO Test here via 'vendor:publish'
    $this->publishes([
      realpath(__DIR__.'/../../config/stubs.php') => config_path('stubs.php'),
    ]);
  }

  /**
   * Register the application services.
   */
  public function register() {
    $this->registerStubClasses();
    $this->registerNameCreatorClasses();

    $this->registerStubsViewFinder();

    // Register console commands
    $this->commands($this->commands);
  }

  /**
   * Try to meaningfully resolve stub classes depending
   * on which class wants it.
   */
  private function registerStubClasses() {
//    $this->app->when(StubModelCommand::class)
//      ->needs(Stub::class)
//      ->give(ModelStub::class);

    // Add more conditional binding here

    // Finally bind ClassStub class as fallback
    $this->app->bind(Stub::class, ClassStub::class);
  }

  private function registerNameCreatorClasses() {
    $this->app->bind('stubs.name_creators.model', ModelNameCreator::class);

    // Add more stub type's binding here

//    $this->app->when(ModelStub::class)
//      ->needs(NameCreator::class)
//      ->give(ModelNameCreator::class);

    // Add more conditional binding here
  }

  private function registerStubsViewFinder() {
    /**
     * @var \Illuminate\View\FileViewFinder $finder
     */
    $finder = app('view.finder'); // resolve the finder

    // Add package specific extensions and paths
    $finder->addExtension('blade.stub');
    $finder->addExtension('stub');
    $finder->addLocation(Helpers::getStubsDirectory());

    // Rebind expanded finder
    // TODO Maybe call to `rebind` is necessary
    $this->app->singleton('view.finder', function () use ($finder) {
      return $finder;
    });
  }

  private function registerBladeDirectives() {
    $blade = $this->app->make('blade.compiler');

    $blade->directive('namespace', function ($param) {
      if ('' === $param) {
        $param = '$namespace';
      }

      return '<?php echo "namespace " . ' . $param . ' . ";" . PHP_EOL; ?>';
    });

    $blade->directive('imports', function ($param) {
      if ('' === $param) {
        $param = '$imports';
      }

      return
        '<?php foreach(' . $param . ' as $item) { echo "use " . $item . ";" . PHP_EOL . PHP_EOL; } ?>';
    });

    $blade->directive('class', function ($param) {
      if ('' === $param) {
        $param = '$name, $extends, $implements';
      }

      $param = array_map('trim', explode(',', $param));

      $name = null;
      $extends = null;
      $implements = null;

      switch (count($param)) {
        case 3: {
          $name = $param[0];
          $extends = $param[1];
          $implements = $param[2];

          break;
        }

        case 2: {
          $name = $param[0];
          $extends = $param[1];

          break;
        }

        case 1: {
          $name = $param[0];

          break;
        }

        default: {
          throw new \Exception('Stub\'s name attribute was not set.');
        }
      }

      $extendsString = '';
      $implementsString = '';

      if (null !== $extends) {
        $extendsString = 'if ("" !== $extends) { echo " extends " . $extends; }';
      }

      if (null !== $implements) {
        $implementsString = 'if (count($implements) > 0) { echo " implements " . implode(", ", ' . $implements . '); }';
      }

      return sprintf(
        '<?php echo "class " . %s; %s %s ?>',
        $name,
        $extendsString,
        $implementsString
      );
    });

    $blade->directive('traits', function ($param) {
      if ('' === $param) {
        $param = '$traits';
      }

      return
        '<?php if (count($traits) > 0) { echo "use " . implode(", ", ' . $param . ') . ";" . PHP_EOL; } ?>';
    });

    // TODO Add 'function' directive
  }
}
