<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\Incomes;
use \App\Auth;
use \App\Flash;


class Income extends Authenticated
{

    public function indexAction()
    {
        View::renderTemplate('Income/index.html', [							
			'incomeCategories' => Incomes::getIncomeCategoriesOfUser()				
		]);
    }

    public function createAction()
    {
		if(isset($_POST['amount'])) {
			$income = new Incomes($_POST);
            
			if ($income->save()) {

				Flash::addMessage('Income has been added');

				$this->redirect('/income/index');

			} else {
					
				View::renderTemplate('Income/index.html', [
					'income' => $income
					
				]);
				
			}
       } 	

    }
}