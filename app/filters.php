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

Route::filter('guest', function () {
    if (Auth::check()) return Redirect::to('/');
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
    if (Auth::guest()) 
    {
        if (Request::ajax())
            return Response::make('Unauthorized', 401);
        else
            return Redirect::guest('login');
    }
});
Route::filter('auth.basic', function () 
{
    return Auth::basic();
});

Route::filter('active-user',function()
{
    if(Auth::user()->active == 0)
        return Redirect::to('login');
});

Route::filter('rm', function () 
{
    if (Auth::check()) 
    {
        $type = Auth::user()->type;
        if ($type != REP_MED)
        {
            if (in_array( $type, array( SUP , GER_COM , GER_PROD , CONT , TESORERIA , ASIS_GER ) ) )
                return Redirect::to('show_user');
            else
                return Redirect::to('login');
        }
    } 
    else
        return Redirect::to('login');
});
Route::filter('sup', function () {
    if (Auth::check()) 
    {
        $type = Auth::user()->type;
        if ($type != SUP)
        {
            if ( in_array( $type, array( REP_MED , GER_COM , GER_PROD , CONT , TESORERIA , ASIS_GER ) ) )
                return Redirect::to('show_user');
            else
                return Redirect::to('login');      
        }
    } 
    else
        return Redirect::to('login');
});

Route::filter('gerprod', function () {
    if (Auth::check()) 
    {
        $type = Auth::user()->type;
        if ($type != GER_PROD)
        {
            if (in_array($type, array( REP_MED,SUP,GER_COM,CONT,TESORERIA , ASIS_GER ) ) )
                return Redirect::to('show_user');
            else
                return Redirect::to('login');
        }
    } 
    else
        return Redirect::to('login');
});

Route::filter('cont', function () {
    if (Auth::check()) 
    {
        $type = Auth::user()->type;
        if ($type != CONT)
        {
            if (in_array($type, array( REP_MED,SUP,GER_COM,GER_PROD,TESOREIA , ASIS_GER ) ) )
                return Redirect::to('show_user');
            else
                return Redirect::to('login');
        }
    }
    else
        return Redirect::to('login');
});

Route::filter('tes', function () {
    if (Auth::check()) 
    {
        $type = Auth::user()->type;
        if ($type != TESORERIA)
        {
            if (in_array($type, array( REP_MED,SUP,GER_COM,GER_PROD,CONT , ASIS_GER ) ) )
                return Redirect::to('show_user');
            else
                return Redirect::to('login');
        }
    } 
    else
        return Redirect::to('login');
});

Route::filter('gercom', function () {
    if (Auth::check()) 
    {
        $type = Auth::user()->type;
        if ($type != GER_COM)
        {
            if (in_array($type, array( REP_MED,SUP,GER_PROD,CONT,TESORERIA , ASIS_GER ) ) )
                return Redirect::to('show_user');
            else
                return Redirect::to('login');
        }
    } 
    else
        return Redirect::to('login');
});

//Asistente de Gerencia
Route::filter('ager' , function(){
    if (Auth::check()) 
    {
        $type = Auth::user()->type;
        if ($type != ASIS_GER)
        {
            if (in_array($type, array( REP_MED,SUP,GER_PROD,GER_COM,CONT,TESORERIA )))
                return Redirect::to('show_user');
            else
                return Redirect::to('login');   
        }
    }
    else
        return Redirect::to('login');
});

Route::filter('rm_cont_ager', function () 
{
    if (Auth::check()) 
    {
        $type = Auth::user()->type;
        if (! ($type == REP_MED || $type == CONT || $type == ASIS_GER ))
        {
            if( in_array( $type, array( SUP,GER_PROD,GER_COM,TESORERIA )))
                return Redirect::to('show_user');
            else
                return Redirect::to('login');                
        }
    }
    else
        return Redirect::to('login');
});

Route::filter('sup_gerprod_gercom', function () 
{
    if (Auth::check()) 
    {
        $type = Auth::user()->type;
        if (! ($type == SUP || $type == GER_PROD || $type == GER_COM ))
        {
            if( in_array( $type, array( REP_MED,CONT,TESORERIA , ASIS_GER ) ) )
                return Redirect::to('show_user');
            else
                return Redirect::to('login');                
        }
    }
    else
        return Redirect::to('login');
});

Route::filter('sup_gerprod', function () 
{
    if (Auth::check()) 
    {
        $type = Auth::user()->type;
        if (! ($type == SUP || $type == GER_PROD ))
        {
            if( in_array( $type, array( REP_MED,GER_COM,CONT,TESORERIA , ASIS_GER ) ) )
                return Redirect::to('show_user');
            else
                return Redirect::to('login');                
        }
    }
    else
        return Redirect::to('login');
});

Route::filter('rm_ager', function () 
{
    if (Auth::check()) 
    {
        $type = Auth::user()->type;
        if (! ($type == REP_MED || $type == ASIS_GER ))
        {
            if( in_array( $type, array( SUP,GER_PROD,GER_COM,CONT,TESORERIA )))
                return Redirect::to('show_user');
            else
                return Redirect::to('login');                
        }
    }
    else
        return Redirect::to('login');
});

Route::filter('sys_user', function () 
{
    if (Auth::check()) 
    {
        $type = Auth::user()->type;
        if(!in_array( $type, array( REP_MED,SUP,GER_PROD,GER_COM,CONT,TESORERIA,ASIS_GER )))
            return Redirect::to('login');  
    }
    else
        return Redirect::to('login');
});

Route::filter('rm_sup', function () 
{
    if (Auth::check()) 
    {
        $type = Auth::user()->type;
        if (! ($type == REP_MED || $type == SUP ))
        {
            if( in_array( $type, array( GER_PROD,GER_COM,CONT,TESORERIA , ASIS_GER ) ) )
                return Redirect::to('show_user');
            else
                return Redirect::to('login');                
        }
    }
    else
        return Redirect::to('login');
});

Route::filter( 'rm_sup_ager' , function () 
{
    if (Auth::check()) 
    {
        $type = Auth::user()->type;
        if (! ($type == REP_MED || $type == SUP || $type == ASIS_GER ) )
        {
            if( in_array( $type, array( GER_PROD,GER_COM,CONT,TESORERIA ) ) )
                return Redirect::to('show_user');
            else
                return Redirect::to('login');                
        }
    }
    else
        return Redirect::to('login');
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
    if (Session::token() !== Input::get('_token')) 
    {
        throw new Illuminate\Session\TokenMismatchException;
    }
});