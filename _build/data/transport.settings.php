<?php
/**
 * Loads system settings into build
 *
 * @package fastfield
 * @subpackage build
 */
$settings = array();

$settings['parser_class']= $modx->newObject('modSystemSetting');
$settings['parser_class']->fromArray(array(
    'key' => 'parser_class',
    'value' => 'fastFieldParser',
    'xtype' => 'textfield',
    'namespace' => 'core',
    'area' => 'system',
),'',true,true);

$settings['parser_class_path']= $modx->newObject('modSystemSetting');
$settings['parser_class_path']->fromArray(array(
    'key' => 'parser_class_path',
    'value' => '{core_path}components/fastfield/model/fastfield/',
    'xtype' => 'textfield',
    'namespace' => 'core',
    'area' => 'system',
),'',true,true);

return $settings;