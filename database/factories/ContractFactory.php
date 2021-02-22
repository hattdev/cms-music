<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Modules\Contract\Models\Contract;

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

$factory->define(Contract::class, function (Faker $faker) {
    [$keys, $values] = Arr::divide(CONTRACT_TYPE);
    [$keysStatus, $valuesStatus] = Arr::divide(Arr::except(CONTRACT_STATUS,[SAP_HET_HAN]));
    return [
        'contract_number' => $faker->iban("VN",null,6),
        'contract_type' => Arr::random($keys),
        'sign_date' => $faker->dateTimeBetween('-1 years','now'),
        'exp_date' => $faker->dateTimeBetween('-3 months','+1 years'),
        'auto_renewed' => Arr::random([0,1]),

        'partner_name' => $faker->unique()->name,
        'tax_code' => $faker->iban("MST",null,6),
        'phone' => $faker->unique()->phoneNumber,
        'email' => $faker->unique()->safeEmail,
        'address' => $faker->unique()->address,
        'share_rate' => $faker->numberBetween(10,70),
        'advance' => 0,
        'items_provided' => implode(',',Arr::random(ITEMS_PROVIDED,2)),
        'scope_of_supply' => implode(',',Arr::random(SCOPE_OF_SUPPLY,2)),
        'note' => $faker->paragraph(3),
        'status'  => Arr::random($keysStatus),
        'create_user'  => 1,
        'update_user'  => 1,
    ];
});
