<?php

declare(strict_types=1);

namespace Hexlet\Code;

use PHPUnit\Framework\TestCase;
use function Hexlet\Code\GenDiff\genDiff;

class GenDiffTest extends TestCase
{
    protected $expectedStylish;
    protected $expectedPlain;
    private function getFixtureFullPath($fixtureName)
    {
        $parts = [__DIR__, 'fixtures', $fixtureName];
        return realpath(implode('/', $parts));
    }

    protected function setUp(): void
    {
        $expectedPathStylish = $this->getFixtureFullPath('result_stylish.txt');
        $this->expectedStylish = trim(file_get_contents($expectedPathStylish));
        $expectedPathPlain = $this->getFixtureFullPath('result_plain.txt');
        $this->expectedPlain = trim(file_get_contents($expectedPathPlain));
    }

    public function filesProviderStylish()
    {
        return [
            ['file1.json', 'file2.json'],
            ['file1.yml', 'file2.yml'],
            ['file1.yml', 'file2.yml']
        ];
    }

    /**
     * @dataProvider filesProviderStylish
     */
    public function testGenDiff($fileName1, $fileName2)
    {
        $filePath1 = $this->getFixtureFullPath($fileName1);
        $filePath2 = $this->getFixtureFullPath($fileName2);
        $this->assertEquals($this->expectedStylish, genDiff($filePath1, $filePath2, 'stylish'));
        $this->assertEquals($this->expectedPlain, genDiff($filePath1, $filePath2, 'plain'));
    }
}
