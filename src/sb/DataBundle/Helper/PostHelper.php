<?php

namespace sb\DataBundle\Helper;

/**
 * Class PostHelper
 * @package sb\DataBundle\Helper
 * @author Florian Weber <florian.weber.dd@icloud.com>
 */
class PostHelper
{
    /**
     * Generates a Slug from the Post Title
     *
     * @param string $title
     * @return string
     */
    public static function generateSlug($title)
    {
        return ltrim(rtrim(preg_replace('/[^\w\s]/', '-', preg_replace('/ /', '-', strtolower($title))), '-'), '-');
    }
}
