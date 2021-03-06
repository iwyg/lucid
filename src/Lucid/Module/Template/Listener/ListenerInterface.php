<?php

/*
 * This File is part of the Lucid\Module\Template\Listener package
 *
 * (c) iwyg <mail@thomas-appel.com>
 *
 * For full copyright and license information, please refer to the LICENSE file
 * that was distributed with this package.
 */

namespace Lucid\Module\Template\Listener;

use Lucid\Module\Template\Data\TemplateDataInterface;

/**
 * @interface ListenerInterface
 *
 * @package Lucid\Module\Template\Listener
 * @version $Id$
 * @author iwyg <mail@thomas-appel.com>
 */
interface ListenerInterface
{
    /**
     * onRender
     *
     * @param View $view
     *
     * @return void
     */
    public function onRender(TemplateDataInterface $data);
}
