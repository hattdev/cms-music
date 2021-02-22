<?php
use Illuminate\Database\Seeder;
    use Modules\Content\Models\Content;

    class ContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        factory(\Modules\Content\Models\BaiHatContent::class,100)->create();
        factory(\Modules\Content\Models\VideoContent::class,100)->create();
        factory(\Modules\Content\Models\TacGiaContent::class,100)->create();
    }
}
