<?php

/*
 * This File is part of the Thapp\Jmg\Loader\Flysystem package
 *
 * (c) iwyg <mail@thomas-appel.com>
 *
 * For full copyright and license information, please refer to the LICENSE file
 * that was distributed with this package.
 */

namespace Thapp\Jmg\Loader\Flysystem;

use Thapp\Jmg\Loader\AbstractLoader;
use League\Flysystem\FilesystemInterface;
use Thapp\Jmg\Exception\SourceLoaderException;

/**
 * @class FlysystemLoader
 *
 * @package Thapp\Jmg\Loader\Flysystem
 * @version $Id$
 * @author iwyg <mail@thomas-appel.com>
 */
class Flysystem extends AbstractLoader
{
    /** @var FilesystemInterface */
    private $fs;

    /**
     * Constructor.
     *
     * @param FilesystemInterface $fs
     */
    public function __construct(FilesystemInterface $fs)
    {
        $this->fs = $fs;
    }

    /**
     * {@inheritdoc}
     *
     * @throws SourceLoaderException if the file could not be opened.
     * @throws SourceLoaderException if the file is not an image.
     *
     * @return Thapp\JitImage\Resource\FileResourceInterface
     */
    public function load($file)
    {
        if (!$handle = $this->fs->readStream($file)) {
            throw new SourceLoaderException(sprintf('Could not load file "%s".', $file));
        }

        if (!$resource = $this->validate($handle)) {
            throw new SourceLoaderException(sprintf('File "%s" is not an image.', $file));
        }

        return $resource;
    }

    /**
     * {@inheritdoc}
     */
    public function supports($file)
    {
        return $this->fs->has($file);
    }
}
