<?php namespace JeffLemon\Privilege;

use Illuminate\Support\ServiceProvider;

class PrivilegeServiceProvider extends ServiceProvider {

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
		$this->package('fst/privilege');

		\Route::filter('privilege.hasRole', 'JeffLemon\Privilege\Privilege@hasRoleFilter');
  		\Route::filter('privilege.hasPermission', 'JeffLemon\Privilege\Privilege@hasPermissionFilter');
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app['privilege'] = $this->app->share(function($app)
  		{
    		return new Privilege;
  		});

	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array('privilege');
	}

}

