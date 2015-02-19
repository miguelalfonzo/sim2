<?php

use \Log;

/*
|--------------------------------------------------------------------------
| Application & Route Filters
|--------------------------------------------------------------------------
|
| Below you will find the "before" and "after" events for the application
| which may be used to do any work before or after a request into your
| application. Here you may also register your custom route filters.
|
*/

App::before(function ($request) {

});

App::after(function ($request, $response) {
    //
});

/*
|--------------------------------------------------------------------------
| Authentication Filters
|--------------------------------------------------------------------------
|
| The following filters are used to verify that the user of the current
| session is logged into this application. The "basic" filter easily
| integrates HTTP Basic authentication for quick, simple checking.
|
*/

Route::filter('auth', function () {
    if (Auth::guest()) {
        if (Request::ajax()) {
            return Response::make('Unauthorized', 401);
        } else {
            return Redirect::guest('login');
        }
    }
});

Route::filter('rm', function () {

    if (Auth::check()) {

            if (Auth::user()->type != 'R' && Auth::user()->type == 'S')
                return Redirect::to('show_sup');
            if (Auth::user()->type != 'R' && Auth::user()->type == 'P')
                return Redirect::to('show_gerprod');
            if (Auth::user()->type != 'R' && Auth::user()->type == 'C')
                return Redirect::to('show_cont');
            if (Auth::user()->type != 'R' && Auth::user()->type == 'G')
                return Redirect::to('show_gercom');
            if (Auth::user()->type != 'R' && Auth::user()->type == 'T')
                return Redirect::to('show_tes');
             if (Auth::user()->type != 'R' && Auth::user()->type == 'AG')
                 return Redirect::to('registrar-fondo');
    } else {
        return Redirect::to('login');
    }
});

Route::filter('sup', function () {
    if (Auth::check()) {
        if (Auth::user()->type != 'S' && Auth::user()->type == 'R')
            return Redirect::to('show_rm');
        if (Auth::user()->type != 'S' && Auth::user()->type == 'P')
            return Redirect::to('show_gerprod');
        if (Auth::user()->type != 'S' && Auth::user()->type == 'C')
            return Redirect::to('show_cont');
        if (Auth::user()->type != 'S' && Auth::user()->type == 'T')
            return Redirect::to('show_tes');
        if (Auth::user()->type != 'S' && Auth::user()->type == 'G')
            return Redirect::to('show_gercom');
        if (Auth::user()->type != 'S' && Auth::user()->type == 'AG')
            return Redirect::to('registrar-fondo');
    } else {
        return Redirect::to('login');
    }
});

Route::filter('gerprod', function () {
    if (Auth::check()) {
        if (Auth::user()->type != 'P' && Auth::user()->type == 'R')
            return Redirect::to('show_rm');
        if (Auth::user()->type != 'P' && Auth::user()->type == 'S')
            return Redirect::to('show_sup');
        if (Auth::user()->type != 'P' && Auth::user()->type == 'C')
            return Redirect::to('show_cont');
        if (Auth::user()->type != 'P' && Auth::user()->type == 'T')
            return Redirect::to('show_tes');
        if (Auth::user()->type != 'P' && Auth::user()->type == 'G')
            return Redirect::to('show_gercom');
        if (Auth::user()->type != 'P' && Auth::user()->type == 'AG')
            return Redirect::to('registrar-fondo');
    } else {
        return Redirect::to('login');
    }
});

Route::filter('cont', function () {
    if (Auth::check()) {
        if (Auth::user()->type != 'C' && Auth::user()->type == 'R')
            return Redirect::to('show_rm');
        if (Auth::user()->type != 'C' && Auth::user()->type == 'S')
            return Redirect::to('show_sup');
        if (Auth::user()->type != 'C' && Auth::user()->type == 'P')
            return Redirect::to('show_gerprod');
        if (Auth::user()->type != 'C' && Auth::user()->type == 'G')
            return Redirect::to('show_gercom');
        if (Auth::user()->type != 'C' && Auth::user()->type == 'T')
            return Redirect::to('show_tes');
        if (Auth::user()->type != 'C' && Auth::user()->type == 'AG')
            return Redirect::to('registrar-fondo');
    } else {
        return Redirect::to('login');
    }
});

Route::filter('tes', function () {
    if (Auth::check()) {
        if (Auth::user()->type != 'T' && Auth::user()->type == 'R')
            return Redirect::to('show_rm');
        if (Auth::user()->type != 'T' && Auth::user()->type == 'S')
            return Redirect::to('show_sup');
        if (Auth::user()->type != 'T' && Auth::user()->type == 'P')
            return Redirect::to('show_gerprod');
        if (Auth::user()->type != 'T' && Auth::user()->type == 'G')
            return Redirect::to('show_gercom');
        if (Auth::user()->type != 'T' && Auth::user()->type == 'C')
            return Redirect::to('show_cont');
        if (Auth::user()->type != 'T' && Auth::user()->type == 'AG')
            return Redirect::to('registrar-fondo');
    } else {
        return Redirect::to('login');
    }
});

