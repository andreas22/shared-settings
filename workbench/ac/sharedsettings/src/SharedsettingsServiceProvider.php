<?php namespace Ac\SharedSettings;

use Illuminate\Support\ServiceProvider;

class SharedsettingsServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
        $this->package('ac/sharedsettings', 'sharedsettings', __DIR__);
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
        include __DIR__.'/routes.php';
        include __DIR__.'/filters/api-filters.php';

        $this->app->bind('Ac\SharedSettings\Repositories\APIUsersRepositoryInterface', 'Ac\SharedSettings\Repositories\DbAPIUsersRepository');
        $this->app->bind('Ac\SharedSettings\Repositories\DataRepositoryInterface', 'Ac\SharedSettings\Repositories\DbDataRepository');
        $this->app->bind('Ac\SharedSettings\Repositories\APIFiltersRepositoryInterface', 'Ac\SharedSettings\Repositories\APIFiltersRepository');
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array();
	}

}
