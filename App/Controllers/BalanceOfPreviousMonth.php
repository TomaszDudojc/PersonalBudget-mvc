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
        $previousMonth = Dates::getPreviousMonth();
        
        View::renderTemplate('balance/index.html', [							
            'nameOfSelectedPeriod' => "Previous month",
            'previousMonth' => Dates::getPreviousMonth(),
            'incomeCategoriesFromSelectedPeriod' => Balances::getIncomeCategoriesFromSelectedPeriod($previousMonth),
            'incomesFromSelectedPeriod' => Balances::getIncomesFromSelectedPeriod($previousMonth),
            'amountOfAllIncomesFromSelectedPeriod' => Balances::getAmountOfAllIncomesFromSelectedPeriod($previousMonth),
            'expenseCategoriesFromSelectedPeriod' => Balances::getExpenseCategoriesFromSelectedPeriod($previousMonth),
            'expensesFromSelectedPeriod' => Balances::getExpensesFromSelectedPeriod($previousMonth),
            'amountOfAllExpensesFromSelectedPeriod' => Balances::getAmountOfAllExpensesFromSelectedPeriod($previousMonth),
            'balance' => Balances::getBalanceFromSelectedPeriod($previousMonth)				
		]);
    }
}