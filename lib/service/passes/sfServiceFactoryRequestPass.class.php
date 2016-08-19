<?php

namespace {

  use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
  use Symfony\Component\DependencyInjection\ContainerBuilder;
  use Symfony\Component\DependencyInjection\Definition;
  use Symfony\Component\DependencyInjection\Reference;

  /**
   * Class sfServiceFactoryRequestPass
   */
  class sfServiceFactoryRequestPass extends sfAbstractServiceFactoryPass implements CompilerPassInterface
  {
    /**
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
      $config = $this->factoryConfig;

      if (! isset($config['request']['class']))
      {
        return;
      }

      $params = isset($config['request']['param']) ? (array) $config['request']['param'] : array();
      $params['no_script_name'] = sfConfig::get('sf_no_script_name');

      $definition = new Definition(
        sfConfig::get('sf_factory_request', $config['request']['class']),
        array(
          new Reference('sf_event_dispatcher'),
          array(),
          array(),
          sfConfig::get('sf_factory_request_parameters', $params),
        )
      );

      $definition->setConfigurator(array(new Reference('sf_request_configurator'), 'configure'));
      $container->setDefinition('sf_request', $definition);
    }

  }
}
