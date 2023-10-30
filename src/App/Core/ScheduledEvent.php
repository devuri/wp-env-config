<?php
/**
 * This file is part of the Slim White Label WordPress Plugin.
 *
 * (c) Uriel Wilson
 *
 * Please see the LICENSE file that was distributed with this source code
 * for full copyright and license information.
 */

namespace Urisoft\App\Core;

/**
 * Class ScheduledEvent.
 *
 * A class for scheduling and managing custom events in WordPress.
 */
class ScheduledEvent
{
    /**
     * Event name to identify the scheduled event.
     *
     * @var string
     */
    private $event_name;

    /**
     * The callback function to execute when the event is triggered.
     *
     * @var callable
     */
    private $callback;

    /**
     * The recurrence interval for the event (default is 'twicedaily').
     *
     * @var string
     */
    private $recurrence;

    /**
     * Constructor for the ScheduledEvent class.
     *
     * @param string   $event_name Event name to identify the scheduled event.
     * @param callable $callback   The callback function to execute when the event is triggered.
     * @param string   $recurrence The recurrence interval for the event (default is 'twicedaily').
     */
    public function __construct( string $event_name, $callback, string $recurrence = 'twicedaily' )
    {
        $this->event_name = $event_name;
        $this->callback   = $callback;
        $this->recurrence = $recurrence;
    }

    /**
     * Adds an action hook to schedule the event on WordPress initialization.
     */
    public function add_app_event(): void
    {
        add_action( 'init', [ $this, 'schedule_app_event' ] );
    }

    /**
     * Schedules the custom event if it's not already scheduled.
     * Adds an action hook for the event callback.
     */
    public function schedule_app_event(): void
    {
        if ( ! wp_next_scheduled( $this->event_name ) ) {
            wp_schedule_event( time(), $this->recurrence, $this->event_name );
        }

        add_action( $this->event_name, [ $this, 'event_callback' ] );
    }

    /**
     * Executes the specified callback function when the event is triggered.
     */
    public function event_callback(): void
    {
        if ( \is_callable( $this->callback ) ) {
            \call_user_func( $this->callback );
        }
    }

    /**
     * Get an array of available recurrence options for scheduling events.
     *
     * @return array An array of available recurrence options.
     */
    public static function available_recurrences()
    {
        return [
            'hourly'     => 'Hourly',
            'twicedaily' => 'Twice Daily',
            'daily'      => 'Daily',
            'weekly'     => 'Weekly',
        ];
    }
}
