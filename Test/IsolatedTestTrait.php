<?php

namespace NoRegression\TestBundle\Test;

use Doctrine\DBAL\Driver\PDOSqlite\Driver as PDOSqliteDriver;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;

trait IsolatedTestTrait
{
    protected static $application;
    protected static $environment = 'test';
    protected static $debug = true;

    protected function rebuildDatabase()
    {
        $conn = static::$application->getKernel()->getContainer()->get('doctrine.dbal.default_connection');
        if (!$conn->getDriver() instanceof PDOSqliteDriver) {
            throw new \RuntimeException('It would not work nicely with driver other than PDOSqlite');
        }

        $this->runConsole('doctrine:database:create', array('-n' => true));
        $this->runConsole('doctrine:schema:drop', array('--force' => true));
        $this->runConsole('doctrine:schema:create', array());
        $this->runConsole('doctrine:fixtures:load', array('-n' => true));
    }

    protected static function bootstrapApplication()
    {
        $kernel = new \AppKernel(static::$environment, static::$debug);
        $kernel->boot();
        static::$application = new Application($kernel);
        static::$application->setAutoExit(false);
    }

    protected function runConsole($command, array $options = array())
    {
        $options['-e'] = self::$environment;
        $options['-q'] = null;

        $input = new ArrayInput(array_merge($options, array('command' => $command)));
        $result = self::$application->run($input);

        if (0 != $result) {
            throw new \RuntimeException(
                sprintf('Something has gone wrong, got return code %d for command %s', $result, $command)
            );
        }

        return $result;
    }
}
