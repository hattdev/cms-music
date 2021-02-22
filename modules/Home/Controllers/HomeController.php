<?php


    namespace Modules\Home\Controllers;


    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Schema;
    use Modules\ModuleController;

    class HomeController extends ModuleController
    {
        public function updateDatabase(Request $request){

            Schema::table('invoice', function (Blueprint $table) {
                if (!Schema::hasColumn('invoice', 'real_name')) {
                    $table->string('real_name')->nullable();
                }
            });
            echo 'done';
        }
        public function index(Request $request)
        {

            return view("Home::index");
        }

    }
