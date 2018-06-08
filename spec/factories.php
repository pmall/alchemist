<?php

use function Eloquent\Phony\mock;

if (! function_exists('parameter')) {

    function parameter(int $position, array $options = [])
    {
        $parameter = mock(ReflectionParameter::class);

        $parameter->getPosition->returns($position);
        $parameter->getName->returns('parameter');
        $parameter->__toString->returns('description');

        if (isset($options['name'])) {
            $parameter->getName->returns($options['name']);
        }

        if (isset($options['default'])) {
            $parameter->isDefaultValueAvailable->returns(true);
            $parameter->getDefaultValue->returns($options['default']);
        }

        if (isset($options['type'])) {
            $parameter->getType->returns($options['type']);
        }

        return $parameter->get();
    }

}

if (! function_exists('type')) {

    function type(bool $allowsNull, string $class = null)
    {
        $type = mock(ReflectionType::class);

        $type->allowsNull->returns($allowsNull);

        $isBuiltIn = is_null($class);

        $type->isBuiltIn->returns($isBuiltIn);

        if (! $isBuiltIn) {
            $type->__toString->returns($class);
        }

        return $type->get();
    }

}
