<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\Balances;
use \App\Auth;
use \App\Dates;

class BalanceOfPreviousMonth extends Authenticated
{

    public function indexAction()
    {
        $previousMonth=Dates::getPreviousMonth();
        
        View::renderTemplate('balanceOfPreviousMonth/index.html', [							
            'previousMonth' => Dates::getPreviousMonth(),
            'incomeCategories' => Balances::getIncomeCategories($previousMonth),
            'incomesInCategory' => Balances::getIncomesInCategory($previousMonth),
            'amountOfAllIncomes' => Balances::getAmountOfAllIncomes($previousMonth),
            'expenseCategories' => Balances::getExpenseCategories($previousMonth),
            'expensesInCategory' => Balances::getExpensesInCategory($previousMonth),
            'amountOfAllExpenses' => Balances::getAmountOfAllExpenses($previousMonth),				
		]);
    }
}