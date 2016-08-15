<?php

namespace Tests;

use App\DetectorCommand;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class DetectorCommandTest extends \PHPUnit_Framework_TestCase
{
    private $testDir;

    private $file1Name;
    private $file2Name;
    private $file3Name;
    private $symlinkedFileName;

    private $file1Hash;
    private $file2Hash;
    private $file3Hash;
    private $symlinkedFileHash;

    public function setUp()
    {
        $this->testDir = __DIR__ . '/test_dir';

        $this->file1Name = 'file_1';
        $this->file2Name = 'file_2_matching';
        $this->file3Name = 'file_3_matching';
        $this->symlinkedFileName = 'sub_file';

        $this->file1Hash = md5_file($this->testDir . '/file_1');
        $this->file2Hash = md5_file($this->testDir . '/file_2_matching');
        $this->file3Hash = md5_file($this->testDir . '/file_3_matching');
        $this->symlinkedFileHash = md5_file($this->testDir . '/sub_dir_link/sub_file');
    }

    public function testExecuteWithoutFollowingSymlinks()
    {
        $application = new Application();
        $application->add(new DetectorCommand());

        $command = $application->find('detect');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array(
            'command'  => $command->getName(),
            'scanDir' => $this->testDir,
        ));

        $output = $commandTester->getDisplay();

        $this->assertNotContains($this->file1Hash, $output);
        $this->assertContains($this->file2Hash, $output);
        $this->assertContains($this->file3Hash, $output);

        $this->assertNotContains($this->file1Name, $output);
        $this->assertContains($this->file2Name, $output);
        $this->assertContains($this->file3Name, $output);

        // Do not follow symlinks.
        $this->assertNotContains($this->symlinkedFileName, $output);
    }

    public function testExecuteFollowingSymlinks()
    {
        $application = new Application();
        $application->add(new DetectorCommand());

        $command = $application->find('detect');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array(
            'command'  => $command->getName(),
            'scanDir' => $this->testDir,
            '--follow' => true,
        ));

        $output = $commandTester->getDisplay();

        $this->assertContains($this->symlinkedFileHash, $output);
        // Follow symlinks.
        $this->assertContains($this->symlinkedFileName, $output);
    }
}
