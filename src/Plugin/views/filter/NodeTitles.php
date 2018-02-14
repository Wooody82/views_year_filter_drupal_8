<?php

/**
 * @file
 * Definition of Drupal\d8views\Plugin\views\filter\NodeTitles.
 */

namespace Drupal\d8views\Plugin\views\filter;

use Drupal\views\Plugin\views\display\DisplayPluginBase;
use Drupal\views\Plugin\views\filter\InOperator;
use Drupal\views\ViewExecutable;
/**
 * Filters by given list of node title options.
 *
 * @ingroup views_filter_handlers
 *
 * @ViewsFilter("d8views_node_titles")
 */
class NodeTitles extends InOperator {

  private static $content_type = '';
  /**
   * {@inheritdoc}
   */
  public function init(ViewExecutable $view, DisplayPluginBase $display, array &$options = NULL) {

    //kint($view->storage->id());
    // Get the current content type to fetch right contents  
    // Views ids like = insights_list, press, financial_press_page, report, financial_report_page
    if($view->storage->id() == 'insights_list'){
      self::$content_type = 'insights';
    }
    elseif($view->storage->id() == 'press' || $view->storage->id() == 'financial_press_page'){
      self::$content_type = 'press';
    }elseif($view->storage->id() == 'report' || $view->storage->id() == 'financial_report_page' ){
      self::$content_type = 'reports';
    }


    parent::init($view, $display, $options);
    $this->valueTitle = t('Allowed node titles');

    $query = \Drupal::entityQuery('node')
    ->condition('status', 1)
    ->condition('type', self::$content_type)
    ->sort('created');

    $nodes_ids = $query->execute();
    if($nodes_ids) {
      $this->definition['options callback'] = array($this, 'generateOptions');
    }


  }

  /**
   * Override the query so that no filtering takes place if the user doesn't
   * select any options.
   */
  public function query() {
    if (!empty($this->value)) {
      parent::query();
    }
  }

  /**
   * Skip validation if no options have been chosen so we can use it as a
   * non-filter.
   */
  public function validate() {
    if (!empty($this->value)) {
      parent::validate();
    }
  }


  /**
   * Helper function that generates the options.
   * @return array
   */
  public function generateOptions() {
    // Array keys are used to compare with the table field values.

    //Query to get nodes id from insights,press or reports content type
     $query = \Drupal::entityQuery('node')
      ->condition('status', 1)
      ->condition('type', self::$content_type)
      ->sort('created','DESC');

    $nodes_ids = $query->execute();

    if ($nodes_ids) {
      foreach ($nodes_ids as $nodes_id) {
          $nodes_all[] = \Drupal\node\Entity\Node::load($nodes_id);
      }

      foreach ($nodes_all as $node) {
        $years[$node->field_year->value] = $node->field_year->value;
      }


    //Query to get nodes id from insights content type  --- Testing with Custom date field.
    // $query = \Drupal::entityQuery('node')
    //   ->condition('status', 1)
    //   ->condition('type', 'insights')
    //   ->sort('created');

    // $nodes_ids = $query->execute();

    // foreach ($nodes_ids as $nodes_id) {
    //     $nodes_all[] = \Drupal\node\Entity\Node::load($nodes_id);
    // }

    // foreach ($nodes_all as $node) {
    //   $years[$node->field_d->value] = $node->field_d->value;
    //   $parts = explode("-", $node->field_d->value);
    //   $year[$parts[0]] = $parts[0];
    // }


    return $years;
  }


}

}
