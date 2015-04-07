<?php

use \Observer\Transaction;
use \Dmkt\Solicitude;
use \Dmkt\SolicitudeDetalle;
use \Dmkt\SolicitudeClient;
use \Dmkt\SolicitudeFamily;
use \System\SolicitudeHistory;


	Solicitude::observe( new Transaction() );
	SolicitudeDetalle::observe( new Transaction() );
	SolicitudeClient::observe( new Transaction() ); 
	SolicitudeFamily::observe( new Transaction() ); 
	SolicitudeHistory::observe( new Transaction() ); 