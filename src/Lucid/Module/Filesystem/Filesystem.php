<?php

/*
 * This File is part of the Lucid\Module\Filesystem package
 *
 * (c) iwyg <mail@thomas-appel.com>
 *
 * For full copyright and license information, please refer to the LICENSE file
 * that was distributed with this package.
 */

namespace Lucid\Module\Filesystem;

use Lucid\Module\Filesystem\Driver\LocalDriver;
use Lucid\Module\Filesystem\Driver\DriverInterface;
use Lucid\Module\Filesystem\Exception\IOException;

/**
 * @class Filesystem
 *
 * @package Lucid\Module\Filesystem
 * @version $Id$
 * @author iwyg <mail@thomas-appel.com>
 */
class Filesystem implements FilesystemInterface
{
    private $driver;

    /**
     * Constructor.
     *
     * @param DriverInterface $driver
     */
    public function __construct(DriverInterface $driver = null)
    {
        $this->driver = $driver ?: new LocalDriver('/');
    }

    /**
     * {@inheritdoc}
     */
    public function exists($path)
    {
        return $this->driver->exists($path);
    }

    /**
     * {@inheritdoc}
     */
    public function isDir($path)
    {
        return $this->driver->isDir($path);
    }

    /**
     * {@inheritdoc}
     */
    public function isFile($path)
    {
        return $this->driver->isFile($path);
    }

    /**
     * {@inheritdoc}
     */
    public function isLink($path)
    {
        return $this->driver->isLink($path);
    }

    /**
     * {@inheritdoc}
     */
    public function ensureDirectory($path)
    {
        return $this->driver->ensureDirectory($path);
    }

    /**
     * {@inheritdoc}
     */
    public function ensureFile($path)
    {
        return $this->driver->ensureFile($path);
    }

    /**
     * {@inheritdoc}
     */
    public function writeFile($file, $content)
    {
        $res = false;

        if (!$this->driver->exists($file)) {
            $res = $this->driver->writeFile($file, $content);
        } elseif ($this->driver->isFile($file)) {
            $res = $this->driver->updateFile($file, $content);
        }

        if (false === $res) {
            throw new IOException(sprintf('Cannot write to file "%s".', $file));
        }

        return $res;
    }

    /**
     * {@inheritdoc}
     */
    public function dumpFile($file, $start = null, $stop = null)
    {
        if (!$this->driver->isFile($file)) {
            throw new IOException(sprintf('Cannot read contents. "%s" is not a file', $file));
        }

        return $this->driver->readFile($file, $start, $stop);
    }

    /**
     * {@inheritdoc}
     */
    public function writeStreamToFile($file, $stream)
    {
        return $this->driver->writeStream($file, $stream);
    }

    /**
     * {@inheritdoc}
     */
    public function readStreamFromFile($file)
    {
        return $this->driver->readStream($file);
    }

    /**
     * {@inheritdoc}
     */
    public function mkdir($dir, $mod = 0755, $recursive = true)
    {
        if (!$res = $this->driver->createDirectory($dir, $mod, $recursive)) {
            throw IOException::createDir($dir);
        }

        return $res;
    }

    /**
     * {@inheritdoc}
     */
    public function rmdir($dir)
    {
        if (!$this->driver->deleteDirectory($dir)) {
            throw IOException::rmDir($dir);
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function unlink($file)
    {
        if (!$this->driver->deleteFile($file)) {
            throw IOException::rmFile($file);
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function remove($path)
    {
        if ($this->driver->isDir($path)) {
            return $this->rmdir($path);
        }

        return $this->unlink($path);
    }

    /**
     * {@inheritdoc}
     */
    public function rename($source, $target)
    {
        return $this->driver->rename($source, $target);
    }

    /**
     * {@inheritdoc}
     */
    public function enum($path, $prefix = null, $pad = true)
    {
        $start = 0;
        $files = array_change_key_case($this->driver->listDirectory($dir = dirname($path)), CASE_LOWER);

        $prefix = $prefix ?: self::COPY_PREFIX;

        $name = $bn = basename($path);
        $suffix = '';

        if (false !== $pos = strrpos($bn, '.')) {
            $suffix = substr($bn, $pos);
            $bn     = substr($bn, 0, $pos);
        }

        $enum = '';

        while (isset($files[strtolower($name = $bn . ' ' . $prefix . $enum . $suffix)])) {
            $enum = 0 < $start ? ' '.$start : '';
            $start++;
        }

        return $dir . $this->getSeparator() . $name;
    }

    /**
     * {@inheritdoc}
     */
    public function touch($path, $time = null, $atime = null)
    {
        if ($this->driver instanceof NativeInterface) {
            return $this->driver->touch($path, $time, $atime);
        }

        if (!$this->driver->exists($path)) {
            $this->driver->writeFile($path, '');
        }

        return $this->driver->updateTimestamp($path, $time ?: time());
    }

    /**
     * {@inheritdoc}
     */
    public function chmod($path, $mod = 0755, $recursive = true, $umask = 0000)
    {
        $stat = $this->driver->setPermission($path, $mod, $recursive);

        return $stat;
    }

    /**
     * {@inheritdoc}
     */
    public function backup($path, $dateFormat = 'Y-m-d-His', $suffix = '~')
    {
        if (!$this->driver->exists($path)) {
            throw new IOException();
        }

        if ($this->driver->isLink($path)) {
            return false;
        }

        $pname = dirname($path) . $this->getSeparator().$suffix.basename($path);
        $backupName = $this->enum($pname, (new \DateTime())->format($dateFormat));

        if ($this->driver->isFile($path)) {
            return $this->driver->copyFile($path, $backupName);
        }

        return $this->driver->copyDirectory($path, $backupName);
    }

    /**
     * {@inheritdoc}
     */
    public function copy($source, $target = null)
    {
        if (null === $target) {
            $target = $this->enum($source);
        }

        if ($this->driver->isFile($source)) {
            return $this->driver->copyFile($target);
        }

        return $this->driver->copyDirectory($target);
    }

    public function chown($file, $owner, $recursive = true)
    {
    }

    public function chgrp($file, $group, $recursive = true)
    {
    }

    public function flush($path)
    {
        $this->driver->deleteDirectory($path);
    }

    /**
     * getSeparator
     *
     * @return string
     */
    protected function getSeparator()
    {
        if ($this->driver instanceof AbstractDriver) {
            return $this->driver->getSeparator();
        }

        return '/';
    }
}