Route::filter('gercom', function () {
    if (Auth::check()) {
        if (Auth::user()->type != 'G' && Auth::user()->type == 'R')
            return Redirect::to('show_rm');
        if (Auth::user()->type != 'G' && Auth::user()->type == 'S')
            return Redirect::to('show_sup');
        if (Auth::user()->type != 'G' && Auth::user()->type == 'P')
            return Redirect::to('show_gerprod');
        if (Auth::user()->type != 'G' && Auth::user()->type == 'T')
            return Redirect::to('show_tes');
        if (Auth::user()->type != 'G' && Auth::user()->type == 'C')
            return Redirect::to('show_cont');
        if (Auth::user()->type != 'G' && Auth::user()->type == 'AG')
            return Redirect::to('registrar-fondo');
    } else {
        return Redirect::to('login');
    }
});

//Asistente de Gerencia
Route::filter('ager' , function(){

    if (Auth::check()) {
        if (Auth::user()->type != 'AG' && Auth::user()->type == 'R')
            return Redirect::to('show_rm');
        if (Auth::user()->type != 'AG' && Auth::user()->type == 'S')
            return Redirect::to('show_sup');
        if (Auth::user()->type != 'AG' && Auth::user()->type == 'P')
            return Redirect::to('show_gerprod');
        if (Auth::user()->type != 'AG' && Auth::user()->type == 'T')
            return Redirect::to('show_tes');
        if (Auth::user()->type != 'AG' && Auth::user()->type == 'G')
            return Redirect::to('show_gercom');
        if (Auth::user()->type != 'AG' && Auth::user()->type == 'C')
            return Redirect::to('show_cont');
    } else {
        return Redirect::to('login');
    }
});

Route::filter('auth.basic', function () {
    return Auth::basic();
});

Route::filter('active-user',function(){
    if(Auth::user()->active == 0) {
        return Redirect::to('login');
    }
});

Route::filter('rm_cont', function () 
{
    if (Auth::check()) 
    {
        if (! (Auth::user()->type == 'R' || Auth::user()->type == 'C' ))
        {
            if (Auth::user()->type == 'S')
            {
                return Redirect::to('show_sup');
            }
            else if (Auth::user()->type == 'P')
            {
                return Redirect::to('show_gerprod');
            }
            else if(Auth::user()->type == 'G')
            {
                return Redirect::to('show_gercom');
            }
            else if (Auth::user()->type == 'T')
            {
                return Redirect::to('show_tes');
            }    
            else if (Auth::user()->type == 'AG')
            {
                return Redirect::to('registrar-fondo');
            }
            else
            {
                return Redirect::to('login');           
            }        
        }
    }
    else
    {
        return Redirect::to('login');
    }
});

Route::filter('sup_gerprod', function () 
{
    if (Auth::check()) 
    {
        if (! (Auth::user()->type == 'S' || Auth::user()->type == 'P' ))
        {
            if (Auth::user()->type == 'C')
            {
                return Redirect::to('show_cont');
            }
            else if(Auth::user()->type == 'G')
            {
                return Redirect::to('show_gercom');
            }
            else if (Auth::user()->type == 'T')
            {
                return Redirect::to('show_tes');
            }    
            else if (Auth::user()->type == 'AG')
            {
                return Redirect::to('registrar-fondo');
            }
            else if (Auth::user()->type == 'R')
            { 
                return Redirect::to('show_rm');
            }
            else
            {
                return Redirect::to('login');           
            }        
        }
    }
    else
    {
        return Redirect::to('login');
    }
});


/*
|--------------------------------------------------------------------------
| Guest Filter
|--------------------------------------------------------------------------
|
| The "guest" filter is the counterpart of the authentication filters as
| it simply checks that the current user is not logged in. A redirect
| response will be issued if they are, which you may freely change.
|
*/

Route::filter('guest', function () {
    if (Auth::check()) return Redirect::to('/');
});

/*
|--------------------------------------------------------------------------
| CSRF Protection Filter
|--------------------------------------------------------------------------
|
| The CSRF filter is responsible for protecting your application against
| cross-site request forgery attacks. If this special token in a user
| session does not match the one given in this request, we'll bail.
|
*/

Route::filter('csrf', function () {
    if (Session::token() !== Input::get('_token')) {
        throw new Illuminate\Session\TokenMismatchException;
    }
});
