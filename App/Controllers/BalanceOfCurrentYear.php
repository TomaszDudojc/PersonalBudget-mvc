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
        
        View::renderTemplate('Balance/index.html', [							
            'nameOfSelectedPeriod' => "Current year",
            'currentYear' => Dates::getCurrentYear (),
            'incomeCategoriesFromSelectedPeriod' => Balances::getIncomeCategoriesFromSelectedPeriod($currentYear),
            'incomesFromSelectedPeriod' => Balances::getIncomesFromSelectedPeriod($currentYear),
            'amountOfAllIncomesFromSelectedPeriod' => Balances::getAmountOfAllIncomesFromSelectedPeriod($currentYear),
            'expenseCategoriesFromSelectedPeriod' => Balances::getExpenseCategoriesFromSelectedPeriod($currentYear),
            'expensesFromSelectedPeriod' => Balances::getExpensesFromSelectedPeriod($currentYear),
            'amountOfAllExpensesFromSelectedPeriod' => Balances::getAmountOfAllExpensesFromSelectedPeriod($currentYear),
            'balance' => Balances::getBalanceFromSelectedPeriod($currentYear)			
		]);
    }
}