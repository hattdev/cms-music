<?php

    /** @var \Illuminate\Database\Eloquent\Factory $factory */

    use Faker\Generator as Faker;
    use Modules\Report\Models\Invoice;
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

    $factory->define(Invoice::class, function (Faker $faker) {
        [$keysStatus, $valuesStatus] = Arr::divide(INVOICE_STATUS);
        [$keysInvoice, $valuesInvoice] = Arr::divide(INVOICE_TYPES);
        $contractNumber = Contract::inRandomOrder()->first('contract_number');

        return [
            'name' => $faker->unique()->name,
            'invoice_start_date' => $faker->dateTimeBetween('-1 years', 'now'),
            'invoice_end_date'   => $faker->dateTimeBetween('-3 month', 'now'),
            'invoice_type'   => Arr::random($keysInvoice),
            'contract_number' => $contractNumber->contract_number ?? null,

            //            Bank

            'bank_account_number'        => $faker->unique()->bankAccountNumber,
            'bank_name'                  => "Vietcombank CN Hải Phòng",
            'revenue_to_phuong_nam'      => $faker->numberBetween(500, 700).'000',
            'amount_payment_for_mg'      => $faker->numberBetween(500, 700).'000',
            'amount_payment_for_partner' => $faker->numberBetween(500, 700).'000',
            'note'               => $faker->paragraph(3),

            'status'  => Arr::random($keysStatus),
            'create_user'        => 1,
            'update_user'        => 1,
        ];
    });
