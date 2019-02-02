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

  'skip_methods' => [
    // "PUT"
  ]
];
