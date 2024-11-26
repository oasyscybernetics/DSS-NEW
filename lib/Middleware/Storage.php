<?php
/*
 * Oasys Digital Signage
 * 
 * 
 */


namespace Xibo\Middleware;


use Slim\Helper\Set;
use Slim\Middleware;
use Xibo\Service\LogService;
use Xibo\Storage\PdoStorageService;
use Xibo\Storage\MySqlTimeSeriesStore;

/**
 * Class Storage
 * @package Xibo\Middleware
 */
class Storage extends Middleware
{
    public function call()
    {
        $app = $this->app;

        $app->startTime = microtime(true);
        $app->commit = true;

        // Configure storage
        self::setStorage($app->container);

        $this->next->call();

        // Are we in a transaction coming out of the stack?
        if ($app->store->getConnection()->inTransaction()) {
            // We need to commit or rollback? Default is commit
            if ($app->commit) {
                $app->store->commitIfNecessary();
            } else {

                $app->logService->debug('Storage rollback.');

                $app->store->getConnection()->rollBack();
            }
        }

        // Get the stats for this connection
        $stats = $app->store->stats();
        $stats['length'] = microtime(true) - $app->startTime;
        $stats['memoryUsage'] = memory_get_usage();
        $stats['peakMemoryUsage'] = memory_get_peak_usage();

        $app->logService->info('Request stats: %s.', json_encode($stats, JSON_PRETTY_PRINT));

        $app->store->close();
    }

    /**
     * Set Storage
     * @param Set $container
     */
    public static function setStorage($container)
    {
        // Register the log service
        $container->singleton('logService', function($container) {
            return new LogService($container->log, $container->mode);
        });

        // Register the database service
        $container->singleton('store', function($container) {
            return (new PdoStorageService($container->logService))->setConnection();
        });

        // Register the statistics database service
        $container->singleton('timeSeriesStore', function($container) {
            if ($container->configService->timeSeriesStore == null) {
                return (new MySqlTimeSeriesStore())
                    ->setDependencies($container->logService,
                        $container->dateService,
                        $container->layoutFactory,
                        $container->campaignFactory)
                    ->setStore($container->store);
            } else {
                $timeSeriesStore = $container->configService->timeSeriesStore;
                $timeSeriesStore = $timeSeriesStore();

                return $timeSeriesStore->setDependencies(
                    $container->logService,
                    $container->dateService,
                    $container->layoutFactory,
                    $container->campaignFactory,
                    $container->mediaFactory,
                    $container->widgetFactory,
                    $container->displayFactory,
                    $container->displayGroupFactory
                );
            }
        });
    }
}