<?php
namespace Sasedev\MpdfBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 *
 * Sasedev\MpdfBundle\DependencyInjection\SasedevMpdfExtension
 *
 *
 * @author sasedev <sinus@sasedev.net>
 *         Created on: 5 juil. 2020 @ 22:25:09
 */
class SasedevMpdfExtension extends Extension
{

    /**
     *
     * {@inheritdoc}
     *
     */
    public function load(array $configs, ContainerBuilder $container)
    {

        $locator = new FileLocator(__DIR__ . '/../Resources/config');
        $loader = new Loader\YamlFileLoader($container, $locator);
        $loader->load('services.yaml');

    }

}
