<?php

namespace App\Models;

use PDO;
use \App\Auth;
use \Core\View;
use \App\Flash;
//use \App\Dates;

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
        $sql = "SELECT * FROM payment_methods_assigned_to_users WHERE user_id = :user_id and is_active != 'no' ORDER BY name";

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
        $this->category = filter_input(INPUT_POST, 'category');       
        $this->method = filter_input(INPUT_POST, 'method');     
        $this->comment =  filter_input(INPUT_POST, 'comment');      
       
        $sql = "INSERT INTO expenses VALUES (NULL, :user_id, :idOfExpenseCategory, :idOfPaymentMethod, :amount, :date, :comment )";    		
												
		$db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);        ;
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
        $current_name = filter_input(INPUT_POST, 'current_name');
        $this->id = filter_input(INPUT_POST, 'id');        
        $this->new_category = filter_input(INPUT_POST, 'new_name');
        if ($_POST['new_limit'] != '') {
            $this->new_limit = filter_input(INPUT_POST, 'new_limit');
        }
        else{
            $this->new_limit = 0;
        }

        $this->new_category = mb_convert_case($this->new_category,MB_CASE_TITLE,"UTF-8");
        
       if($this->existCategory() &&  $this->new_category != $current_name){
            return false;
        }
        else{
            $sql = "UPDATE expenses_category_assigned_to_users SET name = :new_name, month_limit = :month_limit
            WHERE id = :id";

            $db = static::getDB();
            $stmt = $db->prepare($sql); 
    
            $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);
            $stmt->bindValue(':new_name', $this->new_category, PDO::PARAM_STR);
             $stmt->bindValue(':month_limit', $this->new_limit, PDO::PARAM_STR);           
    
            return $stmt->execute();     
        }          
    }

    public function addCategory() 
    {
        if ($_POST['new_limit'] != '') {
            $this->new_limit = filter_input(INPUT_POST, 'new_limit');
        }
        else{
            $this->new_limit = 0;
        } 
        $this->new_category = filter_input(INPUT_POST, 'new_category'); 
        
        $this->new_category = mb_convert_case($this->new_category,MB_CASE_TITLE,"UTF-8");
        
        if($this->existCategory()){
            return false;
        } 
        else{$sql = "INSERT INTO expenses_category_assigned_to_users VALUES (NULL, :user_id, :new_name, :month_limit)";

            $db = static::getDB();
            $stmt = $db->prepare($sql);    
           
            $stmt->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
            $stmt->bindValue(':new_name', $this->new_category, PDO::PARAM_STR);            
            $stmt->bindValue(':month_limit', $this->new_limit, PDO::PARAM_STR);            
    
            return $stmt->execute();     
        }          
    }

    public function deleteCategory() 
    {
        $this->category = filter_input(INPUT_POST, 'id');
               
        $this->deleteAllExpensesFromCategory();
        $sql = "DELETE FROM expenses_category_assigned_to_users WHERE id = :id";

        $db = static::getDB();

        $stmt = $db->prepare($sql);    
        $stmt->bindValue(':id', $this->category, PDO::PARAM_INT);            
        
        return $stmt->execute();
    }

    protected function deleteAllExpensesFromCategory() 
    {
        $sql = "DELETE FROM expenses WHERE expense_category_assigned_to_user_id = :id";
								
		$db = static::getDB();
        
        $stmt = $db->prepare($sql);        
        $stmt->bindValue(':id', $this->category, PDO::PARAM_INT);
        
        $stmt->execute();

        $result = $stmt->rowCount();
        		
        if($result>0){
        return true;
        } 
    }

    protected function existCategory() 
    {
        $sql = "SELECT * FROM expenses_category_assigned_to_users WHERE user_id = :user_id AND name = :new_name";
		
		$db = static::getDB();

		$stmt = $db->prepare($sql);

		$stmt->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);       
        $stmt->bindValue(':new_name', $this->new_category, PDO::PARAM_STR);

		$stmt->execute();
		
        $result = $stmt->rowCount();
        		
        if($result>0){
        return true;
        } 
    }

    public function editMethod() 
    {
        $this->method = filter_input(INPUT_POST, 'id');
        $this->new_method = filter_input(INPUT_POST, 'new_name');

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
        else{$sql = "INSERT INTO payment_methods_assigned_to_users VALUES (NULL, :user_id, :new_name, 'yes')";           
    
            $db = static::getDB();
            $stmt = $db->prepare($sql);    
           
            $stmt->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
            $stmt->bindValue(':new_name', $this->new_method, PDO::PARAM_STR);
    
            return $stmt->execute();     
        }          
    }

    public function deleteMethod() 
    {
        $this->method = filter_input(INPUT_POST, 'id');

        if($this->isUsedMethod()){$sql = "UPDATE payment_methods_assigned_to_users SET is_active = 'no' WHERE id = :id";
            $db = static::getDB();
            $stmt = $db->prepare($sql);
            
            $stmt->bindValue(':id', $this->method, PDO::PARAM_INT);                    
        
            return $stmt->execute();
        }
       else{$sql = "DELETE FROM payment_methods_assigned_to_users WHERE id = :id";

            $db = static::getDB();

            $stmt = $db->prepare($sql);    
            $stmt->bindValue(':id', $this->method, PDO::PARAM_INT);            
        
            return $stmt->execute();         
       }        
    }
    
    protected function existMethod() 
    {
        $sql = "SELECT * FROM payment_methods_assigned_to_users WHERE user_id = :user_id AND name = :new_name AND is_active != 'no'";
		
		$db = static::getDB();

		$stmt = $db->prepare($sql);

		$stmt->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);       
        $stmt->bindValue(':new_name', $this->new_method, PDO::PARAM_STR);

		$stmt->execute();
		
        $result = $stmt->rowCount();
        		
        if($result>0){
        return true;
        } 
    }

    protected function isUsedMethod() 
    {
        $sql = "SELECT * FROM expenses WHERE payment_method_assigned_to_user_id = :id";
		
		$db = static::getDB();

		$stmt = $db->prepare($sql);
		      
        $stmt->bindValue(':id', $this->method, PDO::PARAM_INT);

		$stmt->execute();
		
        $result = $stmt->rowCount();
        		
        if($result>0){
        return true;
        } 
    }    

    public static function deleteAllExpenses()
	{
		$sql = "DELETE FROM expenses WHERE user_id = :user_id";
								
        $db = static::getDB();
        $stmt = $db->prepare($sql);    
       
        $stmt->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);        

        $stmt->execute();
        
        $result = $stmt->rowCount();
        		
        if($result>0){
        return true;
        }	
	}

    public static function deleteAllExpenseCategories()
	{
		static::deleteAllExpenses();
        $sql = "DELETE FROM expenses_category_assigned_to_users WHERE user_id = :user_id";
								
        $db = static::getDB();
        $stmt = $db->prepare($sql);    
           
        $stmt->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);        
    
        $stmt->execute();
       
        $result = $stmt->rowCount();
        		
        if($result>0){
        return true;
        }	
	}

    public static function deleteAllPaymentMethods()
	{
		static::deleteAllExpenses();
        $sql = "DELETE FROM payment_methods_assigned_to_users WHERE user_id = :user_id";
								
        $db = static::getDB();
        $stmt = $db->prepare($sql);    
           
        $stmt->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);        
    
        $stmt->execute();
        
        $result = $stmt->rowCount();
        		
        if($result>0){
        return true;
        }	
	}

    public function deleteExpensesFromSelectedCategory() 
    {
        $this->category = filter_input(INPUT_POST, 'category');
               
        if($this->deleteAllExpensesFromCategory()){
            return true;
        }        
    }

    public function changeCategory()
    {		
        $this->id = filter_input(INPUT_POST, 'id');
        $this->category = filter_input(INPUT_POST, 'category');
       
        $sql = "UPDATE expenses
        SET  expense_category_assigned_to_user_id = :idOfExpenseCategory
        WHERE id = :id";    		
												
		$db = static::getDB();

        $stmt = $db->prepare($sql);

        $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);
        $stmt->bindValue(':idOfExpenseCategory',  $this->category, PDO::PARAM_INT);

        return $stmt->execute();        
    }
    
    public static function getLimit($user_id, $category) 
    {
        $sql = "SELECT month_limit FROM expenses_category_assigned_to_users
        WHERE user_id = :user_id AND name = :name";
		
	    $db = static::getDB();

		$stmt = $db->prepare($sql);

		$stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);       
        $stmt->bindValue(':name', $category, PDO::PARAM_STR);

		$stmt->execute(); 
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);  
        
        return $result['month_limit'];               
    }   
    
    public static function getMonthlyExpenses($user_id, $category_id, $startDate, $endDate) 
    {          
        //$date = date('Y-m', strtotime($date));
       	
        $sql = "SELECT SUM(amount) as amount FROM expenses
        WHERE user_id = :user_id 
        AND expense_category_assigned_to_user_id = :id
        AND date_of_expense BETWEEN :start_date AND :end_date";
        //AND date_of_expense LIKE '$date-%'";        
      		
	    $db = static::getDB();

		$stmt = $db->prepare($sql);

		$stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);       
        $stmt->bindValue(':id', $category_id, PDO::PARAM_INT);
        $stmt->bindValue(':start_date', $startDate, PDO::PARAM_STR);
        $stmt->bindValue(':end_date', $endDate, PDO::PARAM_STR);  
       
		$stmt->execute(); 
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC); 
        
        return $result['amount'];                            
    }
}
