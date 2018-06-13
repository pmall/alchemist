<?php

use function Eloquent\Phony\mock;

use Quanta\Injection\Exceptions\ResolvingExceptionInterface;
use Quanta\Injection\Exceptions\ArgumentResolutionException;

describe('ArgumentResolutionException', function () {

    beforeEach(function () {

        $parameter = mock(ReflectionParameter::class);

        $parameter->getName->returns('parameter');
        $parameter->__toString->returns('description');

        $this->previous = mock(Throwable::class)->get();

        $this->exception = new ArgumentResolutionException($parameter->get(), $this->previous);

    });

    it('should implement ResolvingExceptionInterface', function () {

        expect($this->exception)->toBeAnInstanceOf(ResolvingExceptionInterface::class);

    });

    describe('->getPrevious()', function () {

        it('should return the previous exception', function () {

            $test = $this->exception->getPrevious();

            expect($test)->toBe($this->previous);

        });

    });

});
