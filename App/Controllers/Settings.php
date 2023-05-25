<?php

namespace App\Controllers;

use \Core\View;
use \App\Auth;
use \App\Flash;
use \App\Dates;
use \App\Models\Incomes;
use \App\Models\Expenses;

class Settings extends Authenticated
{

    public function indexAction()
    {
        View::renderTemplate('Settings/index.html', [							
			'incomeCategories' => Incomes::getIncomeCategoriesOfUser(),
            'incomeCategories' => Incomes::getIncomeCategoriesOfUser(),
            'expenseCategories' => Expenses::getExpenseCategoriesOfUser(),
            'paymentMethods' => Expenses::getPaymentMethodsOfUser()			
		]);
    }
    
    public function editIncomeCategoryAction() 
	{
        
        if(isset($_POST['category'])) {
			
			$income = new Incomes($_POST);           

			if($income->editCategory()){
                Flash::addMessage('Income category has been edited.');               
            }
            else{
                Flash::addMessage('This category already exists! Choose a different name.', Flash::WARNING);                
            }
            $this->redirect('/settings/index');			
		} 
	}
    
}