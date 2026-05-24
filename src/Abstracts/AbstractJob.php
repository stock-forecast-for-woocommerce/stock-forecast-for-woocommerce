<?php

namespace StockForecastForWooCommerce\Abstracts;

use StockForecastForWooCommerce\Utils\Logger;
use Throwable;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class AbstractJob
 *
 * Base class for all queue jobs.
 *
 * @package StockForecastForWooCommerce\Abstracts
 * @version 1.0.0
 */
abstract class AbstractJob
{
    /**
     * Job name.
     *
     * Example: calculate_product_forecast
     *
     * @var string
     */
    protected string $job = '';

    /**
     * Register job hook.
     *
     * @return void
     */
    public function register(): void
    {
        $job = trim($this->job);
        if ($job === '') {
            Logger::error('Queue job name not defined', [
                'class' => static::class,
            ]);

            return;
        }

        add_action(
            $this->getAction(),
            [$this, 'execute']
        );
    }

    /**
     * Execute job with safety wrapper.
     *
     * @param array $payload
     *
     * @return void
     */
    public function execute(array $payload = []): void
    {
        try {

            Logger::debug('Queue job started', [
                'job'     => $this->job,
                'payload' => $payload,
            ]);

            do_action(
                'stock_forecast_for_woocommerce_job_before_execute',
                $this->job,
                $payload
            );

            $this->handle($payload);

            Logger::debug('Queue job completed', [
                'job'     => $this->job,
                'payload' => $payload,
            ]);

            do_action(
                'stock_forecast_for_woocommerce_job_after_execute',
                $this->job,
                $payload
            );

        } catch (Throwable $e) {

            Logger::error('Queue job execution failed', [
                'job'     => $this->job,
                'payload' => $payload,
                'error'   => $e->getMessage(),
            ]);

            do_action(
                'stock_forecast_for_woocommerce_job_failed',
                $this->job,
                $payload,
                $e
            );
        }
    }

    /**
     * Get Action Scheduler hook name.
     *
     * @return string
     */
    protected function getAction(): string
    {
        return 'stock_forecast_for_woocommerce_job_' . trim($this->job);
    }

    /**
     * Handle job logic.
     *
     * Must be implemented in child class.
     *
     * @param array $payload
     *
     * @return void
     */
    abstract protected function handle(array $payload): void;
}