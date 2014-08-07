<?php

namespace Expense;

use \BaseController;
use \View;

class ExpenseController extends BaseController{

	public function show(){
		return View::make('Expense.register');
	}

	
}