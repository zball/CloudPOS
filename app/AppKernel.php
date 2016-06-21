<?php

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Routing\RouteCollectionBuilder;

require __DIR__ . '/autoload.php';


class AppKernel extends Kernel
{
    use MicroKernelTrait;

    /**
     * @return array
     */
    public function registerBundles()
    {
        $bundles = array(
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new JMS\SerializerBundle\JMSSerializerBundle(),
            new FOS\RestBundle\FOSRestBundle(),
            new FOS\OAuthServerBundle\FOSOAuthServerBundle(),

            // CloudPOS Bundles
            new CloudPOS\Bundle\CoreBundle\CloudPOSCoreBundle(),
            new CloudPOS\Bundle\ApiBundle\CloudPOSApiBundle(),
            new CloudPOS\Bundle\UserBundle\CloudPOSUserBundle(),
            new CloudPOS\Bundle\StoreBundle\CloudPOSStoreBundle(),
            new CloudPOS\Bundle\PaymentBundle\CloudPOSPaymentBundle(),
        );

        if (in_array($this->getEnvironment(), ['dev', 'test'])) {
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
        }

        return $bundles;
    }

    /**
     * @return string
     */
    public function getCacheDir()
    {
        return dirname(__DIR__) . '/var/cache/' . $this->getEnvironment();
    }

    /**
     * @return string
     */
    public function getLogDir()
    {
        return dirname(__DIR__) . '/var/logs';
    }

    /**
     * @param ContainerBuilder $c
     * @param LoaderInterface $loader
     */
    protected function configureContainer(ContainerBuilder $c, LoaderInterface $loader)
    {
        $base_config = realpath(__DIR__ . '/config/config_' . $this->getEnvironment() . '.yml');
        if (file_exists($base_config)) {
            $loader->load($base_config);
        } else {
            $loader->load(__DIR__ . '/config/config.yml');
        }

        $security_config = realpath(__DIR__ . '/config/security_' . $this->getEnvironment() . '.yml');
        if (file_exists($security_config)) {
            $loader->load($security_config);
        } else {
            $loader->load(__DIR__ . '/config/security.yml');
        }

        $loader->load(__DIR__ . '/config/parameters.yml');
        $loader->load(__DIR__ . '/config/services.yml');
      //  $loader->load(__DIR__ . '/config/routing.yml');
    }

    /**
     * @param RouteCollectionBuilder $routes
     */
    protected function configureRoutes(RouteCollectionBuilder $routes)
    {
        // Hack FOS Oauth Routes
        $routes->import('@FOSOAuthServerBundle/Resources/config/routing/token.xml', '/api');
        $routes->import('@FOSOAuthServerBundle/Resources/config/routing/authorize.xml', '/api');

        // Hack bundle routes for now
        foreach ($this->bundles as $name => $info) {
            if (strpos($name, 'CloudPOS') === 0) {
                $formatted = str_replace('CloudPOS', '', $name);
                $dir = __DIR__ . '/../src/CloudPOS/Bundle/' . $formatted . '/Controller/';
                if (is_dir($dir)) {
                    $routes->import($dir, '/api', 'annotation');
                }
            }
        }
    }
}
