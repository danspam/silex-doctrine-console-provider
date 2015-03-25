<?php

namespace danspam\Silex\Provider;

use Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper;
use Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper;
use Silex\Application;
use Silex\ServiceProviderInterface;
use Symfony\Component\Console\Application as Console;
use Symfony\Component\Console\Helper\DialogHelper;
use Symfony\Component\Console\Helper\HelperSet;

class DoctrineConsoleProvider implements ServiceProviderInterface
{
      /**
     * The console application.
     *
     * @var Console
     */
    protected $console;

    /**
     * Creates a new doctrine migrations provider.
     *
     * @param Console $console
     */
    public function __construct(Console $console)
    {
        $this->console = $console;
    }

    public function register(Application $app)
    {
        //nothing to do here
    }

    public function boot(Application $app)
    {
        $helperSet = new HelperSet(array(
            'connection' => new ConnectionHelper($app['db']),
            'dialog'     => new DialogHelper(),
        ));

        if (isset($app['orm.em'])) {
            $helperSet->set(new EntityManagerHelper($app['orm.em']), 'em');
        } elseif (isset($app['db.orm.em'])) {
            $helperSet->set(new EntityManagerHelper($app['db.orm.em']), 'em');
        }

        $this->console->setHelperSet($helperSet);

        $this->console->addCommands(array(
            // DBAL Commands
            new \Doctrine\DBAL\Tools\Console\Command\RunSqlCommand(),
            new \Doctrine\DBAL\Tools\Console\Command\ImportCommand(),
            // ORM Commands
            new \Doctrine\ORM\Tools\Console\Command\ClearCache\MetadataCommand(),
            new \Doctrine\ORM\Tools\Console\Command\ClearCache\ResultCommand(),
            new \Doctrine\ORM\Tools\Console\Command\ClearCache\QueryCommand(),
            new \Doctrine\ORM\Tools\Console\Command\SchemaTool\CreateCommand(),
            new \Doctrine\ORM\Tools\Console\Command\SchemaTool\UpdateCommand(),
            new \Doctrine\ORM\Tools\Console\Command\SchemaTool\DropCommand(),
            new \Doctrine\ORM\Tools\Console\Command\EnsureProductionSettingsCommand(),
            new \Doctrine\ORM\Tools\Console\Command\ConvertDoctrine1SchemaCommand(),
            new \Doctrine\ORM\Tools\Console\Command\GenerateRepositoriesCommand(),
            new \Doctrine\ORM\Tools\Console\Command\GenerateEntitiesCommand(),
            new \Doctrine\ORM\Tools\Console\Command\GenerateProxiesCommand(),
            new \Doctrine\ORM\Tools\Console\Command\ConvertMappingCommand(),
            new \Doctrine\ORM\Tools\Console\Command\RunDqlCommand(),
            new \Doctrine\ORM\Tools\Console\Command\ValidateSchemaCommand()
        ));
    }
}
