<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
    use Modules\Contract\Models\Contract;
    use Modules\Partner\Models\Partner;

    /*
    |--------------------------------------------------------------------------
    | Model Factories
    |--------------------------------------------------------------------------
    |
    | This directory should contain each of the model factory definitions for
    | your application. Factories provide a convenient way to generate new
    | model instances for testing / seeding your application's database.
    |
    */

$factory->define(Partner::class, function (Faker $faker) {
    [$keysStatus, $valuesStatus] = Arr::divide(Arr::except(CONTRACT_STATUS,[SAP_HET_HAN]));
    $contractNumber = Contract::inRandomOrder()->first('contract_number');

    return [
        'contract_number' => $contractNumber,
        'name' => $faker->unique()->name,
        'note' => $faker->paragraph(3),
        'status'  => Arr::random($keysStatus),
        'create_user'  => 1,
        'update_user'  => 1,
    ];
});
