<?php		
	use \DB;
	use \Carbon\Carbon;

	$qryProducts =  
		DB::table( '( SELECT DISTINCT CODARTICULO FROM VTA.PPTO_VTA WHERE ANIO = ' . Carbon::now()->year . ') P' )
        ->select( 'F.CODIGO ID' , 'F.NOMBRE DESCRIPCION' )
        ->leftJoin( 'VTA.FOPRTE A' , 'P.CODARTICULO' , '=' , 'A.FOALIAS' )
        ->leftJoin( 'VTA.TABLAS F' , function( $join )
        {
	        $join->on( 'F.tipo' , '=' , TIPO_FAMILIA )->on( 'F.CODIGO' , '=' , 'A.FOFAMILIA' );
        })->distinct()->orderBy( 'F.NOMBRE' , 'ASC' );

    $qryGerProds = 
    	DB::table( 'VTA.TABLAS A' )
    	->select( 'C.CODIGO')
    	->leftJoin( 'VTA.FOPRTE B' , 'B.FOFAMILIA' , '=' , 'A.CODIGO' )
    	->leftJoin( 'VTA.PPTO_VTA D' , 'B.FOALIAS' , '=' , 'D.CODARTICULO' )
    	->leftJoin( 'VTA.TABLAS C' , function( $join )
    	{
    		$join->on( 'C.TIPO' , '=' , TIPO_GERPROD )->on( 'C.CODIGO' , '=' , 'B.FOJEFPROD' );
    	})->whereIn( 'A.TIPO' , $idsProducto )->where( 'D.ANIO' , Carbon::now()->year )->distinct();
?>
