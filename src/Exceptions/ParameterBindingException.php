<?php declare(strict_types=1);

namespace Ellipse\Resolving\Exceptions;

use RuntimeException;
use ReflectionParameter;

final class ParameterBindingException extends RuntimeException implements ResolvingExceptionInterface
{
    public function __construct(ReflectionParameter $parameter)
    {
        $template = "Failed to bind an argument to the parameter $%s (%s)";

        $msg = sprintf($template, $parameter->getName(), (string) $parameter);

        parent::__construct($msg);
    }
}
