<?php

/*
 * This File is part of the Thapp\JitImageFlysystemLoader\Tests\Loader package
 *
 * (c) iwyg <mail@thomas-appel.com>
 *
 * For full copyright and license information, please refer to the LICENSE file
 * that was distributed with this package.
 */

namespace Thapp\JitImage\Tests\Loader;

use Thapp\JitImage\Loader\FlysystemLoader as Loader;

/**
 * @class FlysystemLoaderTest
 *
 * @package Thapp\JitImageFlysystemLoader\Tests\Loader
 * @version $Id$
 * @author iwyg <mail@thomas-appel.com>
 */
class FlysystemLoaderTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function itShouldBeInstantiable()
    {
        $this->assertInstanceof('Thapp\JitImage\Loader\LoaderInterface', new Loader($this->mockFlysys()));
    }

    /** @test */
    public function itShouldLoadFile()
    {
        $handle = fopen(__DIR__.'/../Fixures/pattern.png', 'r');

        $fs = $this->mockFlysys();
        $fs->expects($this->once())->method('readStream')->with('file')->willReturn($handle);

        $loader = new Loader($fs);
        $this->assertInstanceof('Thapp\JitImage\Resource\FileresourceInterface', $loader->load('file'));

        fclose($handle);
    }

    /** @test */
    public function itShouldThrowIfInvalidHandle()
    {
        $handle = tmpfile();

        $fs = $this->mockFlysys();
        $fs->expects($this->once())->method('readStream')->with('file')->willReturn($handle);

        $loader = new Loader($fs);
        try {
            $loader->load('file');
        } catch (\Thapp\JitImage\Exception\SourceLoaderException $e) {
            $this->assertTrue(true);

            return;
        }

        $this->fail();
    }

    /** @test */
    public function itShouldThrowIfInvalidFile()
    {
        $handle = tmpfile();

        $fs = $this->mockFlysys();
        $fs->expects($this->once())->method('readStream')->with('file')->willReturn(false);

        $loader = new Loader($fs);
        try {
            $loader->load('file');
        } catch (\Thapp\JitImage\Exception\SourceLoaderException $e) {
            $this->assertTrue(true);

            return;
        }

        $this->fail();
    }

    /** @test */
    public function itShouldSupportFile()
    {
        $fs = $this->mockFlysys();
        $fs->expects($this->once())->method('has')->with('file')->willReturn(true);

        $loader = new Loader($fs);
        $this->assertTrue($loader->supports('file'));
    }

    protected function mockFlysys()
    {
        return $this->getMockBuilder('League\Flysystem\FilesystemInterface')
            ->disableOriginalConstructor()->getMock();
    }
}
