<?php
/**
 * @copyright Copyright (c) 2018 Code-Source
 */

if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}
$GLOBALS['TYPO3_CONF_VARS']['SYS']['fal']['onlineMediaHelpers']['googlemap'] = \CDSRC\CdsrcGmap\Helpers\GoogleMapHelper::class;

$rendererRegistry = \TYPO3\CMS\Core\Resource\Rendering\RendererRegistry::getInstance();
$rendererRegistry->registerRendererClass(
    \CDSRC\CdsrcGmap\Rendering\GoogleMapRenderer::class
);

$GLOBALS['TYPO3_CONF_VARS']['SYS']['FileInfo']['fileExtensionToMimeType']['googlemap'] = 'google/map';

$GLOBALS['TYPO3_CONF_VARS']['SYS']['mediafile_ext'] .= ',googlemap';
