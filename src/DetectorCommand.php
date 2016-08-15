<?php

namespace App;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;

class DetectorCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('detect')
            ->addArgument('scanDir', InputArgument::REQUIRED, 'The directory to scan')
            ->addOption('follow', 'f', InputOption::VALUE_NONE)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $scanDir = $input->getArgument('scanDir');

        $finder = new Finder();

        if ($input->getOption('follow')) {
            $output->writeln("<info>Checking '$scanDir' (follows links).</info>");
            $finder->followLinks();
        } else {
            $output->writeln("<info>Checking '$scanDir' (do not follows links).</info>");
        }

        $finder->files()->in($scanDir);

        // Regroups files by hashes.
        $hashes = array();
        foreach ($finder as $file) {
            $md5Hash = md5_file($file);

            $hashes[$md5Hash][] = $file;
        }

        // Displays duplicate files.
        foreach ($hashes as $md5Hash => $fileArray) {
            if (count($fileArray) > 1) {
                $output->writeln("<info>Duplicate files detected:</info> (hash: <comment>$md5Hash</comment>)");
                foreach ($fileArray as $fileName) {
                    $output->writeln("\t" . $fileName);
                }
                $output->writeln("");
            }
        }

        // Displays checked files count.
        $output->writeln("<info>Files checked: " . count($hashes) . ".</info>");
    }
}
