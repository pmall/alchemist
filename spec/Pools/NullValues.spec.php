<?php

require __DIR__ . '/../factories.php';

use Quanta\Injection\Pools\ArgumentsPoolInterface;
use Quanta\Injection\Pools\NullValues;

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

            $test = $this->strategy->arguments([$p1, $p2, $p3]);

            expect($test->isBound($p1))->toBeFalsy();
            expect($test->isBound($p2))->toBeTruthy();
            expect($test->isBound($p3))->toBeFalsy();

            expect($test->argument($p2))->toBeNull();

        });

    });

});
