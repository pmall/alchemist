<?php declare(strict_types=1);

namespace Quanta\Injection\Pools;

use Quanta\Injection\ArgumentList;

abstract class AbstractArgumentsPoolDecorator implements ArgumentsPoolInterface
{
    /**
     * The delegate.
     *
     * @var \Quanta\Injection\Pools\ArgumentsPoolInterface
     */
    private $delegate;

    /**
     * Set up an arguments pool decorator with the given delegate.
     *
     * @param \Quanta\Injection\Pools\ArgumentsPoolInterface $delegate
     */
    public function __construct(ArgumentsPoolInterface $delegate)
    {
        $this->delegate = $delegate;
    }

    /**
     * Proxy the delegate.
     *
     * @param \ReflectionParameter[] $parameters
     * @return \Quanta\Injection\ArgumentList
     */
    public function arguments(array $parameters): ArgumentList
    {
        return $this->delegate->arguments($parameters);
    }
}
