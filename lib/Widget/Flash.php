<?php
/*
 * Oasys Digital Signage
 * 
 */

namespace Xibo\Widget;

/**
 * Class Flash
 * @package Xibo\Widget
 */
class Flash extends ModuleWidget
{

    /** @inheritdoc */
    public function layoutDesignerJavaScript()
    {
        // We use the same javascript as the data set view designer
        return 'flash-designer-javascript';
    }

    /** @inheritdoc */
    public function editForm()
    {
        return 'generic-form-edit';
    }

    /** @inheritdoc */
    public function edit()
    {
        $this->setDuration($this->getSanitizer()->getInt('duration', $this->getDuration()));
        $this->setUseDuration($this->getSanitizer()->getCheckbox('useDuration'));
        $this->setOption('name', $this->getSanitizer()->getString('name'));
        $this->setOption('enableStat', $this->getSanitizer()->getString('enableStat'));
        $this->saveWidget();
    }

    /** @inheritdoc */
    public function previewAsClient($width, $height, $scaleOverride = 0)
    {
        return $this->previewIcon();
    }

    /**
     * Get Resource
     * @param int $displayId
     * @return mixed
     */
    public function getResource($displayId = 0)
    {
        $this->download();
    }

    /** @inheritdoc */
    public function isValid()
    {
        return self::$STATUS_VALID;
    }
}
