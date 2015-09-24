<?php		

	use \DB;
	use \Carbon\Carbon;

	$qryProducts =  
		DB::table( '( SELECT DISTINCT CODARTICULO FROM VTA.PPTO_VTA WHERE ANIO = ' . Carbon::now()->year . ') P' )
        ->select( 'F.CODIGO ID' , 'F.NOMBRE DESCRIPCION' )
        ->leftJoin( 'VTA.FOPRTE A' , 'P.CODARTICULO' , '=' , 'A.FOALIAS' )
        ->leftJoin( TB_VTA_TABLAS.' F' , function( $join )
        {
	        $join->on( 'F.tipo' , '=' , TIPO_FAMILIA )->on( 'F.CODIGO' , '=' , 'A.FOFAMILIA' );
        })
        ->where('F.CODIGO', '=', 21)
        ->distinct()->orderBy( 'F.NOMBRE' , 'ASC' );

?>
