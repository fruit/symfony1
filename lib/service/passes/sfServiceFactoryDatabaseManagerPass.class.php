<?php

namespace {

  use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
  use Symfony\Component\DependencyInjection\ContainerBuilder;
  use Symfony\Component\DependencyInjection\Definition;
  use Symfony\Component\DependencyInjection\Reference;

  /**
   * Class sfServiceFactoryDatabaseManagerPass
   */
  class sfServiceFactoryDatabaseManagerPass extends sfAbstractServiceFactoryPass implements CompilerPassInterface
  {
    /**
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
      if ($container->hasParameter('sf_database_manager.param')) {
        $params = (array)$container->getParameter('sf_database_manager.param');
      } else {
        $params = array();
      }

      $class = $container->getParameter('sf_database_manager.class');

      $definition = new Definition(
        $class,
        array(
          new Reference('sf_application_configuration'),
          array_merge(array('auto_shutdown' => false), $params),
        )
      );

      $container->setDefinition('sf_database_manager', $definition);
    }

  }
}
