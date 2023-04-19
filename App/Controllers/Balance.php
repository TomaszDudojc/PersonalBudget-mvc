<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\Balances;
use \App\Auth;
use \App\Flash;
use \App\Dates;
use \App\Models\Incomes;
use \App\Models\Expenses;

class Balance extends Authenticated
{

    public function indexAction()
    {
        View::renderTemplate('Balance/index.html', [							
			'currentMonth' => Dates::getCurrentMonth(),
            'incomeCategories' => Balances::getIncomeCategories()
           // 'incomesInCategory' => Balances::getIncomesInCategory(),
           // 'amountOfAllIncomes' => Balances::getAmountOfAllIncomes()				
					
							
		]);
    }
}