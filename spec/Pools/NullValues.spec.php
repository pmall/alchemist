<?php

require __DIR__ . '/../factories.php';

use Ellipse\Resolving\Pools\ArgumentsPoolInterface;
use Ellipse\Resolving\Pools\NullValues;

describe('NullValues', function () {

    beforeEach(function () {

        $this->strategy = new NullValues;

    });

    it('should implement ArgumentsPoolInterface', function () {

        expect($this->strategy)->toBeAnInstanceOf(ArgumentsPoolInterface::class);

    });

    describe('->arguments()', function () {

        it('should bind the parameters to null when they are nullable', function () {

            $p1 = parameter(0, ['type' => type(false)]);
            $p2 = parameter(1, ['type' => type(true)]);
            $p3 = parameter(2, ['type' => type(false)]);

            $ps = [$p1, $p2, $p3];

            $test = $this->strategy->arguments($ps);

            expect($test->unbound($ps))->toEqual([$p1, $p3]);
            expect($test->value($p2))->toBeNull();

        });

    });

});
