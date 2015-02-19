<?php

namespace SimpleAcl\Bundle\CoreBundle\DependencyInjection;

use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * Could be easier I think !
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class SimpleAclCoreExtension extends Extension
{
    protected $prefix = 'simple_acl';
    protected $parameterClasses = 'simple_acl.config.classes';

    const CONFIGURE_SERVICES   = 1;
    const CONFIGURE_DATABASE   = 2;
    const CONFIGURE_PARAMETERS = 4;
    const CONFIGURE_VALIDATORS = 8;

    protected $configDir;
    protected $configFiles = array(
        'services',
        'forms'
    );

    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $this->configDir = __DIR__ . '/../Resources/config';

        $configure
            = self::CONFIGURE_SERVICES
            | self::CONFIGURE_DATABASE
            | self::CONFIGURE_PARAMETERS;

        $this->configure($configs, $container, new Configuration(), $configure);
    }

    /**
     * @param array $configs
     * @param ContainerBuilder $container
     * @param ConfigurationInterface $configuration
     * @param int $configure
     *
     * @return array
     */
    public function configure(array $configs, ContainerBuilder $container, ConfigurationInterface $configuration, $configure)
    {
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\XmlFileLoader($container, new FileLocator($this->configDir));

        if ($configure & self::CONFIGURE_SERVICES) {
            foreach ($this->configFiles as $file) {
                $loader->load($file . '.xml');
            }
        }

        if ($configure & self::CONFIGURE_DATABASE) {
            $this->loadDatabaseDriver($config['db_driver'], $loader, $container);
        }

        $classes = isset($config['classes']) ? $config['classes'] : array();
        if ($configure & self::CONFIGURE_PARAMETERS) {
            $this->mapClassParameters($classes, $container);
        }

        if ($configure & self::CONFIGURE_VALIDATORS) {
            $this->mapValidationGroupParameters($config['validation_groups'], $container);
        }

        $this->mergeParameters($container, $this->parameterClasses, $classes);

        return $config;
    }

    /**
     * Merge parameter value
     *
     * @param ContainerBuilder $container
     * @param string $parameter
     * @param array $classes
     */
    protected function mergeParameters(ContainerBuilder $container, $parameter, array $classes)
    {
        if ($container->hasParameter($parameter)) {
            $classes = array_merge($classes, $container->getParameter($parameter));
        }
        $container->setParameter($parameter, $classes);
    }

    protected function mapClassParameters(array $classes, ContainerBuilder $container)
    {
        foreach ($classes as $model => $serviceClasses) {
            $this->mapModelServiceClass($model, $serviceClasses, $container);
        }
    }

    /**
     * @param $model
     * @param array $serviceClasses
     * @param ContainerBuilder $container
     */
    protected function mapModelServiceClass($model, array $serviceClasses, ContainerBuilder $container)
    {
        foreach ($serviceClasses as $service => $class) {
            $container->setParameter(
                sprintf('%s.%s.%s.class', $this->prefix, $service === 'form' ? 'form.type' : $service, $model),
                $class
            );
        }
    }

    /**
     * Map validation group parameters.
     *
     * @param array $validationGroups
     * @param ContainerBuilder $container
     */
    protected function mapValidationGroupParameters(array $validationGroups, ContainerBuilder $container)
    {
        foreach ($validationGroups as $model => $groups) {
            $container->setParameter(sprintf('%s.validation_groups.%s', $this->prefix, $model), $groups);
        }
    }

    /**
     * Load bundle driver
     *
     * @param string $driver
     * @param Loader\XmlFileLoader $loader
     * @param ContainerBuilder $container
     * @throws \InvalidArgumentException
     */
    protected function loadDatabaseDriver($driver, Loader\XmlFileLoader $loader, ContainerBuilder $container = null)
    {
        $bundle = str_replace(array('Extension', 'DependencyInjection\\'), array('Bundle', ''), get_class($this));
        if (!in_array($driver, array('mongodb', 'orm'))) {
            throw new \InvalidArgumentException(
                sprintf('Driver "%s" is unsupported by %s.', $driver, basename($bundle))
            );
        }

        $loader->load(sprintf('driver/%s.xml', $driver));

        if (null !== $container) {
            $container->setParameter($this->getAlias().'.driver', $driver);
            $container->setParameter($this->getAlias().'.driver.'.$driver, true);
        }
    }
}
