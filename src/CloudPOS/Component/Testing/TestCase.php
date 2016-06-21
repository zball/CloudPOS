<?php
namespace CloudPOS\Component\Testing;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\DependencyInjection\Container;

class TestCase extends WebTestCase
{
    /**
     * @var Container
     */
    protected $container;

    /**
     * @var Application
     */
    protected $console;

    public function setUp()
    {
        static::bootKernel();

        $this->container = static::$kernel->getContainer();
        $this->console = new Application(static::$kernel);
        $this->console->setAutoExit(false);
    }

    protected function runCommand($command)
    {
        $command = sprintf('%s --quiet', $command);

        $this->console->run(
            new StringInput($command)
        );
    }

    protected function setUpDb()
    {
        $this->runCommand('doctrine:database:create --env=test');
        $this->runCommand('doctrine:schema:update --env=test --force');
    }

    public function tearDown()
    {
        parent::tearDown();
        $db_file = static::$kernel->getCacheDir() . '/../sqlite.db.cache';
        @unlink($db_file);
        $stream = fopen($db_file, 'w+');
        fclose($stream);
    }
}