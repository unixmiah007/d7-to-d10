<?php
 // Example: Replace 'your_database_name', 'your_database_user', 'your_database_password' and 'localhost' with your actual database credentials.
  $database_url = 'mysql://drupal9:drupal9@localhost/drupal7prod';

  // If your database server is on a different host, use the host option in the database URL:
  // $database_url = 'mysql://your_database_user:your_database_password@your_database_host/your_database_name';

  // Set the active database connection
  Database::addConnectionInfo('default', 'default', $database_url);

  // Ensure the active database connection is established
  drupal_bootstrap(DRUPAL_BOOTSTRAP_DATABASE);

  // Your entity reference fields array here
  $entityref_fields = array('field_name_1', 'field_name_2'); // Replace with your actual field names

  // DB Update script goes here
  foreach ($entityref_fields as $ref_name) {
    $result = db_query('SELECT field_name, data FROM {field_config} WHERE field_name = :name', array(':name' => $ref_name));
    foreach ($result as $record) {
      $data = unserialize($record->data);

      $data['settings']['handler_settings']['sort'] = [
        'type' => 'none',
      ];

      // Write settings back to the database.
      db_update('field_config')
        ->fields(
          array(
            'data' => serialize($data),
          )
        )
        ->condition('field_name', $ref_name, '=')
        ->execute();
    }
    drupal_flush_all_caches();
  }
