<?php

namespace App\Models;

use PDO;
use \App\Auth;
use \Core\View;
use \App\Flash;

class Expenses extends \Core\Model
{
    public static function getExpenseCategoriesOfUser()
    {
        $sql = "SELECT * FROM expenses_category_assigned_to_users WHERE user_id = :user_id ORDER BY name";

        $db = static::getDB();
		$expenseCategories = $db->prepare($sql);
        $expenseCategories->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);        
		$expenseCategories->execute();

        return $expenseCategories->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getPaymentMethodsOfUser()
    {
        $sql = "SELECT * FROM payment_methods_assigned_to_users WHERE user_id = :user_id ORDER BY name";

        $db = static::getDB();
		$paymentMethods = $db->prepare($sql);
        $paymentMethods->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);        
		$paymentMethods->execute();

        return $paymentMethods->fetchAll(PDO::FETCH_ASSOC);
    }

    public function save()
    {		
        $this->amount = filter_input(INPUT_POST, 'amount');
        $this->date =  filter_input(INPUT_POST, 'date');
        //$idOfExpenseCategory = filter_input(INPUT_POST, 'category'); 
        $this->category = filter_input(INPUT_POST, 'category');
       //$idOfPaymentMethod = filter_input(INPUT_POST, 'method');
       $this->method = filter_input(INPUT_POST, 'method');     
        $this->comment =  filter_input(INPUT_POST, 'comment');      
       
        $sql = "INSERT INTO expenses VALUES (NULL, :user_id, :idOfExpenseCategory, :idOfPaymentMethod, :amount, :date, :comment )";    		
												
		$db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
        //$stmt->bindValue(':idOfExpenseCategory',  $idOfExpenseCategory, PDO::PARAM_INT);
        $stmt->bindValue(':idOfExpenseCategory',  $this->category, PDO::PARAM_INT);
        $stmt->bindValue(':idOfPaymentMethod',  $this->method, PDO::PARAM_INT);
        $stmt->bindValue(':amount', $this->amount, PDO::PARAM_STR);
        $stmt->bindValue(':date', $this->date, PDO::PARAM_STR);
        $stmt->bindValue(':comment', $this->comment, PDO::PARAM_STR);

        return $stmt->execute();        
    }
    
    public function delete() 
	{
        $this->id = filter_input(INPUT_POST, 'id');

        $sql = "DELETE FROM expenses WHERE id = :id";
								
		$db = static::getDB();
        $stmt = $db->prepare($sql);
        
        $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);
		
        return $stmt->execute();  
	}

    public function edit() 
    {
        $this->id = filter_input(INPUT_POST, 'id');
        $this->amount = filter_input(INPUT_POST, 'amount');
        $this->date =  filter_input(INPUT_POST, 'date');         
        $this->comment =  filter_input(INPUT_POST, 'comment');
        if ($_POST['category'] != '') {
            $this->category = filter_input(INPUT_POST, 'category');
        } 
        if ($_POST['method'] != '') {
            $this->method = filter_input(INPUT_POST, 'method'); 
        }                 
              
       
        $sql = "UPDATE expenses
        SET amount = :amount, date_of_expense = :date, expense_comment = :comment";
        if (isset($this->category)) {
                $sql .= ', expense_category_assigned_to_user_id = :idOfExpenseCategory';
            }
        if (isset($this->method)) {
                $sql .= ', payment_method_assigned_to_user_id = :idOfPaymentMethod';
            }

        $sql .= "\nWHERE id = :id";       		
												
		$db = static::getDB();
        $stmt = $db->prepare($sql);        
        
        $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);
        $stmt->bindValue(':amount', $this->amount, PDO::PARAM_STR);
        $stmt->bindValue(':date', $this->date, PDO::PARAM_STR);
        $stmt->bindValue(':comment', $this->comment, PDO::PARAM_STR);        
        $stmt->bindValue(':idOfPaymentMethod',  $this->method, PDO::PARAM_INT);
        if (isset($this->category)){
            $stmt->bindValue(':idOfExpenseCategory',  $this->category, PDO::PARAM_INT);
        }
        if (isset($this->method)){
            $stmt->bindValue(':idOfPaymentMethod',  $this->method, PDO::PARAM_INT);
        }
        return $stmt->execute();          
    }

    public function editCategory() 
    {
        $this->category = filter_input(INPUT_POST, 'category');
        $this->new_category = filter_input(INPUT_POST, 'new_category');

        $this->new_category = mb_convert_case($this->new_category,MB_CASE_TITLE,"UTF-8");
        
        if($this->existCategory()){
            return false;
        }
        else{$sql = "UPDATE expenses_category_assigned_to_users SET name = :new_name WHERE id = :id";
    
            $db = static::getDB();
            $stmt = $db->prepare($sql); 
    
            $stmt->bindValue(':id', $this->category, PDO::PARAM_INT);
            $stmt->bindValue(':new_name', $this->new_category, PDO::PARAM_STR);
    
            return $stmt->execute();     
        }          
    }

    public function addCategory() 
    {
        $this->new_category = filter_input(INPUT_POST, 'new_category'); 
        
        $this->new_category = mb_convert_case($this->new_category,MB_CASE_TITLE,"UTF-8");
        
        if($this->existCategory()){
            return false;
        } 
        else{$sql = "INSERT INTO expenses_category_assigned_to_users VALUES (NULL, :user_id, :new_name)";           
    
            $db = static::getDB();
            $stmt = $db->prepare($sql);    
           
            $stmt->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
            $stmt->bindValue(':new_name', $this->new_category, PDO::PARAM_STR);
    
            return $stmt->execute();     
        }          
    }

    public function deleteCategory() 
    {
        $this->category = filter_input(INPUT_POST, 'category');
               
       If($this->deleteAllExpensesFromCategory()){
            $sql = "DELETE FROM expenses_category_assigned_to_users WHERE id = :id";

            $db = static::getDB();

            $stmt = $db->prepare($sql);    
            $stmt->bindValue(':id', $this->category, PDO::PARAM_INT);            
        
            return $stmt->execute();  
       }        
    }

    protected function deleteAllExpensesFromCategory() 
    {
        $sql = "DELETE FROM expenses WHERE expense_category_assigned_to_user_id = :id";
								
		$db = static::getDB();
        
        $stmt = $db->prepare($sql);        
        $stmt->bindValue(':id', $this->category, PDO::PARAM_INT);
        
        return $stmt->execute(); 
    }

    protected function existCategory() 
    {
        $sql = "SELECT * FROM expenses_category_assigned_to_users WHERE user_id = :user_id AND name = :new_name";
		
		$db = static::getDB();

		$stmt = $db->prepare($sql);

		$stmt->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);       
        $stmt->bindValue(':new_name', $this->new_category, PDO::PARAM_STR);

		$stmt->execute();	
		
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
		
		if(count($result)>0){
		return true;
        }
    }

    public function editMethod() 
    {
        $this->method = filter_input(INPUT_POST, 'method');
        $this->new_method = filter_input(INPUT_POST, 'new_method');

        $this->new_method = mb_convert_case($this->new_method,MB_CASE_TITLE,"UTF-8");
        
        if($this->existMethod()){
            return false;
        }
        else{$sql = "UPDATE payment_methods_assigned_to_users SET name = :new_name WHERE id = :id";
    
            $db = static::getDB();
            $stmt = $db->prepare($sql); 
    
            $stmt->bindValue(':id', $this->method, PDO::PARAM_INT);
            $stmt->bindValue(':new_name', $this->new_method, PDO::PARAM_STR);
    
            return $stmt->execute();     
        }          
    }

    public function addMethod() 
    {
        $this->new_method = filter_input(INPUT_POST, 'new_method'); 
        
        $this->new_method = mb_convert_case($this->new_method,MB_CASE_TITLE,"UTF-8");
        
        if($this->existMethod()){
            return false;
        } 
        else{$sql = "INSERT INTO payment_methods_assigned_to_users VALUES (NULL, :user_id, :new_name)";           
    
            $db = static::getDB();
            $stmt = $db->prepare($sql);    
           
            $stmt->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
            $stmt->bindValue(':new_name', $this->new_method, PDO::PARAM_STR);
    
            return $stmt->execute();     
        }          
    }

    public function deleteMethod() 
    {
        $this->method = filter_input(INPUT_POST, 'method');
               
        If($this->deleteAllExpensesUsingThisMethod()){
            $sql = "DELETE FROM payment_methods_assigned_to_users WHERE id = :id";

            $db = static::getDB();

            $stmt = $db->prepare($sql);    
            $stmt->bindValue(':id', $this->method, PDO::PARAM_INT);            
        
            return $stmt->execute();             
        }        
    }

    protected function existMethod() 
    {
        $sql = "SELECT * FROM payment_methods_assigned_to_users WHERE user_id = :user_id AND name = :new_name";
		
		$db = static::getDB();

		$stmt = $db->prepare($sql);

		$stmt->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);       
        $stmt->bindValue(':new_name', $this->new_method, PDO::PARAM_STR);

		$stmt->execute();	
		
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
		
		if(count($result)>0){
		return true;
        }
    }

    protected function deleteAllExpensesUsingThisMethod() 
    {
        $sql = "DELETE FROM expenses WHERE payment_method_assigned_to_user_id = :id";
								
		$db = static::getDB();
        
        $stmt = $db->prepare($sql);        
        $stmt->bindValue(':id', $this->method, PDO::PARAM_INT);
        
        return $stmt->execute(); 
    }
}
