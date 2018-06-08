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

        it('should bind parameters with a class type hint to instances associated with this class name', function () {

            $p1 = parameter(0);
            $p2 = parameter(1, ['type' => type(false)]);
            $p3 = parameter(2, ['type' => type(false, 'SomeClass')]);
            $p4 = parameter(3, ['type' => type(false, 'SomeOtherClass')]);

            $ps = [$p1, $p2, $p3, $p4];

            $test = $this->strategy->arguments($ps);

            expect($test->unbound($ps))->toEqual([$p1, $p2, $p4]);
            expect($test->value($p3))->toBe($this->instance);

        });

    });

});
