<?php

declare(strict_types=1);

namespace Hexlet\Code;

use PHPUnit\Framework\TestCase;
use function Hexlet\Code\GenDiff\genDiff;

class GenDiffTest extends TestCase
{
    protected $expected;
    private function getFixtureFullPath($fixtureName)
    {
        $parts = [__DIR__, 'fixtures', $fixtureName];
        return realpath(implode('/', $parts));
    }

    protected function setUp(): void
    {
        $expectdPath = $this->getFixtureFullPath('expected_flat.txt');
        $this->expected = file_get_contents($expectdPath);
    }

    public function filesProvider()
    {
        return [
            ['flatFile1.json', 'flatFile2.json']
        ];
    }

    /**
     * @dataProvider filesProvider
     */
    public function testGenDiff($fileName1, $fileName2)
    {
        $filePath1 = $this->getFixtureFullPath($fileName1);
        $filePath2 = $this->getFixtureFullPath($fileName2);
        $this->assertEquals($this->expected, genDiff($filePath1, $filePath2));
    }
}
