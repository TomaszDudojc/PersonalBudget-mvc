<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\Balances;
use \App\Auth;
use \App\Dates;

class BalanceOfCurrentYear extends Authenticated
{

    public function indexAction()
    {
        $currentYear = Dates::getCurrentYear ();
        
        View::renderTemplate('balanceOfCurrentYear/index.html', [							
            'currentYear' => Dates::getCurrentYear (),
            'incomeCategories' => Balances::getIncomeCategories($currentYear),
            'incomesInCategory' => Balances::getIncomesInCategory($currentYear),
            'amountOfAllIncomes' => Balances::getAmountOfAllIncomes($currentYear),
            'expenseCategories' => Balances::getExpenseCategories($currentYear),
            'expensesInCategory' => Balances::getExpensesInCategory($currentYear),
            'amountOfAllExpenses' => Balances::getAmountOfAllExpenses($currentYear)				
		]);
    }
}