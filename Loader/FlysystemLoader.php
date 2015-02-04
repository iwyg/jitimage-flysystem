<?php

/*
 * This File is part of the Thapp\JitImage package
 *
 * (c) iwyg <mail@thomas-appel.com>
 *
 * For full copyright and license information, please refer to the LICENSE file
 * that was distributed with this package.
 */

namespace Thapp\JitImage\Loader;

use League\Flysystem\FilesystemInterface;

/**
 * @class FlysystemLoader
 *
 * @package Thapp\JitImage
 * @version $Id$
 * @author iwyg <mail@thomas-appel.com>
 */
class FlysystemLoader extends AbstractLoader
{
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
}
