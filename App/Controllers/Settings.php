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

    public function addIncomeCategoryAction() 
	{        
        if(isset($_POST['new_category'])) {
			
			$income = new Incomes($_POST);           

			if($income->addCategory()){
                Flash::addMessage('Income category has been added.');               
            }
            else{
                Flash::addMessage('This category already exists! Choose a different name.', Flash::WARNING);                
            }
            $this->redirect('/settings/index');			
		} 
	}

    public function deleteIncomeCategoryAction() 
	{        
        if(isset($_POST['category'])) {
			
			$income = new Incomes($_POST);           

			$income->deleteCategory();
            
            Flash::addMessage('Income category has been removed.');               
           
            $this->redirect('/settings/index');			
		} 
	}
    
    public function editExpenseCategoryAction() 
	{        
        if(isset($_POST['category'])) {
			
			$expense = new Expenses($_POST);           

			if($expense->editCategory()){
                Flash::addMessage('Expense category has been edited.');               
            }
            else{
                Flash::addMessage('This category already exists! Choose a different name.', Flash::WARNING);                
            }
            $this->redirect('/settings/index');			
		} 
	}

    public function addExpenseCategoryAction() 
	{        
        if(isset($_POST['new_category'])) {
			
			$expense = new Expenses($_POST);           

			if($expense->addCategory()){
                Flash::addMessage('Expense category has been added.');               
            }
            else{
                Flash::addMessage('This category already exists! Choose a different name.', Flash::WARNING);                
            }
            $this->redirect('/settings/index');			
		} 
	}

    public function deleteExpenseCategoryAction() 
	{        
        if(isset($_POST['category'])) {
			
			$expense = new Expenses($_POST);           

			$expense->deleteCategory();
            
            Flash::addMessage('Expense category has been removed.');               
           
            $this->redirect('/settings/index');			
		} 
	}    
}