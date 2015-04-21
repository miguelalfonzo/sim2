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
use \Dmkt\Solicitud\Periodo;
use \System\SolicitudeHistory;
use \Expense\Expense;
use \Expense\ExpenseItem;


	Solicitude::observe( new Transaction() );
	SolicitudeDetalle::observe( new Transaction() );
	SolicitudeHistory::observe( new Transaction() );
	SolicitudeClient::observe( new Transaction() ); 
	SolicitudeFamily::observe( new Transaction() ); 
	SolicitudeGer::observe( new Transaction() );
	Deposit::observe( new Transaction() );
	Entry::observe( new Transaction() );
	Fondo::observe( new Transaction() );
	Periodo::observe( new Transaction() );
	Expense::observe( new Transaction() );
	ExpenseItem::observe( new Transaction() );