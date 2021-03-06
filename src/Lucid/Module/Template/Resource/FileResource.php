<?php

/*
 * This File is part of the Lucid\Module\Template\Resource package
 *
 * (c) iwyg <mail@thomas-appel.com>
 *
 * For full copyright and license information, please refer to the LICENSE file
 * that was distributed with this package.
 */

namespace Lucid\Module\Template\Resource;

/**
 * @class FileResource
 *
 * @package Lucid\Module\Template\Resource
 * @version $Id$
 * @author iwyg <mail@thomas-appel.com>
 */
class FileResource extends AbstractResource
{
    /**
     * resource
     *
     * @var string
     */
    private $file;

    /**
     * Constructor.
     *
     * @param string $file
     */
    public function __construct($file)
    {
        $this->file = $file;
    }

    /**
     * {@inheritdoc}
     */
    public function getResource()
    {
        return $this->file;
    }

    /**
     * {@inheritdoc}
     */
    public function getContents()
    {
        return file_get_contents($this->file);
    }
}
