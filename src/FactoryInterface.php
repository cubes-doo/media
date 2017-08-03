<?php

namespace Cubes\Media;

/**
 * Interface FactoryInterface
 *
 * @package Cubes\Media
 */
interface FactoryInterface
{
    /**
     * Constant TYPE_YOUTUBE used as identifier for code logic.
     */
    const TYPE_YOUTUBE = 'youtube';

    /**
     * Constant TYPE_VIMEO used as identifier for code logic.
     */
    const TYPE_VIMEO   = 'vimeo';

    /**
     * Constant YOUTUBE_RGX used as youtube allowed regex pattern for youtube url.
     */
    const YOUTUBE_RGX  = '~^(?:https?://)?(?:www[.])?(?:youtube[.]com/watch[?]v=|youtu[.]be/)([^&]{11})~x';

    /**
     * Constant VIMEO_RGX used as vimeo allowed regex pattern for youtube url.
     */
    const VIMEO_RGX    = '/https?:\/\/(?:www\.|player\.)?vimeo.com\/(?:channels\/(?:\w+\/)?|groups\/([^\/'.
                         ']*)\/videos\/|album\/(\d+)\/video\/|video\/|)(\d+)(?:$|\/|\?)/';

    /**
     * Constant URL_RGX used as vimeo allowed regex pattern for youtube url.
     */
    const URL_RGX      = '((https?|ftp)\:\/\/)?([a-z0-9+!*(),;?&=\$_.-]+(\:[a-z0-9+!*(),;?&=\$_.-]+)?@)?'.
                         '([a-z0-9-.]*)\.([a-z]{2,3})(\:[0-9]{2,5})?(\/([a-z0-9+\$_-]\.?)+)*\/?(\?[a-z+&\$'.
                         '_.-][a-z0-9;:@&%=+\/\$_.-]*)?(#[a-z_.-][a-z0-9+\$_.-]*)?';
}