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
        View::renderTemplate('Balance/index.html', [							
            'currentMonth' => Dates::getCurrentMonth(),
            'incomeCategories' => Balances::getIncomeCategories(),
            'incomesInCategory' => Balances::getIncomesInCategory(),
            'amountOfAllIncomes' => Balances::getAmountOfAllIncomes(),
            'expenseCategories' => Balances::getExpenseCategories(),
            'expensesInCategory' => Balances::getExpensesInCategory(),
            'amountOfAllExpenses' => Balances::getAmountOfAllExpenses()				
		]);
    }
}