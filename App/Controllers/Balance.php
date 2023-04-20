<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\Balances;
use \App\Auth;
use \App\Dates;

class Balance extends Authenticated
{

    public function indexAction()
    {
        $currentMonth = Dates::getCurrentMonth();
        
        View::renderTemplate('Balance/index.html', [							
            'currentMonth' => Dates::getCurrentMonth(),
            'incomeCategories' => Balances::getIncomeCategories($currentMonth),
            'incomesInCategory' => Balances::getIncomesInCategory($currentMonth),
            'amountOfAllIncomes' => Balances::getAmountOfAllIncomes($currentMonth),
            'expenseCategories' => Balances::getExpenseCategories($currentMonth),
            'expensesInCategory' => Balances::getExpensesInCategory($currentMonth),
            'amountOfAllExpenses' => Balances::getAmountOfAllExpenses($currentMonth)				
		]);
    }
}