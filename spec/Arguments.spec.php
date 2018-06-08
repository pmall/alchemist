<?php

require __DIR__ . '/factories.php';

use function Eloquent\Phony\stub;
use function Eloquent\Phony\mock;

use Ellipse\Resolving\Arguments;
use Ellipse\Resolving\Exceptions\ParameterBindingException;
use Ellipse\Resolving\Exceptions\ArgumentResolutionException;

describe('Arguments', function () {

    describe('->unbound()', function () {

        it('should return the parameters whose positions are not bound to an argument', function () {

            $p1 = parameter(0);
            $p2 = parameter(1);
            $p3 = parameter(2);

            $arguments = new Arguments([1 => stub()]);

            $test = $arguments->unbound([$p1, $p2, $p3]);

            expect($test)->toEqual([$p1, $p3]);

        });

    });

    describe('->value()', function () {

        context('when the given parameter position is bound to an argument', function () {

            context('when the argument resolution does not throw an exception', function () {

                it('should return the argument', function () {

                    $parameter = parameter(0);

                    $arguments = new Arguments([0 => stub()->returns('value')]);

                    $test = $arguments->value($parameter);

                    expect($test)->toEqual('value');

                });

            });

            context('when the argument resolution throws an exception', function () {

                it('should throw a ArgumentResolutionException', function () {

                    $exception = mock(Throwable::class)->get();

                    $parameter = parameter(0);

                    $arguments = new Arguments([0 => stub()->throws($exception)]);

                    $test = function () use ($arguments, $parameter) {

                        $arguments->value($parameter);

                    };

                    $exception = new ArgumentResolutionException($parameter, $exception);

                    expect($test)->toThrow($exception);

                });

            });

        });

        context('when the given parameter position is not bound to an argument', function () {

            it('should throw a ParameterBindingException', function () {

                $parameter = parameter(1);

                $arguments = new Arguments([0 => stub()]);

                $test = function () use ($arguments, $parameter) {

                    $arguments->value($parameter);

                };

                $exception = new ParameterBindingException($parameter);

                expect($test)->toThrow($exception);

            });

        });

    });

    describe('->with()', function () {

        it('shoud return a new Arguments with the given callable bound to the given parameter position', function () {

            $p1 = parameter(0);
            $p2 = parameter(1);

            $argument = stub()->returns('value');

            $arguments1 = new Arguments([0 => stub()]);

            $arguments2 = $arguments1->with($p2, $argument);

            $test = $arguments2->value($p2);

            expect($test)->toEqual('value');

        });

    });

});
