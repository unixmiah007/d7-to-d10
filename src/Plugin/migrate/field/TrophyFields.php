<?php

namespace Drupal\bnc_migrate\Plugin\migrate\field;

use Drupal\datetime\Plugin\Field\FieldType\DateTimeItemInterface;
use Drupal\migrate\Plugin\MigrationInterface;
use Drupal\migrate\MigrateException;
use Drupal\migrate_drupal\Plugin\migrate\field\FieldPluginBase;

/**
 * Provides a field plugin for date and time fields.
 *
 * @MigrateField(
 *   id = "bnc_trophy_fields",
 *   type_map = {
 *     "measurement_16" = "bc_trophy_measurement_16ths",
 *     "measurement_8"  = "bc_trophy_measurement_8ths",
 *     "measurement_rld"= "bc_trophy_measurement_right_left_diff",
 *   },
 *   source_module = "trophy",
 *   destination_module = "bc_trophy"
 * )
 */
class TrophyFields extends FieldPluginBase {

  /**
   * {@inheritdoc}
   */
  public function getFieldFormatterMap() {
    return [
      'measurement_rld_default' => 'bc_trophy_measurement_right_left_diff_default',
      'measurement_8_default' => 'bc_trophy_measurement_8ths_default',
      'measurement_16_default' => 'bc_trophy_measurement_16ths_default',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFieldWidgetMap() {
    return [
      'single_measurement' => 'bc_trophy_measurement_16ths',
      'group_measurement' => 'bc_trophy_measurement_right_left_diff',
    ];
  }
}
