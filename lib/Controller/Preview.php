<?php
/*
 * Oasys Digital Signage
 * 
 * 
 */
namespace Xibo\Controller;

use baseDAO;
use database;
use Xibo\Exception\AccessDeniedException;
use Xibo\Factory\LayoutFactory;
use Xibo\Service\ConfigServiceInterface;
use Xibo\Service\DateServiceInterface;
use Xibo\Service\LogServiceInterface;
use Xibo\Service\SanitizerServiceInterface;

/**
 * Class Preview
 * @package Xibo\Controller
 */
class Preview extends Base
{
    /**
     * @var LayoutFactory
     */
    private $layoutFactory;

    /**
     * Set common dependencies.
     * @param LogServiceInterface $log
     * @param SanitizerServiceInterface $sanitizerService
     * @param \Xibo\Helper\ApplicationState $state
     * @param \Xibo\Entity\User $user
     * @param \Xibo\Service\HelpServiceInterface $help
     * @param DateServiceInterface $date
     * @param ConfigServiceInterface $config
     * @param LayoutFactory $layoutFactory
     */
    public function __construct($log, $sanitizerService, $state, $user, $help, $date, $config, $layoutFactory)
    {
        $this->setCommonDependencies($log, $sanitizerService, $state, $user, $help, $date, $config);

        $this->layoutFactory = $layoutFactory;
    }

    /**
     * Layout Preview
     * @param int $layoutId
     */
    public function show($layoutId)
    {
        $layout = $this->layoutFactory->getById($layoutId);

        if (!$this->getUser()->checkViewable($layout))
            throw new AccessDeniedException();

        $this->getState()->template = 'layout-preview';
        $this->getState()->setData([
            'layout' => $layout,
            'previewOptions' => [
                'getXlfUrl' => $this->urlFor('layout.getXlf', ['id' => $layout->layoutId]),
                'getResourceUrl' => $this->urlFor('module.getResource'),
                'libraryDownloadUrl' => $this->urlFor('library.download'),
                'layoutBackgroundDownloadUrl' => $this->urlFor('layout.download.background'),
                'loaderUrl' => $this->getConfig()->uri('img/loader.gif')
            ]
        ]);
    }

    /**
     * Get the XLF for a Layout
     * @param $layoutId
     */
    function getXlf($layoutId)
    {
        $layout = $this->layoutFactory->concurrentRequestLock($this->layoutFactory->getById($layoutId));
        try {
            if (!$this->getUser()->checkViewable($layout)) {
                throw new AccessDeniedException();
            }

            echo file_get_contents($layout->xlfToDisk([
                'notify' => false,
                'collectNow' => false,
            ]));

            $this->setNoOutput(true);

        } finally {
            // Release lock
            $this->layoutFactory->concurrentRequestRelease($layout);
        }
    }
}
