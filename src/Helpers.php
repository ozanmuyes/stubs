<?php

namespace Ozanmuyes\Stubs;

class Helpers {
  public static $directorySeparator = DIRECTORY_SEPARATOR;

  /**
   * Returns absolute path to stubs directory
   * considering various settings.
   *
   * @return string
   */
  public static function getStubsDirectory() {
    // First try to get path information from `config/stubs.php`,
    // if somehow can not obtain the value check if the stubs were
    // published to `resources/stubs` directory, and if not published
    // fallback to package's stubs directory.
    $path = config('stubs.paths.stubs', null);

    if (null === $path) {
      $path = file_exists(resource_path('stubs'))
        ? resource_path('stubs')
        : base_path('vendor/ozanmuyes/stubs/resources/stubs');
    }

    return $path;
  }

  /**
   * Truncates given subjects considering searches if any matches.
   *
   * @param string|array $searches Namespace, or Namespace and Imports
   * @param string|array $subjects Imports for Namespace,
   *                               Namespace and Imports for Extends
   * @param bool         $mustReturnArray
   *
   * @return array|string
   * @throws \Exception
   */
  public static function truncateFullyQualifiedNamespace($searches, $subjects, bool $mustReturnArray = false) {
    if (is_string($subjects)) {
      if ('' === $subjects) {
        return $subjects;
      }

      $subjects = [$subjects];
    } elseif (is_array($subjects)) {
      if (count($subjects) === 0) {
        return $subjects;
      }
    } else {
      throw new \Exception('Subjects MUST be either string or array, neither given.');
    }

    if (is_string($searches)) {
      $searches = [$searches];
    }

    $results = [];
    $searchesCount = count($searches);

    foreach ($subjects as $subject) {
      for ($i = 0; $i < $searchesCount; $i++) {
        $pos = strpos($subject, $searches[$i]);

        if (false === $pos) {
          if ($i + 1 === $searchesCount) {
            // We exhausted the `searches` list with `subject`, add `subject` to `result`
            // In order to not to lose it even though we are unable to truncate it.
            $results[] = $subject;
          } else {
            continue;
          }
        } else {
          $trimmed = trim(substr($subject, strlen($searches[$i])), '\\');

          // If the `subject` and the `search` are equal just get class name from `subject`
          if ('' === $trimmed) {
            $trimmed = trim(substr($subject, strrpos($subject, '\\')), '\\');
          }

          $results[] = $trimmed;

          // We have successfully truncate the `subject`, so there is no need to look further
          break;
        }
      }
    }

    if ($mustReturnArray) {
      return $results;
    }

    switch (count($results)) {
      case 1: {
        if ($mustReturnArray) {
          return $results;
        } else {
          return $results[0];
        }
      }

      default: // 2 or more
        return $results;
    }
  }

  private static function pathHasExtension(string $path) : bool {
    $lastDotPos = strrpos($path, '.');

    // Since there is no dot in the path, there is no extension in the path.
    if (false === $lastDotPos) {
      return false;
    }

    $lastDirectorySeparatorPos = strrpos($path, self::$directorySeparator);

    // Since the path does not have parent directory and dot exists in the path,
    // there is extension in the path
    if (false === $lastDirectorySeparatorPos) {
      return true;
    }

    // We MUST handle dot included directory names as well, like in Linux
    if ($lastDirectorySeparatorPos + 1 === strlen($path)) {
      return false;
    }

    return ($lastDotPos > $lastDirectorySeparatorPos);
  }

  private static function addTrailingSlash(string $path) {
    if (self::pathHasExtension($path) || ends_with($path, self::$directorySeparator)) {
      return $path;
    }

    return $path . self::$directorySeparator;
  }

  public static function getRelativePath(string $absolutePath, string $relativeTo) {
    $relativePath = starts_with($absolutePath, $relativeTo)
      ? substr($absolutePath, strlen($relativeTo) + 1)
      : $absolutePath;

    return $relativePath;
  }

  public static function getRelativeStubPath(string $stubAbsolutePath) {
    return self::getRelativePath($stubAbsolutePath, self::getStubsDirectory());
  }

  public static function constructPath($extension, ...$directories) {
    $directories = implode(self::$directorySeparator, $directories);

    // Check if the path indicates a directory or file
    if (null === $extension) {
      // Directory
      return $directories . self::$directorySeparator;
    } else {
      // File
      // Check if extension should be set
      if ('' === $extension) {
        // Without extension
        return $directories;
      } else {
        // With extension
        return "{$directories}.{$extension}";
      }
    }
  }

//  private static function getIncludeContents($filename) {
//    if (is_file($filename)) {
//      ob_start();
//      /** @noinspection PhpIncludeInspection */
//      include $filename;
//
//      return ob_get_clean();
//    }
//
//    return false;
//  }
//
//  /**
//   * @param $context
//   * @param $name
//   *
//   * @return mixed|null
//   * @see http://stackoverflow.com/a/2287029/250453
//   */
//  private static function getNestedVar(&$context, $name) {
//    $pieces = explode('.', $name);
//
//    foreach ($pieces as $piece) {
//      if (!is_array($context) || !array_key_exists($piece, $context)) {
//        // error occurred
//
//        return null;
//      }
//
//      $context = &$context[$piece];
//    }
//
//    return $context;
//  }
//
//  /**
//   * Get the specified configuration value from application's config file,
//   * or if not found package's config file.
//   *
//   * @param  string $key
//   * @param  string $default
//   *
//   * @return mixed
//   */
//  public static function config(string $key, string $default = null) {
//    try {
//      $value = config($key, null);
//    } catch (\Exception $exception) {
//      $value = null;
//    }
//
//    // TODO Test below
//    if (null === $value) {
//      $packageConfig = self::getIncludeContents(realpath(__DIR__.'/../config/stubs.php'));
//
//      if (false === $packageConfig) {
//        return $default;
//      }
//
//      $value = self::getNestedVar($packageConfig, $key);
//    }
//
//    if (null === $value) {
//      return $default;
//    }
//
//    return $value;
//  }
}
