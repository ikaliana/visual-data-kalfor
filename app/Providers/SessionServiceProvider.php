<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class SessionServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer('*', function ($view) {
            $loginfo  = array(
                'islogin' => false,
                'id' => 0,
                'user' => '',
                'name' => '',
                'roles' => [],
                'is_klhk' => false,
                'is_admin' => false
            );

            if(session()->has('userinfo')) {
                $loginfo['islogin'] = true;
                $loginfo['id'] = session()->get('userinfo.id');
                $loginfo['user'] = session()->get('userinfo.email');
                $loginfo['name'] = session()->get('userinfo.name');

                $roles = session()->get('roles');
                $loginfo['roles'] = $roles;

                foreach($roles as $role) {
                    if(!$loginfo['is_klhk']) $loginfo['is_klhk'] = ($role == 'Administrator' || $role == 'KLHK');
                    if(!$loginfo['is_admin']) $loginfo['is_admin'] = ($role == 'Administrator');
                }
            }

            $view->with('loginfo', $loginfo);

        });
    }
}
