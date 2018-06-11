<?php

require __DIR__ . '/../factories.php';

use function Eloquent\Phony\mock;

use Psr\Container\ContainerInterface;

use Ellipse\Resolving\Pools\ArgumentsPoolInterface;
use Ellipse\Resolving\Pools\DefaultArgumentsPool;

describe('DefaultArgumentsPool', function () {

    beforeEach(function () {

        $this->instance1 = new class {};
        $this->instance2 = new class {};

        $this->container = mock(ContainerInterface::class);
        $this->instances = ['SomeClass1' => $this->instance1];
        $this->placeholders = ['v1', 'v2', 'v3'];

        $this->strategy = new DefaultArgumentsPool($this->container->get(), $this->instances, $this->placeholders);

    });

    it('should implement ArgumentsPoolInterface', function () {

        expect($this->strategy)->toBeAnInstanceOf(ArgumentsPoolInterface::class);

    });

    describe('->arguments()', function () {

        it('should have the default behavior', function () {

            $p1 = parameter(0);                                         // placeholder
            $p2 = parameter(1, ['type' => type(false, 'SomeClass1')]);  // instance
            $p3 = parameter(2, ['type' => type(false, 'SomeClass2')]);  // container
            $p4 = parameter(3, ['type' => type(false, 'SomeClass3')]);  // placeholder
            $p5 = parameter(4, ['type' => type(true, 'SomeClass4')]);   // placeholder
            $p6 = parameter(5, ['type' => type(true, 'SomeClass5')]);   // null
            $p7 = parameter(6, ['default' => 'default']);               // default

            $this->container->has->with('SomeClass1')->returns(true);
            $this->container->has->with('SomeClass2')->returns(true);
            $this->container->has->with('SomeClass3')->returns(false);
            $this->container->has->with('SomeClass4')->returns(false);
            $this->container->has->with('SomeClass5')->returns(false);
            $this->container->get->with('SomeClass2')->returns($this->instance2);

            $test = $this->strategy->arguments([$p1, $p2, $p3, $p4, $p5, $p6, $p7]);

            expect($test->isBound($p1))->toBeTruthy();
            expect($test->isBound($p2))->toBeTruthy();
            expect($test->isBound($p3))->toBeTruthy();
            expect($test->isBound($p4))->toBeTruthy();
            expect($test->isBound($p5))->toBeTruthy();
            expect($test->isBound($p6))->toBeTruthy();
            expect($test->isBound($p7))->toBeTruthy();

            expect($test->argument($p1))->toEqual('v1');
            expect($test->argument($p2))->toEqual($this->instance1);
            expect($test->argument($p3))->toEqual($this->instance2);
            expect($test->argument($p4))->toEqual('v2');
            expect($test->argument($p5))->toEqual('v3');
            expect($test->argument($p6))->toBeNull();
            expect($test->argument($p7))->toEqual('default');

        });

    });

});
