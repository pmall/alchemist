<?php

require __DIR__ . '/../factories.php';

use function Eloquent\Phony\mock;

use Psr\Container\ContainerInterface;

use Ellipse\Resolving\Pools\ArgumentsPoolInterface;
use Ellipse\Resolving\Pools\ContainerEntries;

describe('ContainerEntries', function () {

    beforeEach(function () {

        $this->container = mock(ContainerInterface::class);

        $this->strategy = new ContainerEntries($this->container->get());

    });

    it('should implement ArgumentsPoolInterface', function () {

        expect($this->strategy)->toBeAnInstanceOf(ArgumentsPoolInterface::class);

    });

    describe('->arguments()', function () {

        it('should bind parameters with a class type hint to container entries with this class name as identifier', function () {

            $instance = new class {};

            $p1 = parameter(0);
            $p2 = parameter(1, ['type' => type(false)]);
            $p3 = parameter(2, ['type' => type(false, 'SomeClass')]);
            $p4 = parameter(3, ['type' => type(false, 'SomeOtherClass')]);

            $ps = [$p1, $p2, $p3, $p4];

            $this->container->has->with('SomeClass')->returns(true);
            $this->container->has->with('SomeOtherClass')->returns(false);

            $this->container->get->with('SomeClass')->returns($instance);

            $test = $this->strategy->arguments($ps);

            expect($test->unbound($ps))->toEqual([$p1, $p2, $p4]);
            expect($test->value($p3))->toBe($instance);

        });

    });

});
