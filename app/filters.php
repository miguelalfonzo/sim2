<?php

App::before(function ($request) {});
App::after(function ($request, $response) {});

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

Route::filter('guest', function ()
{
    if ( Auth::check() ) return Redirect::to('/');
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
Route::filter('auth', function () 
{
    if ( Auth::guest() )
        if ( Request::ajax() )
            return Response::make( 'Unauthorized' , 401 );
        else
            return Redirect::guest( 'login' );
});

Route::filter( 'auth.basic' , function () 
{
    return Auth::basic();
});

Route::filter('active-user',function()
{
    if( Auth::user()->active === 0 )
        return Redirect::to('login');
});

Route::filter('sup', function () 
{
    if ( ! Auth::check() || is_null( Auth::user()->simApp ) ) 
        return Redirect::to( 'login' );
    else     
        if ( Auth::user()->type  !== SUP )
            return Redirect::to( 'show_user' );
});


Route::filter( 'cont' , function () 
{
    if ( ! Auth::check() || is_null( Auth::user()->simApp ) ) 
        return Redirect::to( 'login' );
    else
    {
        if ( Auth::user()->type  !== CONT )
            return Redirect::to( 'show_user' );
    }
});

Route::filter( 'rm' , function () 
{
    if ( ! Auth::check() || is_null( Auth::user()->simApp ) ) 
        return Redirect::to( 'login' );
    else     
        if ( Auth::user()->type  !== REP_MED )
            return Redirect::to( 'show_user' );
});

Route::filter( 'tes' , function () 
{
    if ( ! Auth::check() || is_null( Auth::user()->simApp ) ) 
        return Redirect::to( 'login' );
    else     
        if ( Auth::user()->type  !== TESORERIA )
            return Redirect::to( 'show_user' );
});

Route::filter( 'gercom' , function ()
{
    if ( ! Auth::check() || is_null( Auth::user()->simApp ) ) 
        return Redirect::to( 'login' );
    else     
        if ( Auth::user()->type  !== GER_COM )
            return Redirect::to( 'show_user' );
});

//Asistente de Gerencia
Route::filter( 'ager' , function()
{
    if ( ! Auth::check() || is_null( Auth::user()->simApp ) ) 
        return Redirect::to( 'login' );
    else     
        if ( Auth::user()->type  !== ASIS_GER )
            return Redirect::to( 'show_user' );
});

Route::filter( 'rm_cont' , function () 
{
    if ( ! Auth::check() || is_null( Auth::user()->simApp ) )
    { 
        return Redirect::to( 'login' );
    }
    elseif ( ! in_array( Auth::user()->type , array( REP_MED , CONT , ASIS_GER ) ) )
    {
        return Redirect::to( 'show_user' );
    }
});

Route::filter( 'sup_gerprod_gerprom_gercom_gerger' , function () 
{
    if ( ! Auth::check() || is_null( Auth::user()->simApp ) )
    {
        return Redirect::to( 'login' );
    }
    else
    {     
        if ( ! in_array( Auth::user()->type , array( SUP , GER_PROD , GER_PROM , GER_COM , GER_GER ) ) )
        {
            return Redirect::to( 'show_user' );
        }
    }
});

Route::filter( 'gerprod_gerprom_gercom_gerger' , function () 
{
    if ( ! Auth::check() || is_null( Auth::user()->simApp ) )
    {
        return Redirect::to( 'login' );
    }
    else
    {     
        if ( ! in_array( Auth::user()->type , array( GER_PROD , GER_PROM , GER_COM , GER_GER ) ) )
        {
            return Redirect::to( 'show_user' );
        }
    }
});

Route::filter( 'rm_sup_gerprod_gerprom_gercom_gerger_ager_cont' , function () 
{
    if ( ! Auth::check() || is_null( Auth::user()->simApp ) ) 
        return Redirect::to( 'login' );
    else     
        if ( ! in_array( Auth::user()->type , array( REP_MED , SUP , GER_PROD , GER_PROM , GER_COM , GER_GER , ASIS_GER , CONT ) ) )
            return Redirect::to( 'show_user' );
});

Route::filter( 'sys_user' , function () 
{
    if ( ( ! Auth::check() ) || ( ! is_null( Auth::user()->simApp ) && is_null( Auth::user()->simApp ) ) )
        return Redirect::to('login');
});

Route::filter( 'rm_sup_gerprod_ager' , function () 
{
    if ( ! Auth::check() || is_null( Auth::user()->simApp ) ) 
        return Redirect::to( 'login' );
    else     
        if ( ! in_array( Auth::user()->type , array( REP_MED , SUP , GER_PROD , ASIS_GER ) ) )
            return Redirect::to( 'show_user' );
});

Route::filter( 'rm_cont_tes' , function()
{
    if ( ! Auth::check() || is_null( Auth::user()->simApp ) )
    {
        return Redirect::to( 'login' );
    }
    else
    {     
        if ( ! in_array( Auth::user()->type , array( REP_MED , CONT , TESORERIA ) ) )
        {
            return Redirect::to( 'show_user' ); 
        }
    }
});

Route::filter( 'developer' , function()
{
    if ( ! Auth::check() || is_null( Auth::user()->simApp ) )
    {
        return Redirect::to( 'login' );
    }
    else
    {     
        if ( Auth::user()->id != 39 )
        {
            return Redirect::to( 'show_user' ); 
        }
    }
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
Route::filter( 'csrf' , function () 
{
    if (Session::token() !== Input::get('_token')) 
        throw new Illuminate\Session\TokenMismatchException;
});