<?php

namespace Cubes\Media\Providers;

use Cubes\Media\AbstractMedia;
use Cubes\Media\Url\IdentifierTrait;

/**
 *
 * Class Youtube
 *
 * @package Cubes\Media\Providers
 */
class Youtube extends AbstractMedia implements ProviderInterface
{
    use IdentifierTrait;

    /**
     * Constant used as from youtube v3 api for REST fetching.
     */
    const YOUTUBE_API_URL = 'http://www.youtube.com/oembed?url={{replace}}&format=json';

    /**
     * String of youtube video/playlist url.
     *
     * @var string
     */
    protected $url;

    /**
     * Id of youtube video.
     *
     * @var string
     */
    protected $video_id;

    /**
     * Youtube constructor.
     *
     * @param null $url
     * @param array $config
     */
    public function __construct($url, array $config)
    {
        $this->url = $url;
        $this->setConfig($config);
        $this->video_id = $this->setVideoId($this->url);
        $this->init();
        $this->data = $this->getAll();
    }

    /**
     * Method init used to initialize all required config parameters and bind it to object,
     * so we can work with Google Youtube Api's.
     *
     * @return \Cubes\Media\Providers\Youtube
     */
    protected function init()
    {
        $client = new \Google_Client();
        $client->setDeveloperKey($this->config->youtube->api_key);
        $this->service = new \Google_Service_YouTube($client);
    }

    /**
     * Method getAll returns array of all video metadata values.
     *
     * @return array
     */
    public function getAll()
    {
        $data = $this->getVideoMetadata();
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
            'thumbnail' => $data['thumbnail_url'],
            'thumbnailSize' => [
                'width'  => $data['thumbnail_width'],
                'height' => $data['thumbnail_height']
            ],
            'authorName' => $data['author_name'],
            'authorChannelUrl' => $data['author_url'],
            'title' => $data['title'],
            'description' => $data['description'],
            'iframe' => $data['html'],
            'iframeSize' => '',
            'tags' => $data['tags']
        ];

        // Return rebuilted data.
        return $rebuiltedData;
    }

    /**
     * Method setVideoId used to parse video url and fetch id from it.
     *
     * @param  string $url
     * @return $this
     */
    public function setVideoId($url)
    {
        parse_str(parse_url($url, PHP_URL_QUERY), $output);
        return $this->video_id = $output['v'];
    }
    
    /**
     * Method getVideoMetadata used to fetch metadata for specific video id.
     *
     * @return array
     */
    private function getVideoMetadata()
    {
        $url = str_replace('{{replace}}', $this->url, self::YOUTUBE_API_URL);
        $output = $this->toArray(file_get_contents($url));

        $response = $this->service->videos->listVideos('snippet', [
            'id' => $this->getVideoId()
        ]);

        $flattenedResponse = $response['items'][0]['snippet'];
        if (!empty($flattenedResponse)) {
            $output['description'] = $flattenedResponse['description'];
            $output['tags'] = $flattenedResponse['tags'];
        }

        return $output;
    }
}