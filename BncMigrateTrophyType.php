<?php

namespace Drupal\bnc_migrate\Plugin\migrate\source;

use Drupal\Core\Database\Query\SelectInterface;
use Drupal\migrate\Row;  
use Drupal\migrate_drupal\Plugin\migrate\source\d7\FieldableEntity;

/**
 * Migrate Source plugin.
 *
 * @MigrateSource(
 *   id = "bnc_migrate_trophy_type",
 *   source_module = "trophy"
 * )
 */
class BncMigrateTrophyType extends FieldableEntity {

  /**
   * {@inheritdoc}
   */
  public function query(): SelectInterface {
    return $this->select('trophy_type', 'tt')
                 ->fields('tt', ['id', 'type', 'label', 'awardid', 'weight',
                   'status']);
  }

  /**
   * {@inheritdoc}
   */
  public function fields(): array {
    return [
      'id' => $this->t('ID'),
      'type' => $this->t('Machine name of Trophy Type'),
      'label' => $this->t('Label of Trophy Type'),
      'awardid' => $this->t('Award ID'),
      'weight' => $this->t('Order of how it appears in list'),
      'status' => $this->t('Published status'),
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getIds(): array {
    return [
      'id' => [
        'type' => 'integer',
        'alias' => 'tt',
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function prepareRow(Row $row): bool {
    $nid = $row
      ->getSourceProperty('nid');
    $type = $row
      ->getSourceProperty('type');
    var_dump($type);

    foreach ($this
      ->getFields('scorechart', $type) as $field_name => $field) {

      // Ensure we're using the right language if the entity and the field are
      // translatable.
      $row
        ->setSourceProperty($field_name, $this
          ->getFieldValues('trophy_type', $field_name, $nid));
    }
    return parent::prepareRow($row);
  }

}
