<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
    use Modules\Content\Models\Content;
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
$factory->define(\Modules\Content\Models\TacGiaContent::class, function (Faker $faker)  {
    [$keysStatus, $valuesStatus] = Arr::divide(Arr::except(CONTRACT_STATUS,[SAP_HET_HAN]));
    $contractNumber = Contract::inRandomOrder()->first('contract_number');
    $contracSubtNumber = Contract::inRandomOrder()->first('contract_number');
    return [
        'channel_name'=>'Youtube',
        'singer_name'=>  $faker->unique()->name,
        'author_name'=> $faker->unique()->name,
        'author_lyric_name'=> $faker->unique()->name,
        'one_permission'=>"1 quyền ghi âm",
        'full_permission'=>"cả quyền ghi âm và quyền tác giả",
        'monopoly_permission'=>"bài hát phương Nam độc quyền",
        'contract_number' => $contractNumber->contract_number??null,
        'content_type' => TAC_GIA,
        'sign_date' => $faker->dateTimeBetween('-1 years','now'),
        'exp_date' => $faker->dateTimeBetween('-3 months','+1 years'),
        'sub_contract_number'=>$contracSubtNumber,
        'sub_contract_order'=>$faker->numberBetween(1,20),
        'lyric_song'=>$faker->paragraph(4),
        'video_url'=>"https://www.youtube.com/",
        'name' => $faker->unique()->name,
        'status'  => Arr::random($keysStatus),
        'create_user'  => 1,
        'update_user'  => 1,
    ];
});
