<?php

use \Observer\Transaction;
use \Dmkt\Solicitude;
use \Dmkt\SolicitudeDetalle;
use \Dmkt\SolicitudeClient;
use \Dmkt\SolicitudeFamily;
use \Dmkt\SolicitudeGer;
use \Common\Deposit;
use \Expense\Entry;
use \Common\Fondo;
use \System\SolicitudeHistory;


	Solicitude::observe( new Transaction() );
	SolicitudeDetalle::observe( new Transaction() );
	SolicitudeHistory::observe( new Transaction() );
	SolicitudeClient::observe( new Transaction() ); 
	SolicitudeFamily::observe( new Transaction() ); 
	SolicitudeGer::observe( new Transaction() );
	Deposit::observe( new Transaction() );
	Entry::observe( new Transaction() );
	Fondo::observe( new Transaction() );