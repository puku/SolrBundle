<?php

namespace FS\SolrBundle\Debug;

use Psr\Log\LoggerInterface;
use Solarium\Core\Event\Events;
use Solarium\Core\Event\PreExecuteRequest;
use Solarium\Core\Plugin\Plugin;

/**
 * Listens on solarium.core.preExecuteRequest event
 */
class RequestDebugger extends Plugin
{

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * {@inheritdoc}
     */
    protected function initPluginType()
    {
        $this->start = microtime(true);

        $dispatcher = $this->client->getEventDispatcher();
        $dispatcher->addListener(Events::PRE_EXECUTE_REQUEST, array($this, 'preExecuteRequest'));
    }

    /**
     * @param PreExecuteRequest $event
     */
    public function preExecuteRequest(PreExecuteRequest $event)
    {
        $this->logger->info(sprintf('run request: %s', urldecode($event->getRequest()->getUri())));
    }

}