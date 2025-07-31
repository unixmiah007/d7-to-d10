<?php
namespace Drupal\bnc_migrate\Plugin\migrate\source;

use Drupal\migrate_drupal\Plugin\migrate\source\d7\FieldableEntity;
use Drupal\Core\Database\Query\SelectInterface;
use Drupal\migrate\Row;

ini_set("memory_limit", "-1");
/**
 * Migrate Source plugin.
 *
 * @MigrateSource(
 *   id = "bnc_migrate_scorechart",
 *   source_module = "trophy"
 * )
 */
class BncMigrateScorechart extends FieldableEntity {

  /**
   * {@inheritdoc}
   */
  public function query(): SelectInterface {
    return $this->select('scorechart', 'sc')
                   ->fields('sc', ['scorechart_id', 'trophy_id', 'type', 'awardid', 'created', 'changed', 'uid', 'trophy_state']);
  }

  /**
   * {@inheritdoc}
   */
  public function fields(): array {
    return [
      'scorechart_id' => $this->t('Scorechart ID'),
      'trophy_id' => $this->t('ID of the Trophy this scorechart is associated with'),
      'type' => $this->t('machine name of trophy type stored in Trophy Types'),
      'awardid' => $this->t('Text code for the award ID'),
      'created' => $this->t('Date when the scorechart was created'),
      'changed' => $this->t('Date when the scorechart was last updated'),
      'uid' => $this->t('Image path'),
      'trophy_state' => $this->t('Integer reference of the current trophy workflow state'),
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getIds(): array {
    return [
      'scorechart_id' => [
        'type' => 'integer',
        'alias' => 'sc',
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function prepareRow(Row $row): bool {
    $nid = $row->getSourceProperty('nid');

    foreach ($this->getFields('scorechart', $nid) as $field_name => $field) {
      // Ensure we're using the right language if the entity and the field are translatable.
      $row->setSourceProperty($field_name, $this->getFieldValues('scorechart', $field_name, $nid));
    }

    return parent::prepareRow($row);
  }

}
