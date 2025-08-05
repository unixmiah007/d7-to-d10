<?php

namespace Drupal\bnc_migrate\Plugin\migrate\source;

use Drupal\migrate_drupal\Plugin\migrate\source\d7\FieldableEntity;
use Drupal\Core\Database\Query\SelectInterface;
use Drupal\migrate\Row;

ini_set("memory_limit", "-1");

/**
 * Migrate Source plugin for Abnormal Points.
 *
 * @MigrateSource(
 *   id = "scorechart_field_g9",
 *   source_module = "trophy"
 * )
 */
class BncMigrateG9 extends FieldableEntity {

  /**
   * {@inheritdoc}
   */
  public function query(): SelectInterface {
    return $this->select('field_data_g9', 'fda')
                ->fields('fda', [
                  'entity_type',
                  'bundle',
                  'deleted',
                  'entity_id',
                  'revision_id',
                  'language',
                  'delta',
                  'g9_right',
                  'g9_left',
                  'g9_difference',
                ]);
  }

  /**
   * {@inheritdoc}
   */
  public function fields(): array {
    return [
      'entity_type' => $row->getSourceProperty('entity_type'),
      'bundle' => $row->getSourceProperty('bundle'),
      'deleted' => $row->getSourceProperty('deleted'),
      'entity_id' => $row->getSourceProperty('entity_id'),
      'revision_id' => $row->getSourceProperty('revision_id'),
      'langcode' => $row->getSourceProperty('language') ?? 'und', // Default to 'und' if langcode is null
      'delta' => $row->getSourceProperty('delta'),
      'field_g9_right' => $row->getSourceProperty('g9_right'),
      'field_g9_left' => $row->getSourceProperty('g9_left'),
      'field_g9_difference' => $row->getSourceProperty('g9_difference'),
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getIds(): array {
    return [
      'entity_id' => [
        'type' => 'integer',
        'alias' => 'fda',
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function prepareRow(Row $row): bool {
    $entity_id = $row->getSourceProperty('entity_id');
    $delta = $row->getSourceProperty('delta');
    $revision_id = $row->getSourceProperty('revision_id');

    foreach ($this->getFields('scorechart', $entity_id) as $field_name => $field) {
      // Ensure we're using the right language if the entity and the field are translatable.
      $row->setSourceProperty($field_name, $this->getFieldValues('scorechart', $field_name, $entity_id));
    }

    return parent::prepareRow($row);
  }

}