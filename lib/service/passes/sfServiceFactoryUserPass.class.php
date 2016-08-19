<?php

namespace {

  use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
  use Symfony\Component\DependencyInjection\ContainerBuilder;
  use Symfony\Component\DependencyInjection\Definition;
  use Symfony\Component\DependencyInjection\Reference;

  /**
   * Class sfServiceFactoryUserPass
   */
  class sfServiceFactoryUserPass extends sfAbstractServiceFactoryPass implements CompilerPassInterface
  {
    /**
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
      $config = $this->factoryConfig;

      if (!isset($config['user']['class']))
      {
        throw new \InvalidArgumentException(
          sprintf('The "user" class was not properly configured in file factories.yml')
        );
      }

      $culture = isset($_GET['sf_culture']) ? (string) $_GET['sf_culture'] : null;
      $culture = get_magic_quotes_gpc() && null !== $culture ? stripslashes($culture) : $culture;
      $params = array_merge(
        array('auto_shutdown' => false, 'culture' => $culture),
        sfConfig::get('sf_factory_user_parameters', $config['user']['param'])
      );

      $definition = new Definition(
        sfConfig::get('sf_factory_user', $config['user']['class']),
        array(
          new Reference('sf_event_dispatcher'),
          new Reference('sf_storage'),
          $params,
        )
      );

      $container->setDefinition('sf_user', $definition);
    }
  }
}
