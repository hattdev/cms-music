<?php
use Illuminate\Database\Seeder;
    use Modules\Partner\Models\Partner;

    class PartnerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        factory(Partner::class,50)->create();
    }
}
