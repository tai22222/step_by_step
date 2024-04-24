<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Inertia\Inertia;

class AppServiceProvider extends ServiceProvider
{
  /**
   * Register any application services.
   *
   * @return void
   */
  public function register()
  {
    //
  }

  /**
   * Bootstrap any application services.
   *
   * @return void
   */
  public function boot()
  {
    // フロント側とサーバ側のInertiaのバージョン不一致を解決させる
    Inertia::version(function () {
      return md5_file(public_path('mix-manifest.json'));
    });
  }
}
