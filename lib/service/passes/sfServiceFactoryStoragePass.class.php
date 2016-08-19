<?php

namespace {

  use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
  use Symfony\Component\DependencyInjection\ContainerBuilder;
  use Symfony\Component\DependencyInjection\Definition;
  use Symfony\Component\DependencyInjection\Reference;

  /**
   * Class sfServiceFactoryStoragePass
   */
  class sfServiceFactoryStoragePass extends sfAbstractServiceFactoryPass implements CompilerPassInterface
  {
    /**
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
      $config = $this->factoryConfig;

      if (!isset($config['storage']['class']))
      {
        throw new \InvalidArgumentException(
          sprintf('The "storage" class was not properly configured in file factories.yml')
        );
      }

      $className = $config['storage']['class'];

      /* @var sfRequest $request */

      $params = isset($config['storage']['param']) ? (array) $config['storage']['param'] : array();

      $sessionId = isset($_GET[$params['session_name']]) ? (string) $_GET[$params['session_name']] : null;
      $sessionId = get_magic_quotes_gpc() ? stripslashes($sessionId) : $sessionId;

      $defaultParameters = array(
        'auto_shutdown' => false,
        'session_id'    => $sessionId, // rewrite using configurator
      );

      $params = array_merge($defaultParameters, sfConfig::get('sf_factory_storage_parameters', $params));

      $databaseName = isset($params['database']) ? (string) $params['database'] : 'default';
      unset($params['database']);

      $definition = new Definition(sfConfig::get('sf_factory_storage', $className));

      if (is_subclass_of($className, 'sfDatabaseSessionStorage'))
      {
        if (!sfConfig::get('sf_use_database'))
        {
          throw new sfParseException(sprintf('You can not use "%s" with sf_use_database=false', $className));
        }

        $params['database_name'] = $databaseName;

        $definition->setConfigurator(array(new Reference('sf_storage_configurator'), 'configure'));
      }

      $definition->addArgument($params);


      $container->setDefinition('sf_storage', $definition);
    }

  }
}
