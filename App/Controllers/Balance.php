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
        $startDate = $dateRange['start_date'];
        $endDate = $dateRange['end_date'];

        if($startDate > $endDate){
            Flash::addMessage("Invalid date range: ".$startDate." =/=> ".$endDate.". Starting date must be earlier than end date!", Flash::WARNING);
            View::renderTemplate('Balance/index.twig', [
                'start_date' => $startDate,
                'end_date' => $endDate
            ]);
        }
        else{
            View::renderTemplate('Balance/index.twig', [		
                'start_date' => $startDate,
                'end_date' => $endDate,                
                'incomeCategoriesFromSelectedPeriod' => Balances::getIncomeCategoriesFromSelectedDateRange($startDate, $endDate),
                'incomesFromSelectedPeriod' => Balances::getIncomesFromSelectedDateRange($startDate, $endDate),
                'amountOfAllIncomesFromSelectedPeriod' => Balances::getAmountOfAllIncomesFromSelectedDateRange($startDate, $endDate),
                'expenseCategoriesFromSelectedPeriod' => Balances::getExpenseCategoriesFromSelectedDateRange($startDate, $endDate),
                'expensesFromSelectedPeriod' => Balances::getExpensesFromSelectedDateRange($startDate, $endDate),
                'amountOfAllExpensesFromSelectedPeriod' => Balances::getAmountOfAllExpensesFromSelectedDateRange($startDate, $endDate),
                'balance' => Balances::getBalanceFromSelectedDateRange($startDate, $endDate),
                'incomeCategories' => Incomes::getIncomeCategoriesOfUser(),
                'expenseCategories' => Expenses::getExpenseCategoriesOfUser(),
                'paymentMethods' => Expenses::getPaymentMethodsOfUser(),
                'currentDate' => Dates::getCurrentDate()				
            ]);  
        }         
    }

    public function deleteIncomeAction() 
	{
        if(isset($_POST['id'])) {
			
			$income = new Incomes($_POST);           

			$income->delete();

			Flash::addMessage('Income has been removed.');

			$this->redirect('/balance/index'); 
		} 
	}

    public function editIncomeAction() 
	{
        if(isset($_POST['id'])) {
			
			$income = new Incomes($_POST);           

			$income->edit();

			Flash::addMessage('Income has been edited.');

			$this->redirect('/balance/index');
		} 
	}

    public function deleteExpenseAction() 
	{
        if(isset($_POST['id'])) {
			
			$expense = new Expenses($_POST);           

			$expense->delete();

			Flash::addMessage('Expense has been removed.');

			$this->redirect('/balance/index'); 
		} 
	}

    public function editExpenseAction() 
	{
        if(isset($_POST['id'])) {
			
			$expense = new Expenses($_POST);           

			$expense->edit();

			Flash::addMessage('Expense has been edited.');

			$this->redirect('/balance/index');          
		} 
	}	
}