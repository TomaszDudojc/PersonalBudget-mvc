<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\Expenses;
use \App\Auth;
use \App\Flash;
use \App\Dates;

class Expense extends Authenticated
{

    public function indexAction()
    {
        View::renderTemplate('Expense/index.twig', [							
			'expenseCategories' => Expenses::getExpenseCategoriesOfUser(),
            'paymentMethods' => Expenses::getpaymentMethodsOfUser(),			
			'currentDate' => Dates::getCurrentDate()				
		]);
    }

    public function createAction()
    {
		if(isset($_POST['amount'])) {
			$expense = new Expenses($_POST);
            
			if ($expense->save()) {

				Flash::addMessage('Expense has been added');

				$this->redirect('/expense/index');

			} else {
					
				View::renderTemplate('Expense/index.twig', [
					'expense' => $expense,					
					'expenseCategories' => Expenses::getExpenseCategoriesOfUser(),
                    'paymentMethods' => Expenses::getPaymentMethodsOfUser(),
					'currentDate' => Dates::getCurrentDate()					
				]);				
			} 	
		} else {
			$this->redirect('/expense/index');
        } 
    }
}