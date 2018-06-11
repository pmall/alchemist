<?php

require __DIR__ . '/../factories.php';

use Ellipse\Resolving\Pools\ArgumentsPoolInterface;
use Ellipse\Resolving\Pools\DefaultValues;

describe('DefaultValues', function () {

    beforeEach(function () {

        $this->strategy = new DefaultValues;

    });

    it('should implement ArgumentsPoolInterface', function () {

        expect($this->strategy)->toBeAnInstanceOf(ArgumentsPoolInterface::class);

    });

    describe('->arguments()', function () {

        it('shound bind parameters to their default values when defined', function () {

            $p1 = parameter(0);
            $p2 = parameter(1, ['default' => 'default']);
            $p3 = parameter(2);

            $test = $this->strategy->arguments([$p1, $p2, $p3]);

            expect($test->isBound($p1))->toBeFalsy();
            expect($test->isBound($p2))->toBeTruthy();
            expect($test->isBound($p3))->toBeFalsy();

            expect($test->argument($p2))->toEqual('default');

        });

    });

});
