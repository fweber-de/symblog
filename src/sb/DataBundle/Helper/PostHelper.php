<?php

namespace sb\DataBundle\Helper;

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
        return preg_replace('/[^\w\s]/', '-', preg_replace('/ /', '-', strtolower($title)));
    }
}
