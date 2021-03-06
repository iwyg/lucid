<?php

/*
 * This File is part of the Lucid\Module\Event package
 *
 * (c) iwyg <mail@thomas-appel.com>
 *
 * For full copyright and license information, please refer to the LICENSE file
 * that was distributed with this package.
 */

namespace Lucid\Module\Event;

/**
 * @class EventInterface
 *
 * @package Lucid\Module\Event
 * @version $Id$
 * @author iwyg <mail@thomas-appel.com>
 */
interface EventInterface
{
    /**
     * Stop event delegation for this event.
     *
     * @return void
     */
    public function stop();

    /**
     * Check if event delegation is stopped for this event.
     *
     * @return boolean
     */
    public function isStopped();

    /**
     * Set the event name.
     *
     * @param string $name
     *
     * @return void
     */
    public function setName($name);

    /**
     * Get the event name
     *
     * @return string|null
     */
    public function getName();
}
