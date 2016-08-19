<?php

namespace {

  use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
  use Symfony\Component\DependencyInjection\ContainerBuilder;
  use Symfony\Component\DependencyInjection\Reference;

  /**
   * Class sfServiceFactoryLoggersPass
   */
  class sfServiceFactoryLoggersPass implements CompilerPassInterface
  {
    /**
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
      if (!$container->has('sf_logger'))
      {
        return;
      }

      $definition     = $container->findDefinition('sf_logger');
      $taggedServices = $container->findTaggedServiceIds('sf.logger');

      foreach ($taggedServices as $id => $tags)
      {
        $definition->addMethodCall('addLogger', array(new Reference($id)));
      }
    }

  }
}
