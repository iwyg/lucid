<?php

/*
 * This File is part of the Lucid\Module\Template\Loader package
 *
 * (c) iwyg <mail@thomas-appel.com>
 *
 * For full copyright and license information, please refer to the LICENSE file
 * that was distributed with this package.
 */

namespace Lucid\Module\Template\Loader;

use Lucid\Module\Template\IdentityInterface;

/**
 * @interface LoaderInterface
 *
 * @package Lucid\Module\Template\Loader
 * @version $Id$
 * @author iwyg <mail@thomas-appel.com>
 */
interface LoaderInterface
{
    /**
     * Load a template.
     *
     * @param IdentityInterface $template
     *
     * @return string
     */
    public function load(IdentityInterface $template);

    /**
     * isValid
     *
     * @param IdentityInterface $template
     *
     * @return void
     */
    public function isValid(IdentityInterface $template, $now);
}
