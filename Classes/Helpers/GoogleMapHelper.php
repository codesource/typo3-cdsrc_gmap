<?php
/**
 * @copyright Copyright (c) 2018 Code-Source
 */

namespace CDSRC\CdsrcGmap\Helpers;


use TYPO3\CMS\Core\Resource\File;
use TYPO3\CMS\Core\Resource\Folder;
use TYPO3\CMS\Core\Resource\OnlineMedia\Helpers\AbstractOnlineMediaHelper;

class GoogleMapHelper extends AbstractOnlineMediaHelper
{

    protected $urlTypes = [
        'default' => [
            'regexp' => '%google\.com/maps/embed\?pb=([^&"]*)%i',
            'url' => 'https://www.google.com/maps/embed?pb=%s',
        ],
        'shorten' => [
            'regexp' => '%goo\.gl/maps/([^\?"]*)%i',
            'url' => 'https:/goo.gl/maps/%s',
        ],
        'my-map' => [
            'regexp' => '%google\.com/maps/d/u/0/embed\?mid=([^&"]*)%i',
            'url' => 'https://www.google.com/maps/d/u/0/embed?mid=%s',
        ],
    ];

    /**
     * Get public url
     *
     * Return NULL if you want to use core default behaviour
     *
     * @param File $file
     * @param bool $relativeToCurrentScript
     *
     * @return string|null
     */
    public function getPublicUrl(File $file, $relativeToCurrentScript = false)
    {
        list($type, $googleMapId) = explode('||', $this->getOnlineMediaId($file));

        if (!isset($this->urlTypes[$type])) {
            return null;
        }

        return sprintf($this->urlTypes[$type]['url'], $googleMapId);
    }

    /**
     * Try to transform given URL to a File
     *
     * @param string $url
     * @param Folder $targetFolder
     *
     * @return File|null
     */
    public function transformUrlToFile($url, Folder $targetFolder)
    {
        $googleMapId = null;
        $googleMapType = null;
        foreach ($this->urlTypes as $type => $configuration) {
            if (preg_match($configuration['regexp'], $url, $match)) {
                $googleMapId = $match[1];
                $googleMapType = $type;
                break;
            }
        }
        if (empty($googleMapId)) {
            return null;
        }

        $mediaId = $googleMapType . '||' . $googleMapId;
        $file = $this->findExistingFileByOnlineMediaId($mediaId, $targetFolder, $this->extension);
        if ($file) {
            return $file;
        }

        if (preg_match("/\<title.*\>(.*)\<\/title\>/isU", file_get_contents($url), $matches)) {
            $fileName = $matches[1];
        } else {
            $fileName = $googleMapId;
        }

        return $this->createNewFile($targetFolder, $fileName . '.' . $this->extension, $mediaId);
    }

    /**
     * Get local absolute file path to preview image
     *
     * Return an empty string when no preview image is available
     *
     * @param File $file
     *
     * @return string
     */
    public function getPreviewImage(File $file)
    {
        return '';
    }

    /**
     * Get meta data for OnlineMedia item
     *
     * See $GLOBALS[TCA][sys_file_metadata][columns] for possible fields to fill/use
     *
     * @param File $file
     *
     * @return array with metadata
     */
    public function getMetaData(File $file)
    {
        return [];
    }
}
