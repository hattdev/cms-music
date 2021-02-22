<?php
use Illuminate\Database\Seeder;
use Modules\Contract\Models\Contract;

class ContractSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        factory(Contract::class,50)->create();
    }
}
