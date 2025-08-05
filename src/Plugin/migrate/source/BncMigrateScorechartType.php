<?php

namespace Drupal\bnc_migrate\Plugin\migrate\source;

use Drupal\Core\Database\Query\SelectInterface;
use Drupal\migrate\Row;
use Drupal\migrate_drupal\Plugin\migrate\source\DrupalSqlBase;
use Drupal\migrate_drupal\Plugin\migrate\source\d7\FieldableEntity;

/**
 * Drupal 7 Node types source from database.
 *
 * For available configuration keys, refer to the parent classes.
 *
 * @see \Drupal\migrate\Plugin\migrate\source\SqlBase
 * @see \Drupal\migrate\Plugin\migrate\source\SourcePluginBase
 *
 * @MigrateSource(
 *   id = "bnc_migrate_scorechart_type",
 *   source_module = "trophy"
 * )
 */
class BncMigrateScorechartType extends DrupalSqlBase {
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
  public function prepareRow(Row $row): bool {
    $type = $row->getSourceProperty('type');
    $source_options = $this->variableGet('field_bundle_settings_scorechart__' . $type, []);
    var_dump($type);
    $options = [];
    foreach (['status', 'revision'] as $item) {
      $options[$item] = in_array($item, $source_options);
    }
    $row->setSourceProperty('options', $options);

    return parent::prepareRow($row);
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
}
