<?php namespace Holmes\Minutes;

use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\AliasLoader;
use Config;

class MinutesServiceProvider extends ServiceProvider {

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register() {}

  /**
   * Setup the service provider.
   *
   * @return void
   */
  public function boot()
  {
    $this->package('holmes/minutes');
    AliasLoader::getInstance()->alias(Config::get('minutes::alias'), 'Holmes\Minutes\Minutes');
  }
}