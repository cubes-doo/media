<?php

namespace Cubes\Media\Providers;

use Cubes\Media\AbstractMedia;
use Cubes\Media\Url\IdentifierTrait;

/**
 * Class Vimeo
 *
 * @package Cubes\Media\Providers
 */
class Vimeo extends AbstractMedia implements ProviderInterface
{
    use IdentifierTrait;

    /**
     * Constant from vimeo api for REST fetching.
     */
    const VIMEO_API_URL = 'http://vimeo.com/api/v2/video/';

    /**
     * String of vimeo video/playlist url.
     *
     * @var string
     */
    protected $url;

    /**
     * Id of vimeo video.
     *
     * @var string
     */
    protected $video_id;

    /**
     * Vimeo constructor.
     *
     * @param null $url
     * @param array $config
     */
    public function __construct($url, array $config)
    {
        $this->url = $this->normalizeUrl($url);
        $this->setConfig($config);
        $this->init();
        $this->data = $this->getAll();
        $this->setVideoId();
    }

    /**
     * Method init used to initialize all required config parameters and bind it to object,
     * so we can work with Vimeo Api's.
     *
     * @return \Cubes\Media\Providers\Vimeo
     */
    protected function init()
    {
        $config = $this->getConfig();
        $this->service = new \Vimeo\Vimeo(
            $config['client_id'], $config['client_secret']
        );
    }

    /**
     * Method getAll returns array of all video metadata values.
     *
     * @return array
     */
    public function getAll()
    {
        $data = unserialize(file_get_contents($this->url));
        return $this->rebuildData($data[0]);
    }

    /**
     * Method rebuildData used to rebuild fetched data to match the required array index naming and order,
     * so we can work with \ArrayAccess interface and __get/__set/__unset magic methods.
     *
     * data array format:
     * $data = [
     *     'thumbnail'        => '',
     *     'thumbnailSize'    => '',
     *     'authorName'       => '',
     *     'authorChannelUrl' => '',
     *     'title'            => '',
     *     'description'      => '',
     *     'iframe'           => '',
     *     'iframeSize'       => '',
     *     'tags'             => ''
     * ]
     *
     * @param  $data
     * @return array
     */
    protected function rebuildData($data)
    {
        // If data is json convert it to array.
        if ($this->isJson($data)) {
            $data = $this->toArray($data);
        }

        // Rebuild array to corresponding format.
        $rebuiltedData = [
            'id' => $data['id'],
            'thumbnail' => $data['thumbnail_large'],
            'thumbnailSize' => '',
            'authorName' => $data['user_name'],
            'authorChannelUrl' => $data['user_url'],
            'title' => $data['title'],
            'description' => $data['description'],
            'iframe' => '',
            'iframeSize' => '',
            'tags' => ''
        ];

        // Return rebuilted data.
        return $rebuiltedData;
    }

    /**
     * Returns video author name.
     *
     * @return mixed
     */
    public function getAuthorName()
    {
        return $this->data['authorName'];
    }

    /**
     * Returns author channel url.
     *
     * @return string
     */
    public function getAuthorChannelUrl()
    {
        return $this->data['authorChannelUrl'];
    }

    /**
     * Returns thumbnail url.
     *
     * @return string
     */
    public function getThumbnail()
    {
        return $this->data['thumbnail'];
    }

    /**
     * Returns size of thumbnail from data.
     *
     * @return string
     */
    public function getThumbnailSize()
    {
        // TODO: Implement logic.
        return 'Not implemented yet for Vimeo provider.';
    }

    /**
     * Returns title of video.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->data['title'];
    }

    /**
     * Returns description of video.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->data['description'];
    }

    /**
     * Returns iframe of video in html format.
     *
     * @return string
     */
    public function getIframe()
    {
        // TODO: Implement logic.
        return 'Not implemented yet for Vimeo provider.';
    }

    /**
     * Sets iframe size so you can then call getIframe with dynamic width and height.
     *
     * @param  array|string $size
     * @return string
     */
    public function setIframeSize($size)
    {
        // TODO: Implement logic.
        return 'Not implemented yet for Vimeo provider.';
    }

    /**
     * Returns array of video tags.
     *
     * @return mixed
     */
    public function getTags()
    {
        // TODO: Implement logic.
        return 'Not implemented yet for Vimeo provider.';
    }

    /**
     * Method setVideoId used to set video_id property from vimeo url parsed.
     *
     * @return $this
     */
    public function setVideoId()
    {
        $this->video_id = $this->data['id'];
    }

    /**
     * Method getVideoId used to fetch video id from parsed url.
     *
     * @return mixed
     */
    public function getVideoId()
    {
        return $this->video_id;
    }

    /**
     * Method used to normalize last character of url if not exist.
     *
     * @param  $url
     * @return string
     */
    private function normalizeUrl($url)
    {
        if (substr($url, -1) == '/') {
            $url = rtrim($url, '/');
        }

        $normalizedUrl = str_replace('https://vimeo.com/', 'http://vimeo.com/api/v2/video/', $url) . '.php';
        return $normalizedUrl;
    }
}