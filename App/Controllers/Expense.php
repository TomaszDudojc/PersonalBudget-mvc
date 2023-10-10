<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\Expenses;
use \App\Auth;
use \App\Flash;
use \App\Dates;

class Expense extends Authenticated
{	
	protected function before()
	{	
		parent::before();
		$this->user = Auth::getUser();
	}
	
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

	public function limitAction()
    {	
		$user_id = $this->user->id;
		$category = $this->route_params['category'];

		echo json_encode(Expenses::getLimit($user_id, $category), JSON_UNESCAPED_UNICODE);
    }
}