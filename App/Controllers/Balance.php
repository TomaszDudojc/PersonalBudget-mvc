<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\Balances;
use \App\Auth;
use \App\Dates;
use \App\Flash;
use \App\Models\Incomes;
use \App\Models\Expenses;

class Balance extends Authenticated
{
    public function indexAction()
    {
        $currentYear = date('Y');
        $currentMonth = date('m');        

        if(!isset($_POST['period'])){
            $period = $currentMonth;
        }
        else{
            $period = $_POST['period'];      
        }        
    
        $dateRange = Dates::validateDate($period, $currentYear, $currentMonth);
        $_SESSION['start_date'] = $dateRange['start_date'];
        $_SESSION['end_date'] = $dateRange['end_date'];       

        if($_SESSION['start_date'] > $_SESSION['end_date']){
            Flash::addMessage("Invalid date range: ".$_SESSION['start_date']." =/=> ".$_SESSION['end_date'].". Start date must be earlier than end date!", Flash::WARNING);
            View::renderTemplate('Balance/index.twig', [
                'start_date' => $_SESSION['start_date'],
                'end_date' => $_SESSION['end_date']
            ]);
        }
        else{
            View::renderTemplate('Balance/index.twig', [		
                'start_date' => $_SESSION['start_date'],
                'end_date' => $_SESSION['end_date'],                
                'incomeCategoriesFromSelectedPeriod' => Balances::getIncomeCategoriesFromSelectedDateRange($_SESSION['start_date'], $_SESSION['end_date']),
                'incomesFromSelectedPeriod' => Balances::getIncomesFromSelectedDateRange($_SESSION['start_date'], $_SESSION['end_date']),
                'amountOfAllIncomesFromSelectedPeriod' => Balances::getAmountOfAllIncomesFromSelectedDateRange($_SESSION['start_date'], $_SESSION['end_date']),
                'expenseCategoriesFromSelectedPeriod' => Balances::getExpenseCategoriesFromSelectedDateRange($_SESSION['start_date'], $_SESSION['end_date']),
                'expensesFromSelectedPeriod' => Balances::getExpensesFromSelectedDateRange($_SESSION['start_date'], $_SESSION['end_date']),
                'amountOfAllExpensesFromSelectedPeriod' => Balances::getAmountOfAllExpensesFromSelectedDateRange($_SESSION['start_date'], $_SESSION['end_date']),
                'balance' => Balances::getBalanceFromSelectedDateRange($_SESSION['start_date'], $_SESSION['end_date']),
                'incomeCategories' => Incomes::getIncomeCategoriesOfUser(),
                'expenseCategories' => Expenses::getExpenseCategoriesOfUser(),
                'paymentMethods' => Expenses::getPaymentMethodsOfUser()                				
            ]);  
        }         
    }

    public function deleteIncomeAction() 
	{
        if(isset($_POST['id'])) {
			
			$income = new Incomes($_POST);           

			$income->delete();

			Flash::addMessage('Income has been deleted.');

			View::renderTemplate('Balance/index.twig', [		
                'start_date' => $_SESSION['start_date'],
                'end_date' => $_SESSION['end_date'],                
                'incomeCategoriesFromSelectedPeriod' => Balances::getIncomeCategoriesFromSelectedDateRange($_SESSION['start_date'], $_SESSION['end_date']),
                'incomesFromSelectedPeriod' => Balances::getIncomesFromSelectedDateRange($_SESSION['start_date'], $_SESSION['end_date']),
                'amountOfAllIncomesFromSelectedPeriod' => Balances::getAmountOfAllIncomesFromSelectedDateRange($_SESSION['start_date'], $_SESSION['end_date']),
                'expenseCategoriesFromSelectedPeriod' => Balances::getExpenseCategoriesFromSelectedDateRange($_SESSION['start_date'], $_SESSION['end_date']),
                'expensesFromSelectedPeriod' => Balances::getExpensesFromSelectedDateRange($_SESSION['start_date'], $_SESSION['end_date']),
                'amountOfAllExpensesFromSelectedPeriod' => Balances::getAmountOfAllExpensesFromSelectedDateRange($_SESSION['start_date'], $_SESSION['end_date']),
                'balance' => Balances::getBalanceFromSelectedDateRange($_SESSION['start_date'], $_SESSION['end_date']),
                'incomeCategories' => Incomes::getIncomeCategoriesOfUser(),
                'expenseCategories' => Expenses::getExpenseCategoriesOfUser(),
                'paymentMethods' => Expenses::getPaymentMethodsOfUser()                				
            ]);   
		} 
	}

