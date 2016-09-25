<?php

return [

  'paths' => [

    /**
     * Absolute path to directory that holds stub files.
     *
     * Default is '[PROJECT_PATH]/app/resources/stubs'.
     */
    'stubs' => base_path('resources/stubs'),

    /**
     * Indicates where to write created classes for each different
     * type of stub files (e.g. model, controllers etc.).
     */
    'targets' => [

      'model' => 'app',
      'controller' => 'app\\Http\\Controllers',
      //

    ],

  ],

  /**
   * MUST be relative to application's namespace
   */
  'namespaces' => [

    'model' => '',
    'controller' => 'Http\\Controllers',
    //

  ],

];
