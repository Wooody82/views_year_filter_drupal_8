<?php

/**
 * Implements hook_views_data_alter().
 */
function d8views_views_data_alter(array &$data) {

  $data['node__field_year']['year_filter'] = array(
    'title' => t('Year filter'),
    'group' => t('Content'),
    'filter' => array(
      'title' => t('Year filter'),
      'help' => t('Year filter for Insights,Press and report content type. create Year text field (field_year) to use it as filter. '),
      'field' => 'field_year_value',
      'id' => 'd8views_node_titles'
    ),
  );

}
