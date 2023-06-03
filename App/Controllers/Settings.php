<?php

namespace App\Controllers;

use \Core\View;
use \App\Auth;
use \App\Flash;
use \App\Dates;
use \App\Models\Incomes;
use \App\Models\Expenses;
use \App\Models\User;

class Settings extends Authenticated
{
    protected function before()
	{	
		parent::before();
		$this->user = Auth::getUser();
	}

    public function indexAction()
    {
        View::renderTemplate('Settings/index.twig', [							
			'incomeCategories' => Incomes::getIncomeCategoriesOfUser(),
            'incomeCategories' => Incomes::getIncomeCategoriesOfUser(),
            'expenseCategories' => Expenses::getExpenseCategoriesOfUser(),
            'paymentMethods' => Expenses::getPaymentMethodsOfUser(),
            'user' => $this->user			
		]);
    }

    public function editUserProfileAction()
    {
        if(isset($_POST['name'])){
            if ($this->user->updateProfile($_POST)) {

                Flash::addMessage('User profile has been edited.');
    
                $this->redirect('/settings/index');
    
            } else {
                Flash::addMessage('Email already taken',Flash::WARNING);	
					
				View::renderTemplate('Settings/index.twig', [
                    'incomeCategories' => Incomes::getIncomeCategoriesOfUser(),
                    'incomeCategories' => Incomes::getIncomeCategoriesOfUser(),
                    'expenseCategories' => Expenses::getExpenseCategoriesOfUser(),
                    'paymentMethods' => Expenses::getPaymentMethodsOfUser(),
                    'user' => $this->user	
				]);
            }
        } 
    }
    
    public function deleteUserProfileAction()
    {
        if(isset($_POST['deleteProfile'])) {			
            if (Incomes::deleteAllIncomeCategories() && Expenses::deleteAllExpenseCategories() && Expenses::deleteAllPaymentMethods()){
            $user = new User();
            $user->deleteProfile(); 
           
            Auth::logout(); 
    
            $this->redirect('/');
            } 
            else {
                 $this->redirect('/settings/index');
            }
        }           
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
            
            Flash::addMessage('Income category has been deleted.');               
           
            $this->redirect('/settings/index');			
		} 
	}

    public function deleteAllIncomeCategoriesAction() 
	{        
        if(isset($_POST['deleteAllIncomeCategories'])) {
			
            Incomes::deleteAllIncomeCategories();            
            
            Flash::addMessage('All categories and incomes has been deleted.');               
           
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
            
            Flash::addMessage('Expense category has been deleted.');               
           
            $this->redirect('/settings/index');			
		} 
	}

    public function deleteAllExpenseCategoriesAction() 
	{        
        if(isset($_POST['deleteAllExpenseCategories'])) {
			
            Expenses::deleteAllExpenseCategories();            
            
            Flash::addMessage('All categories and expenses has been deleted.');               
           
            $this->redirect('/settings/index');			
		} 
	}
    
    public function editPaymentMethodAction() 
	{        
        if(isset($_POST['method'])) {
			
			$expense = new Expenses($_POST);            

			if($expense->editMethod()){
                Flash::addMessage('Payment method has been edited.');               
            }
            else{
                Flash::addMessage('This method already exists! Choose a different name.', Flash::WARNING);                
            }
            $this->redirect('/settings/index');					
		} 
	}

    public function addPaymentMethodAction() 
	{        
        if(isset($_POST['new_method'])) {
			
			$expense = new Expenses($_POST);           

			if($expense->addMethod()){
                Flash::addMessage('Payment method has been added.');               
            }
            else{
                Flash::addMessage('This method already exists! Choose a different name.', Flash::WARNING);                
            }
            $this->redirect('/settings/index');			
		} 
	}

    public function deletePaymentMethodAction() 
	{        
        if(isset($_POST['method'])) {
			
			$expense = new Expenses($_POST);           

			$expense->deleteMethod();
            
            Flash::addMessage('Payment method has been deleted.');               
           
            $this->redirect('/settings/index');			
		} 
	}
    
    public function deleteAllPaymentMethodsAction() 
	{        
        if(isset($_POST['deleteAllPaymentMethods'])) {
			
            Expenses::deleteAllPaymentMethods();            
            
            Flash::addMessage('All payment methods and expenses has been deleted.');               
           
            $this->redirect('/settings/index');			
		} 
	}
}