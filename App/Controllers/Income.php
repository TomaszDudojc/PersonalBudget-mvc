<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\Incomes;
use \App\Auth;
use \App\Flash;
use \App\Dates;

class Income extends Authenticated
{

    public function indexAction()
    {
        View::renderTemplate('Income/index.html', [							
			'incomeCategories' => Incomes::getIncomeCategoriesOfUser(),
			'currentDate' => Dates::getCurrentDate()				
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
					'income' => $income,					
					'incomeCategories' => Incomes::Incomes::getIncomeCategoriesOfUser(),
					'currentDate' => Dates::getCurrentDate()					
				]);				
			} 	
		} else {
			$this->redirect('/income/index');
        } 
    }
}