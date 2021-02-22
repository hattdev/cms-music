<?php


    namespace Modules\Content;


    use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
    use Illuminate\Support\Facades\Route;

    class RouterServiceProvider extends ServiceProvider
    {
        /**
         * The module namespace to assume when generating URLs to actions.
         *
         * @var string
         */
        protected $moduleNamespace = 'Modules\Content\Controllers';
        protected $prefix          = 'noi-dung';

        public function boot()
        {
            parent::boot();
        }

        /**
         * Define the routes for the application.
         *
         * @return void
         */
        public function map()
        {
            $this->mapApiRoutes();
            $this->mapWebRoutes();
        }

        /**
         * Define the "web" routes for the application.
         *
         * These routes all receive session state, CSRF protection, etc.
         *
         * @return void
         */
        protected function mapWebRoutes()
        {
            Route::middleware('web')
                ->namespace($this->moduleNamespace)
                ->prefix($this->prefix)
                ->group(__DIR__.'/Routes/web.php');
        }

        /**
         * Define the "web" routes for the application.
         *
         * These routes all receive session state, CSRF protection, etc.
         *
         * @return void
         */

        protected function mapApiRoutes()
        {
            Route::prefix('api')
                ->middleware('api')
                ->namespace($this->moduleNamespace)
                ->prefix('api/'.$this->prefix)

                ->group(__DIR__.'/Routes/api.php');
        }
    }
