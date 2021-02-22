<?php

    use Illuminate\Database\Seeder;
    use Modules\Report\Models\Invoice;

    class InvoiceSeeder extends Seeder
    {
        /**
         * Run the database seeds.
         *
         * @return void
         */
        public function run()
        {
            //
            factory(Invoice::class, 500)->create();
        }
    }
