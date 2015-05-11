<?php

use \Observer\Transaction;

use \Dmkt\Solicitud;
use \Dmkt\SolicitudDetalle;
use \Dmkt\SolicitudClient;
use \Dmkt\SolicitudFamily;
use \Dmkt\SolicitudGer;
use \Common\Deposit;
use \Expense\Entry;
use \Common\Fondo;
use \Dmkt\Periodo;
use \System\SolicitudHistory;
use \Expense\Expense;
use \Expense\ExpenseItem;
use \Expense\ProofType;
use \Dmkt\Account;
use \Expense\Mark;
use \Expense\MarkProofAccounts;
use \Common\FileStorage;
	
	
	Solicitud::observe(			new Transaction());
	SolicitudDetalle::observe(	new Transaction());
	SolicitudHistory::observe(	new Transaction());
	SolicitudClient::observe(	new Transaction()); 
	SolicitudFamily::observe(	new Transaction()); 
	SolicitudGer::observe(		new Transaction());
	Deposit::observe(			new Transaction());
	Entry::observe(				new Transaction());
	Fondo::observe(				new Transaction());
	Periodo::observe(			new Transaction());
	Expense::observe(			new Transaction());
	ExpenseItem::observe(		new Transaction());
	ProofType::observe(			new Transaction());
	Account::observe( 			new Transaction());
	Mark::observe( 				new Transaction());
	MarkProofAccounts::observe( new Transaction());
	FileStorage::observe(		new Transaction());
