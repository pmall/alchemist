<?php declare(strict_types=1);

namespace Ellipse\Resolving\Exceptions;

use Throwable;
use RuntimeException;
use ReflectionParameter;

final class ArgumentResolutionException extends RuntimeException implements ResolvingExceptionInterface
{
    public function __construct(ReflectionParameter $parameter, Throwable $previous)
    {
        $template = "Failed to resolve the argument bound to the parameter $%s (%s)";

        $msg = sprintf($template, $parameter->getName(), (string) $parameter);

        parent::__construct($msg, 0, $previous);
    }
}
