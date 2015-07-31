<?php		

	use \DB;
	use \Carbon\Carbon;

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