    public function editIncomeAction() 
	{
        if(isset($_POST['id'])) {
			
			$income = new Incomes($_POST);           

			$income->edit();

			Flash::addMessage('Income has been edited.');

			View::renderTemplate('Balance/index.twig', [		
                'start_date' => $_SESSION['start_date'],
                'end_date' => $_SESSION['end_date'],                
                'incomeCategoriesFromSelectedPeriod' => Balances::getIncomeCategoriesFromSelectedDateRange($_SESSION['start_date'], $_SESSION['end_date']),
                'incomesFromSelectedPeriod' => Balances::getIncomesFromSelectedDateRange($_SESSION['start_date'], $_SESSION['end_date']),
                'amountOfAllIncomesFromSelectedPeriod' => Balances::getAmountOfAllIncomesFromSelectedDateRange($_SESSION['start_date'], $_SESSION['end_date']),
                'expenseCategoriesFromSelectedPeriod' => Balances::getExpenseCategoriesFromSelectedDateRange($_SESSION['start_date'], $_SESSION['end_date']),
                'expensesFromSelectedPeriod' => Balances::getExpensesFromSelectedDateRange($_SESSION['start_date'], $_SESSION['end_date']),
                'amountOfAllExpensesFromSelectedPeriod' => Balances::getAmountOfAllExpensesFromSelectedDateRange($_SESSION['start_date'], $_SESSION['end_date']),
                'balance' => Balances::getBalanceFromSelectedDateRange($_SESSION['start_date'], $_SESSION['end_date']),
                'incomeCategories' => Incomes::getIncomeCategoriesOfUser(),
                'expenseCategories' => Expenses::getExpenseCategoriesOfUser(),
                'paymentMethods' => Expenses::getPaymentMethodsOfUser()                				
            ]);  
		} 
	}

    public function deleteExpenseAction() 
	{
        if(isset($_POST['id'])) {
			
			$expense = new Expenses($_POST);           

			$expense->delete();

			Flash::addMessage('Expense has been deleted.');

			View::renderTemplate('Balance/index.twig', [		
                'start_date' => $_SESSION['start_date'],
                'end_date' => $_SESSION['end_date'],                
                'incomeCategoriesFromSelectedPeriod' => Balances::getIncomeCategoriesFromSelectedDateRange($_SESSION['start_date'], $_SESSION['end_date']),
                'incomesFromSelectedPeriod' => Balances::getIncomesFromSelectedDateRange($_SESSION['start_date'], $_SESSION['end_date']),
                'amountOfAllIncomesFromSelectedPeriod' => Balances::getAmountOfAllIncomesFromSelectedDateRange($_SESSION['start_date'], $_SESSION['end_date']),
                'expenseCategoriesFromSelectedPeriod' => Balances::getExpenseCategoriesFromSelectedDateRange($_SESSION['start_date'], $_SESSION['end_date']),
                'expensesFromSelectedPeriod' => Balances::getExpensesFromSelectedDateRange($_SESSION['start_date'], $_SESSION['end_date']),
                'amountOfAllExpensesFromSelectedPeriod' => Balances::getAmountOfAllExpensesFromSelectedDateRange($_SESSION['start_date'], $_SESSION['end_date']),
                'balance' => Balances::getBalanceFromSelectedDateRange($_SESSION['start_date'], $_SESSION['end_date']),
                'incomeCategories' => Incomes::getIncomeCategoriesOfUser(),
                'expenseCategories' => Expenses::getExpenseCategoriesOfUser(),
                'paymentMethods' => Expenses::getPaymentMethodsOfUser()                				
            ]);   
		} 
	}

    public function editExpenseAction() 
	{
        if(isset($_POST['id'])) {
			
			$expense = new Expenses($_POST);           

			$expense->edit();

			Flash::addMessage('Expense has been edited.');
			
            View::renderTemplate('Balance/index.twig', [		
                'start_date' => $_SESSION['start_date'],
                'end_date' => $_SESSION['end_date'],                
                'incomeCategoriesFromSelectedPeriod' => Balances::getIncomeCategoriesFromSelectedDateRange($_SESSION['start_date'], $_SESSION['end_date']),
                'incomesFromSelectedPeriod' => Balances::getIncomesFromSelectedDateRange($_SESSION['start_date'], $_SESSION['end_date']),
                'amountOfAllIncomesFromSelectedPeriod' => Balances::getAmountOfAllIncomesFromSelectedDateRange($_SESSION['start_date'], $_SESSION['end_date']),
                'expenseCategoriesFromSelectedPeriod' => Balances::getExpenseCategoriesFromSelectedDateRange($_SESSION['start_date'], $_SESSION['end_date']),
                'expensesFromSelectedPeriod' => Balances::getExpensesFromSelectedDateRange($_SESSION['start_date'], $_SESSION['end_date']),
                'amountOfAllExpensesFromSelectedPeriod' => Balances::getAmountOfAllExpensesFromSelectedDateRange($_SESSION['start_date'], $_SESSION['end_date']),
                'balance' => Balances::getBalanceFromSelectedDateRange($_SESSION['start_date'], $_SESSION['end_date']),
                'incomeCategories' => Incomes::getIncomeCategoriesOfUser(),
                'expenseCategories' => Expenses::getExpenseCategoriesOfUser(),
                'paymentMethods' => Expenses::getPaymentMethodsOfUser()                				
            ]);  
		} 
	}	
}