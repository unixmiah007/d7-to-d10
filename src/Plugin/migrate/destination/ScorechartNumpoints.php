<?php

namespace Drupal\bnc_migrate\Plugin\migrate\destination;

use Drupal\migrate\Plugin\migrate\destination\DestinationBase;
use Drupal\migrate\Row;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\migrate\Plugin\Migration;
use Psr\Log\LoggerInterface;

/**
 * Provides a destination plugin for importing into scorechart__field_abnormalpoints table.
 *
 * @MigrateDestination(
 *   id = "scorechart_field_numpoints"
 * )
 */
class ScorechartNumpoints extends DestinationBase {

  /**
   * The migration instance.
   *
   * @var \Drupal\migrate\Plugin\Migration
   */
  protected $migration;

  /**
   * The logger service.
   *
   * @var \Psr\Log\LoggerInterface
   */
  protected $logger;

  /**
   * Constructs a new ScorechartFieldAbnormalPoints.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin ID for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\migrate\Plugin\Migration $migration
   *   The migration instance.
   * @param \Psr\Log\LoggerInterface $logger
   *   The logger service.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, Migration $migration) {
    parent::__construct($configuration, $plugin_id, $plugin_definition, $migration);
    $this->migration = $migration;
    $this->logger = \Drupal::logger('bnc_migrate');
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('plugin.manager.migration')->createInstance($configuration['migration']), // Pass the correct migration instance
      $container->get('logger.factory')->get('default') // Inject the logger service
    );
  }

  /**
   * {@inheritdoc}
   */
  public function import(Row $row, array $old_destination_id_values = []) {
    try {
      // Prepare the data for insertion into the table.
      $data = [
        'entity_type' => $row->getSourceProperty('entity_type'),
        'bundle' => $row->getSourceProperty('bundle'),
        'deleted' => $row->getSourceProperty('deleted'),
        'entity_id' => $row->getSourceProperty('entity_id'),
        'revision_id' => $row->getSourceProperty('revision_id'),
        'langcode' => $row->getSourceProperty('language') ?? 'und', // Default to 'und' if langcode is null
        'delta' => $row->getSourceProperty('delta'),
        'field_numpoints_right' => $row->getSourceProperty('numpoints_right'),
        'field_numpoints_left' => $row->getSourceProperty('numpoints_left'),  
        'field_numpoints_total' => $row->getSourceProperty('numpoints_total'),
      ];

      // Log data before importing
      $this->logger->info('Importing row: @data', ['@data' => print_r($data, TRUE)]);

      // Update or insert the data into the table.
      $connection = \Drupal::database();
      $connection->merge('scorechart__field_numpoints')
        ->key([
          'entity_id' => $data['entity_id'],
          'delta' => $data['delta'],
          'langcode' => $data['langcode'] ?? 'und',
        ])
        ->fields($data)
        ->execute();

      // Log success message
      $this->logger->info('Successfully imported row with entity_id: @entity_id', ['@entity_id' => $data['entity_id']]);

      return [
        $data['entity_id'],
        $data['delta'],
        $data['langcode'],
      ];
    } catch (\Exception $e) {
      // Log error message
      $this->logger->error('Error importing row: @message', ['@message' => $e->getMessage()]);
      throw $e;
    }
  }

  /**
   * {@inheritdoc}
   */
  public function rollback(array $destination_identifier) {
    try {
      // Log rollback action
      $this->logger->info('Rolling back row with entity_id: @entity_id', ['@entity_id' => $destination_identifier['entity_id']]);

      // Remove data if a rollback is needed.
      $connection = \Drupal::database();
      $connection->delete('scorechart__field_numpoints')
        ->condition('entity_id', $destination_identifier['entity_id'])
        ->condition('delta', $destination_identifier['delta'])
        ->condition('langcode', $destination_identifier['langcode'])
        ->execute();

      // Log rollback success
      $this->logger->info('Successfully rolled back row with entity_id: @entity_id', ['@entity_id' => $destination_identifier['entity_id']]);
    } catch (\Exception $e) {
      // Log rollback error
      $this->logger->error('Error rolling back row: @message', ['@message' => $e->getMessage()]);
      throw $e;
    }
  }

  /**
   * {@inheritdoc}
   */
  public function fields() {
    // Define the fields expected by this destination.
    return [
      'entity_type' => $this->t('Entity type'),
      'bundle' => $this->t('Bundle'),
      'deleted' => $this->t('Deleted'),
      'entity_id' => $this->t('Entity ID'),
      'revision_id' => $this->t('Revision ID'),
      'langcode' => $this->t('Language code'),
      'delta' => $this->t('Delta'),
      'field_numpoints_right' => $this->t('numpoints Right'),
      'field_numpoints_left' => $this->t('numpoints Left'),
      'field_numpoints_total' => $this->t('numpoints total'),
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getIds() {
    // Define the primary key fields of the destination table.
    return [
      'entity_id' => ['type' => 'integer'],
      'delta' => ['type' => 'integer'],
      'langcode' => ['type' => 'string'],
    ];
  }
}