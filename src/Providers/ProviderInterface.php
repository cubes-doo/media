<?php

namespace Cubes\Media\Providers;

/**
 * Interface ProviderInterface
 *
 * @property null|string  thumbnail
 * @property null|string  thumbnailSize
 * @property null|string  authorName
 * @property null|string  authorChannelUrl
 * @property null|string  title
 * @property null|string  description
 * @property null|string  iframe
 * @property null|string  iframeSize
 * @property array        tags
 *
 * @method toArray
 * @method toJson
 * @method isJson
 *
 * @package Cubes\Media\Providers
 */
interface ProviderInterface
{
    /**
     * @return array
     */
    public function getAll();

    /**
     * @return string
     */
    public function getAuthorName();

    /**
     * @return string
     */
    public function getAuthorChannelUrl();

    /**
     * @return string
     */
    public function getThumbnail();

    /**
     * @return string
     */
    public function getThumbnailSize();

    /**
     * @return string
     */
    public function getTitle();

    /**
     * @return string
     */
    public function getDescription();

    /**
     * @return string
     */
    public function getIframe();

    /**
     * @param  string|array $size
     * @return string
     */
    public function setIframeSize($size);

    /**
     * @return array
     */
    public function getTags();
}