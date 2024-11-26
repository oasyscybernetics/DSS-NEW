<?php
/*
 * Oasys Digital Signage
 * 
 * 
 */
namespace Xibo\Controller;

use baseDAO;
use Xibo\Exception\AccessDeniedException;
use Xibo\Factory\TransitionFactory;
use Xibo\Helper\Form;
use Xibo\Service\ConfigServiceInterface;
use Xibo\Service\DateServiceInterface;
use Xibo\Service\LogServiceInterface;
use Xibo\Service\SanitizerServiceInterface;

/**
 * Class Transition
 * @package Xibo\Controller
 */
class Transition extends Base
{
    /**
     * @var TransitionFactory
     */
    private $transitionFactory;

    /**
     * Set common dependencies.
     * @param LogServiceInterface $log
     * @param SanitizerServiceInterface $sanitizerService
     * @param \Xibo\Helper\ApplicationState $state
     * @param \Xibo\Entity\User $user
     * @param \Xibo\Service\HelpServiceInterface $help
     * @param DateServiceInterface $date
     * @param ConfigServiceInterface $config
     * @param TransitionFactory $transitionFactory
     *
     */
    public function __construct($log, $sanitizerService, $state, $user, $help, $date, $config, $transitionFactory)
    {
        $this->setCommonDependencies($log, $sanitizerService, $state, $user, $help, $date, $config);

        $this->transitionFactory = $transitionFactory;
    }

    /**
     * No display page functionaility
     */
    function displayPage()
    {
        $this->getState()->template = 'transition-page';
    }

    public function grid()
    {
        $filter = [
            'transition' => $this->getSanitizer()->getString('transition'),
            'code' => $this->getSanitizer()->getString('code'),
            'availableAsIn' => $this->getSanitizer()->getInt('availableAsIn'),
            'availableAsOut' => $this->getSanitizer()->getInt('availableAsOut')
        ];

        $transitions = $this->transitionFactory->query($this->gridRenderSort(), $this->gridRenderFilter($filter));

        foreach ($transitions as $transition) {
            /* @var \Xibo\Entity\Transition $transition */

            // If the module config is not locked, present some buttons
            if ($this->getConfig()->getSetting('TRANSITION_CONFIG_LOCKED_CHECKB') != 1 && $this->getConfig()->getSetting('TRANSITION_CONFIG_LOCKED_CHECKB') != 'Checked' ) {

                // Edit button
                $transition->buttons[] = array(
                    'id' => 'transition_button_edit',
                    'url' => $this->urlFor('transition.edit.form', ['id' => $transition->transitionId]),
                    'text' => __('Edit')
                );
            }
        }

        $this->getState()->template = 'grid';
        $this->getState()->recordsTotal = $this->transitionFactory->countLast();
        $this->getState()->setData($transitions);
    }

    /**
     * Transition Edit Form
     * @param int $transitionId
     * @throws \Xibo\Exception\NotFoundException
     */
    public function editForm($transitionId)
    {
        if ($this->getConfig()->getSetting('TRANSITION_CONFIG_LOCKED_CHECKB') == 1 || $this->getConfig()->getSetting('TRANSITION_CONFIG_LOCKED_CHECKB') == 'Checked')
            throw new AccessDeniedException(__('Transition Config Locked'));

        $transition = $this->transitionFactory->getById($transitionId);

        $this->getState()->template = 'transition-form-edit';
        $this->getState()->setData([
            'transition' => $transition,
            'help' => $this->getHelp()->link('Transition', 'Edit')
        ]);
    }

    /**
     * Edit Transition
     * @param int $transitionId
     */
    public function edit($transitionId)
    {
        if ($this->getConfig()->getSetting('TRANSITION_CONFIG_LOCKED_CHECKB') == 1 || $this->getConfig()->getSetting('TRANSITION_CONFIG_LOCKED_CHECKB') == 'Checked')
            throw new AccessDeniedException(__('Transition Config Locked'));

        $transition = $this->transitionFactory->getById($transitionId);
        $transition->availableAsIn = $this->getSanitizer()->getCheckbox('availableAsIn');
        $transition->availableAsOut = $this->getSanitizer()->getCheckbox('availableAsOut');
        $transition->save();

        $this->getState()->hydrate([
            'message' => sprintf(__('Edited %s'), $transition->transition),
            'id' => $transition->transitionId,
            'data' => $transition
        ]);
    }
}
