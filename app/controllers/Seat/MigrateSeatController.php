<?php

namespace Seat;

use \BaseController;
use \Input;
use \View;
use \Exception;
use \Expense\Entry;
use \Carbon\Carbon;
use \DB;

class MigrateSeatController extends BaseController
{
	public function migrateSeats()
	{
		try
		{
			DB::beginTransaction();
			\Log::error( TB_BAGO_ASIENTO );
			$systemSeats = Entry::whereNull( 'estado' )->orderBy( 'id_solicitud' , 'asc' )->orderBy( 'tipo_asiento' , 'asc' )
						   ->orderBy( 'updated_at' , 'asc' )->orderBy( 'id' , 'asc' )->get();
			$seats = array();
			foreach( $systemSeats as $key => $systemSeat )
			{
				if( isset( $seats[ $systemSeat->id_solicitud ][ $systemSeat->tipo_asiento ] ) )
				{
					$seats[ $systemSeat->id_solicitud ][ $systemSeat->tipo_asiento ][] = $systemSeat;				
				}
				else
				{
					$seats[ $systemSeat->id_solicitud ][ $systemSeat->tipo_asiento ]   = array();
					$seats[ $systemSeat->id_solicitud ][ $systemSeat->tipo_asiento ][] = $systemSeat;			
				}
			}

			$penclave = array();
			$errors = array();
			foreach( $seats as $idSolicitud => $seatTypes )
			{
				foreach( $seatTypes as $seatType => $seats )
				{
					$origen = $seatType == 'A' ? 7 : ( $seatType == 'G' ? 1 : 0 );
					if ( $origen === 0 )
					{
						$errors[] = 'No se pudo determinar el origen del asiento ' . $seatType . ' de la solicitud ' . $idSolicitud ;
						break;
					}
					\Log::error( $origen );
					$year   = Carbon::now()->year;
					$penclave[ $idSolicitud ][ $seatType ] = SeatCod::generateSeatCod( $year , $origen );
					foreach( $seats as $key => $seat )
					{
						\Log::error( 'registro del asiento ' . $key );
						if ( $key == 0 )
						{
							$state = 'C';
						}
						else
						{
							$state = '';
						}
						if( substr( $penclave[ $idSolicitud ][ $seatType ] , 0 , 1 ) == 1 )
						{
							$seatPrefix = '0' . substr( $penclave[ $idSolicitud ][ $seatType ] , 1 , 4 ); 
						}
						else
						{
							$seatPrefix = $penclave[ $idSolicitud ][ $seatType ];
						}
						$date = Carbon::createFromFormat( 'd/m/Y' , $seat->fec_origen );
						Seat::registerSeat( $seat , $seatPrefix , $key , $date , $state );
					}
				}
			}
			\Log::error( $errors );
			DB::rollback();
			return $penclave;
		}
		catch( Exception $e )
		{
			DB::rollback();
			return $this->internalException( $e , __FUNCTION__ );
		}
	}
}