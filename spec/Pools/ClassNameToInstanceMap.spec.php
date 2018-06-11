<?php

require __DIR__ . '/../factories.php';

use Ellipse\Resolving\Pools\ArgumentsPoolInterface;
use Ellipse\Resolving\Pools\ClassNameToInstanceMap;

describe('ClassNameToInstanceMap', function () {

    beforeEach(function () {

        $this->instance = new class {};

        $this->strategy = new ClassNameToInstanceMap([
            'SomeClass' => $this->instance,
        ]);

    });

    it('should implement ArgumentsPoolInterface', function () {

        expect($this->strategy)->toBeAnInstanceOf(ArgumentsPoolInterface::class);

    });

    describe('->arguments()', function () {

        it('should bind parameters with a class type hint to instances associated with those class names', function () {

            $p1 = parameter(0);
            $p2 = parameter(1, ['type' => type(false)]);
            $p3 = parameter(2, ['type' => type(false, 'SomeClass')]);
            $p4 = parameter(3, ['type' => type(false, 'SomeOtherClass')]);

            $test = $this->strategy->arguments([$p1, $p2, $p3, $p4]);

            expect($test->isBound($p1))->toBeFalsy();
            expect($test->isBound($p2))->toBeFalsy();
            expect($test->isBound($p3))->toBeTruthy();
            expect($test->isBound($p4))->toBeFalsy();

            expect($test->argument($p3))->toBe($this->instance);

        });

    });

});
