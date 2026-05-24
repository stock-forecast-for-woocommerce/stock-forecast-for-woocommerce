<?php

namespace StockForecastForWooCommerce\Queue;

use StockForecastForWooCommerce\Abstracts\AbstractSingleton;
use StockForecastForWooCommerce\Utils\Logger;
use StockForecastForWooCommerce\Config\PrefixConfig;
use Throwable;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class QueueManager
 *
 * Central dispatcher for background jobs using Action Scheduler.
 *
 * @package StockForecastForWooCommerce\Queue
 * @version 1.0.0
 */
class QueueManager extends AbstractSingleton
{
    /**
     * Registered jobs
     *
     * @var array
     */
    private array $jobs = [];

    /**
     * Register queue hooks.
     *
     * @return void
     */
    public function register(): void
    {
        add_action('init', [$this, 'registerJobs']);

        add_action(
            'stock_forecast_for_woocommerce_queue_dispatch',
            [$this, 'dispatch'],
            10,
            2
        );
    }

    /**
     * Register job handlers.
     *
     * @return void
     */
    public function registerJobs(): void
    {
        /**
         * Filter queue job classes.
         *
         * @param array $jobs
         */
        $jobs = apply_filters(
            'stock_forecast_for_woocommerce_queue_jobs',
            $this->jobs
        );

        $jobs = array_values(array_unique(array_filter($jobs)));

        foreach ($jobs as $jobClass) {

            if (!class_exists($jobClass)) {
                Logger::error('Queue job class not found', [
                    'job_class' => $jobClass,
                ]);

                continue;
            }

            try {

                $job = method_exists($jobClass, 'instance')
                    ? $jobClass::instance()
                    : new $jobClass();

                if (method_exists($job, 'register')) {
                    $job->register();
                }

            } catch (Throwable $e) {

                Logger::error('Queue job registration failed', [
                    'job_class' => $jobClass,
                    'error'     => $e->getMessage(),
                ]);
            }
        }
    }

    /**
     * Dispatch a job to Action Scheduler.
     *
     * @param string $job Job name.
     * @param array $payload Job payload.
     *
     * @return void
     */
    public function dispatch(string $job, array $payload = []): void
    {
        $jobId  = $job;
        $action = PrefixConfig::PREFIX . '_job_' . $job;

        /**
         * Filter whether a queue job should run.
         *
         * @param bool $enabled
         * @param string $jobId
         * @param array $payload
         */
        $enabled = apply_filters(
            'stock_forecast_for_woocommerce_queue_job_enabled',
            true,
            $jobId,
            $payload
        );

        if (!$enabled) {
            Logger::debug('Queue job skipped', [
                'job'    => $jobId,
                'action' => $action,
            ]);
            return;
        }

        if (!function_exists('as_enqueue_async_action')) {
            Logger::error('Action Scheduler not available', [
                'job'    => $jobId,
                'action' => $action,
            ]);
            return;
        }

        try {

            /**
             * Action before queue dispatch.
             */
            do_action(
                'stock_forecast_for_woocommerce_before_queue_dispatch',
                $jobId,
                $payload
            );

            if (function_exists('as_has_scheduled_action') &&
                as_has_scheduled_action($action, [$payload], PrefixConfig::PREFIX)
            ) {
                Logger::debug('Queue job already scheduled', [
                    'job'     => $jobId,
                    'action'  => $action,
                    'payload' => $payload,
                ]);

                return;
            }

            as_enqueue_async_action(
                $action,
                [$payload],
                PrefixConfig::PREFIX
            );

            Logger::debug('Queue job dispatched', [
                'job'     => $jobId,
                'action'  => $action,
                'payload' => $payload,
            ]);

            /**
             * Action after queue dispatch.
             */
            do_action(
                'stock_forecast_for_woocommerce_after_queue_dispatch',
                $jobId,
                $payload
            );

        } catch (Throwable $e) {

            Logger::error('Queue job dispatch failed', [
                'job'     => $jobId,
                'action'  => $action,
                'payload' => $payload,
                'error'   => $e->getMessage(),
            ]);

            /**
             * Action when queue dispatch fails.
             */
            do_action(
                'stock_forecast_for_woocommerce_queue_dispatch_failed',
                $jobId,
                $payload,
                $e
            );
        }
    }

    /**
     * Push a job into the queue dispatcher.
     *
     * @param string $job Job name.
     * @param array $payload Job payload.
     *
     * @return void
     */
    public function push(string $job, array $payload = []): void
    {
        $job = trim($job);

        if ($job === '') {
            Logger::error('Queue job name is empty');
            return;
        }

        Logger::debug('Queue push requested', [
            'job'     => $job,
            'payload' => $payload,
        ]);

        do_action(
            'stock_forecast_for_woocommerce_queue_dispatch',
            $job,
            $payload
        );
    }
}