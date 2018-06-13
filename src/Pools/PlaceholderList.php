<?php declare(strict_types=1);

namespace Quanta\Injection\Pools;

use Quanta\Injection\ArgumentList;

final class PlaceholderList implements ArgumentsPoolInterface
{
    /**
     * The list of placeholders.
     *
     * @var array
     */
    private $placeholders;

    /**
     * Set up a placeholder list the given placeholders.
     *
     * @param array $placeholders
     */
    public function __construct(array $placeholders)
    {
        $this->placeholders = $placeholders;
    }

    /**
     * Sequentially bind parameters to the next placeholder until the list is
     * exhausted.
     *
     * @param \ReflectionParameter[] $parameters
     * @return \Quanta\Injection\ArgumentList
     */
    public function arguments(array $parameters): ArgumentList
    {
        $arguments = new ArgumentList;

        $placeholders = $this->placeholders;

        foreach ($parameters as $parameter) {
            if (count($placeholders) > 0) {
                $placeholder = array_shift($placeholders);

                $arguments = $arguments->with($parameter, function () use ($placeholder) {
                    return $placeholder;
                });
            }
        }

        return $arguments;
    }
}
