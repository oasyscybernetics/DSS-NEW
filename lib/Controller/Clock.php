<?php
/*
 * Oasys Digital Signage
 * 
 * 
 */
namespace Xibo\Controller;


use Xibo\Helper\Session;
use Xibo\Service\ConfigServiceInterface;
use Xibo\Service\DateServiceInterface;
use Xibo\Service\LogServiceInterface;
use Xibo\Service\SanitizerServiceInterface;

/**
 * Class Clock
 * @package Xibo\Controller
 */
class Clock extends Base
{
    /**
     * @var Session
     */
    private $session;

    /**
     * Set common dependencies.
     * @param LogServiceInterface $log
     * @param SanitizerServiceInterface $sanitizerService
     * @param \Xibo\Helper\ApplicationState $state
     * @param \Xibo\Entity\User $user
     * @param \Xibo\Service\HelpServiceInterface $help
     * @param DateServiceInterface $date
     * @param ConfigServiceInterface $config
     * @param Session $session
     */
    public function __construct($log, $sanitizerService, $state, $user, $help, $date, $config, $session)
    {
        $this->setCommonDependencies($log, $sanitizerService, $state, $user, $help, $date, $config);

        $this->session = $session;
    }

    /**
     * Gets the Time
     *
     * @SWG\Get(
     *  path="/clock",
     *  operationId="clock",
     *  tags={"misc"},
     *  description="The Time",
     *  summary="The current CMS time",
     *  @SWG\Response(
     *      response=200,
     *      description="successful response",
     *      @SWG\Schema(
     *          type="object",
     *          additionalProperties={"title":"time", "type":"string"}
     *      )
     *  )
     * )
     *
     * @throws \Exception
     */
    public function clock()
    {
        $this->session->refreshExpiry = false;

        if ($this->getApp()->request()->isAjax() || $this->isApi()) {
            $output = $this->getDate()->getLocalDate(null, 'H:i T');

            $this->getState()->setData(array('time' => $output));
            $this->getState()->html = $output;
            $this->getState()->clockUpdate = true;
            $this->getState()->success = true;
        } else {
            $this->setNoOutput(true);
            echo $this->getDate()->getLocalDate(null, 'c');
        }
    }
}
