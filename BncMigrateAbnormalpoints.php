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
 *   id = "scorechart_abnormalpoints",
 *   source_module = "trophy"
 * )
 */
class BncMigrateAbnormalpoints extends FieldableEntity {

  /**
   * {@inheritdoc}
   */
  public function query(): SelectInterface {
    return $this->select('field_data_abnormalpoints', 'fda')
                ->fields('fda', [
                  'entity_type',
                  'bundle',
                  'deleted',
                  'entity_id',
                  'revision_id',
                  'language',
                  'delta',
                  'abnormalpoints_right',
                  'abnormalpoints_left',
                ]);
  }

  /**
   * {@inheritdoc}
   */
  public function fields(): array {
    return [
      'entity_type' => $this->t('Entity Type'),
      'bundle' => $this->t('Bundle'),
      'deleted' => $this->t('Deleted'),
      'entity_id' => $this->t('Entity ID'),
      'revision_id' => $this->t('Revision ID'),
      'language' => $this->t('Language'),
      'delta' => $this->t('Delta'),
      'abnormalpoints_right' => $this->t('Abnormal Points Right'),
      'abnormalpoints_left' => $this->t('Abnormal Points Left'),
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

    // Combine fields for abnormalpoints_right and abnormalpoints_left
    $abnormalpoints_right = $row->getSourceProperty('abnormalpoints_right');
    $abnormalpoints_left = $row->getSourceProperty('abnormalpoints_left');

    $row->setSourceProperty('abnormalpoints_right', $abnormalpoints_right);
    $row->setSourceProperty('abnormalpoints_left', $abnormalpoints_left);

    foreach ($this->getFields('scorechart', $entity_id) as $field_name => $field) {
      // Ensure we're using the right language if the entity and the field are translatable.
      $row->setSourceProperty($field_name, $this->getFieldValues('scorechart', $field_name, $entity_id));
    }
    
    return parent::prepareRow($row);
  }

}