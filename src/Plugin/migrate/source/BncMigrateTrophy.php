<?php

namespace Drupal\bnc_migrate\Plugin\migrate\source;

use Drupal\migrate\Plugin\migrate\source\SqlBase;
use Drupal\Core\Database\Query\SelectInterface;
use Drupal\migrate\Row;

/**
 * Migrate Source plugin.
 * 
 * @MigrateSource(
 *   id = "bnc_migrate_trophy",
 *   source_module = "trophy"
 * )
 */
class BncMigrateTrophy extends SqlBase {

  /**
   * {@inheritdoc}
   */
  public function query(): SelectInterface {
    /**
     * An important point to note is that your query *must* return a single row
     * for each item to be imported. Here we might be tempted to add a join to
     * migrate_example_beer_topic_node in our query, to pull in the
     * relationships to our categories. Doing this would cause the query to
     * return multiple rows for a given node, once per related value, thus
     * processing the same node multiple times, each time with only one of the
     * multiple values that should be imported. To avoid that, we simply query
     * the base node data here, and pull in the relationships in prepareRow()
     * below.
     */
    return $this->select('trophy', 't')
                 ->fields('t', ['trophy_id', 'type', 'created', 'changed', 'uid',
                   'scorechart_id', 'awardid']);
  }

  /**
   * {@inheritdoc}
   */
  public function fields(): array {
    return [
      'trophy_id' => $this->t('ID of the trophy'),
      'type' => $this->t('Type of trophy'),
      'created' => $this->t('When the trophy was created'),
      'changed' => $this->t('When the trophy was last updated'),
      'uid' => $this->t('Account ID of the creator of the tophy'),
      'scorechart_id' => $this->t('ID of the scorechart'),
      'awardid' => $this->t('ID of the award'),
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getIds(): array {
    return [
      'trophy_id' => [
        'type' => 'integer',
        'alias' => 't',
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function prepareRow(Row $row): bool {
    return parent::prepareRow($row);
  }

}
