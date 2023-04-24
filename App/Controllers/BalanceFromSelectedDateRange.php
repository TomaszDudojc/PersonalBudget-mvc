<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\Balances;
use \App\Auth;
use \App\Dates;
use \App\Flash;

class BalanceFromSelectedDateRange extends Authenticated
{

    public function indexAction()
    {           
        $startingDate = Balances::getStartingDate();
        $endDate = Balances::getEndDate();

        if($startingDate > $endDate)
            {
                Flash::addMessage("Invalid date range: ".$startingDate." =/=> ".$endDate.". Starting date must be earlier than end date!", Flash::WARNING);
                View::renderTemplate('balance/index.html', [
                    'nameOfSelectedPeriod' => $startingDate." => ".$endDate                    
                ]);
            } 
       
        else if ($startingDate == $endDate)
        {
            View::renderTemplate('balance/index.html', [				
                'startingDate' => Balances::getStartingDate(),
                'endDate' => Balances::getEndDate(),
                'nameOfSelectedPeriod' => $startingDate,
                'incomeCategoriesFromSelectedPeriod' => Balances::getIncomeCategoriesFromSelectedDateRange(),
                'incomesFromSelectedPeriod' => Balances::getIncomesFromSelectedDateRange(),
                'amountOfAllIncomesFromSelectedPeriod' => Balances::getAmountOfAllIncomesFromSelectedDateRange(),
                'expenseCategoriesFromSelectedPeriod' => Balances::getExpenseCategoriesFromSelectedDateRange(),
                'expensesFromSelectedPeriod' => Balances::getExpensesFromSelectedDateRange(),
                'amountOfAllExpensesFromSelectedPeriod' => Balances::getAmountOfAllExpensesFromSelectedDateRange(),
                'balance' => Balances::getBalanceFromSelectedDateRange()           			
            ]);
            }

        else
            {
            View::renderTemplate('balance/index.html', [				
                'startingDate' => Balances::getStartingDate(),
                'endDate' => Balances::getEndDate(),
                'nameOfSelectedPeriod' => $startingDate." => ".$endDate,
                'incomeCategoriesFromSelectedPeriod' => Balances::getIncomeCategoriesFromSelectedDateRange(),
                'incomesFromSelectedPeriod' => Balances::getIncomesFromSelectedDateRange(),
                'amountOfAllIncomesFromSelectedPeriod' => Balances::getAmountOfAllIncomesFromSelectedDateRange(),
                'expenseCategoriesFromSelectedPeriod' => Balances::getExpenseCategoriesFromSelectedDateRange(),
                'expensesFromSelectedPeriod' => Balances::getExpensesFromSelectedDateRange(),
                'amountOfAllExpensesFromSelectedPeriod' => Balances::getAmountOfAllExpensesFromSelectedDateRange(),
                'balance' => Balances::getBalanceFromSelectedDateRange()				
            ]);
            }
    }
}