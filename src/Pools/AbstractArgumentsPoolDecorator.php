<?php declare(strict_types=1);

namespace Ellipse\Resolving\Pools;

use Ellipse\Resolving\Arguments;

abstract class AbstractArgumentsPoolDecorator implements ArgumentsPoolInterface
{
    /**
     * The delegate.
     *
     * @var \Ellipse\Resolving\Pools\ArgumentsPoolInterface
     */
    private $delegate;

    /**
     * Set up an arguments pool decorator with the given delegate.
     *
     * @param \Ellipse\Resolving\Pools\ArgumentsPoolInterface $delegate
     */
    public function __construct(ArgumentsPoolInterface $delegate)
    {
        $this->delegate = $delegate;
    }

    /**
     * Proxy the delegate.
     *
     * @param \ReflectionParameter[]        $parameters
     * @return \Ellipse\Resolving\Arguments
     */
    public function arguments(array $parameters): Arguments
    {
        return $this->delegate->arguments($parameters);
    }
}
