<?php

return [
  /*
  |--------------------------------------------------------------------------
  | Database settings
  |--------------------------------------------------------------------------
  |
  | The name of the table to create in the database
  |
  */
  'table_name' => 'request_logs',

  /*
  |--------------------------------------------------------------------------
  | Array of sensitive data regular expressions to hide when storing in the database
  |--------------------------------------------------------------------------
  */
  'sensitive_data' => [
    "password"
  ],

  /*
  |--------------------------------------------------------------------------
  | Array of url regular expressions to skip when logging requests
  |--------------------------------------------------------------------------
  */
  'skip_urls' => [
    // "notifications"
  ],

  /*
  |--------------------------------------------------------------------------
  | Array of method regular expressions to skip when logging requests
  |--------------------------------------------------------------------------
  */
  'skip_methods' => [
    // "PUT"
  ],

  /*
  |--------------------------------------------------------------------------
  | Number of rows to create a db dump after
  |--------------------------------------------------------------------------
  */
  'dump_every' => 1000,

  /*
  |--------------------------------------------------------------------------
  | The default Storage location for the db dump
  |--------------------------------------------------------------------------
  */
  'storage_disk' => 'local',

  /*
  |--------------------------------------------------------------------------
  | The queue the will handle the logging job
  |--------------------------------------------------------------------------
  */
  'queue_name' => 'clearcut'
];
