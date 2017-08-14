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
        $this->url = $url;
        $this->setVideoId();
        $this->setConfig($config);
        $this->init();
        $this->data = $this->getAll();
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
            $config->vimeo->client_id, $config->vimeo->client_secret
        );

        $token = $this->service->clientCredentials();
        $this->service->setToken($token['body']['access_token']);
    }

    /**
     * Method getAll returns array of all video metadata values.
     *
     * @return array
     */
    public function getAll()
    {
        $data = $this->service->request('/videos/'.$this->getVideoId())['body'];
        return $this->rebuildData($data);
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
            'id' => $this->getVideoId(),
            'thumbnail' => $data['pictures']['sizes'][3]['link'],
            'thumbnailSize' => $data['pictures']['sizes'][3],
            'authorName' => $data['user']['name'],
            'authorChannelUrl' => $data['user']['link'],
            'title' => $data['name'],
            'description' => $data['description'],
            'iframe' => $data['embed']['html'],
            'iframeSize' => '',
            'tags' => $data['tags']
        ];

        // Return rebuilted data.
        return $rebuiltedData;
    }

    /**
     * Method setVideoId used to set video_id property from the parsed vimeo URl.
     *
     * @return $this
     */
    public function setVideoId()
    {
        $this->video_id = (int) substr(parse_url($this->url, PHP_URL_PATH), 1);
    }
}