<?php

/*
 * This File is part of the Lucid\Module\Http package
 *
 * (c) iwyg <mail@thomas-appel.com>
 *
 * For full copyright and license information, please refer to the LICENSE file
 * that was distributed with this package.
 */

namespace Lucid\Module\Http\Session;

use Lucid\Module\Http\ParameterInterface;
use Lucid\Module\Http\Session\Storage\StorageInterface;

/**
 * @interface SessionInterface
 *
 * @package Lucid\Module\Http
 * @version $Id$
 * @author iwyg <mail@thomas-appel.com>
 */
interface SessionInterface extends StorageInterface
{
    /**
     * set
     *
     * @param string $attr
     * @param mixed $value
     *
     * @return void
     */
    public function set($attr, $value);

    /**
     * get
     *
     * @param string $attr
     * @param mixed $default
     *
     * @return mixed
     */
    public function get($attr, $default = null);

    /**
     * setMessage
     *
     * @param string $key
     * @param mixed $message
     *
     * @return void
     */
    public function setMessage($key, $message);

    /**
     * getMessage
     *
     * @param string $key
     * @param mixed $default
     *
     * @return string|array
     */
    public function getMessage($key, $default = null);

    /**
     * getMessages
     *
     * @return Lucid\Module\Http\Session\Message\MessagesInterface
     */
    public function getMessages();
}
