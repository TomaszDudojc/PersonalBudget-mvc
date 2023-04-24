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
        
        View::renderTemplate('balance/index.html', [							
            'nameOfSelectedPeriod' => "Current month",
            'currentMonth' => Dates::getCurrentMonth(),
            'incomeCategoriesFromSelectedPeriod' => Balances::getIncomeCategoriesFromSelectedPeriod($currentMonth),
            'incomesFromSelectedPeriod' => Balances::getIncomesFromSelectedPeriod($currentMonth),
            'amountOfAllIncomesFromSelectedPeriod' => Balances::getAmountOfAllIncomesFromSelectedPeriod($currentMonth),
            'expenseCategoriesFromSelectedPeriod' => Balances::getExpenseCategoriesFromSelectedPeriod($currentMonth),
            'expensesFromSelectedPeriod' => Balances::getExpensesFromSelectedPeriod($currentMonth),
            'amountOfAllExpensesFromSelectedPeriod' => Balances::getAmountOfAllExpensesFromSelectedPeriod($currentMonth),
            'balance' => Balances::getBalanceFromSelectedPeriod($currentMonth)				
		]);
    }
}