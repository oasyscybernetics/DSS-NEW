<?php
/*
 * Oasys Digital Signage
 * 
 */

namespace Xibo\Widget;

/**
 * Class GenericFile
 * @package Xibo\Widget
 */
class GenericFile extends ModuleWidget
{
    /** @inheritdoc */
    public function edit()
    {
        // Non-editable
    }

    /**
     * Preview code for a module
     * @param int $width
     * @param int $height
     * @param int $scaleOverride The Scale Override
     * @return string The Rendered Content
     */
    public function preview($width, $height, $scaleOverride = 0)
    {
        // Videos are never previewed in the browser.
        return $this->previewIcon();
    }

    /**
     * Get Resource
     * @param int $displayId
     * @return mixed
     */
    public function getResource($displayId = 0)
    {
        if (ini_get('zlib.output_compression')) {
            ini_set('zlib.output_compression', 'Off');
        }

        $this->download();
    }

    /**
     * Is this module valid
     * @return int
     */
    public function isValid()
    {
        // Yes
        return 1;
    }
}
