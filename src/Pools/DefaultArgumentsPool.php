<?php declare(strict_types=1);

namespace Ellipse\Resolving\Pools;

use Psr\Container\ContainerInterface;

final class DefaultArgumentsPool extends AbstractArgumentsPoolDecorator
{
    /**
     * Set up a default arguments pool object.
     *
     * @param \Psr\Container\ContainerInterface $container
     * @param array                             $instances
     * @param array                             $placeholders
     */
    public function __construct(ContainerInterface $container, array $instances, array $placeholders)
    {
        parent::__construct(new CompositeArgumentsPool([
            new ClassNameToInstanceMap($instances),
            new ContainerEntries($container),
            new Placeholders($placeholders),
            new DefaultValues,
            new NullValues,
        ]));
    }
}
