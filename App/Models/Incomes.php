<?php

namespace App\Models;

use PDO;
use \App\Auth;
use \Core\View;
use \App\Flash;

class Incomes extends \Core\Model
{
    public static function getIncomeCategoriesOfUser()
    {
        $sql = "SELECT * FROM incomes_category_assigned_to_users WHERE user_id = :user_id ORDER BY name";

        $db = static::getDB();
		$incomeCategories = $db->prepare($sql);
        $incomeCategories->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);        
		$incomeCategories->execute();

        return $incomeCategories->fetchAll(PDO::FETCH_ASSOC);
    }

    public function save()
    {		
        $this->amount = filter_input(INPUT_POST, 'amount');
        $this->date =  filter_input(INPUT_POST, 'date');         
        $this->category = filter_input(INPUT_POST, 'category');   
        $this->comment =  filter_input(INPUT_POST, 'comment');      
       
        $sql = "INSERT INTO incomes VALUES (NULL, :user_id, :idOfIncomeCategory, :amount, :date, :comment )";    		
												
		$db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);        
        $stmt->bindValue(':idOfIncomeCategory',  $this->category, PDO::PARAM_INT);
        $stmt->bindValue(':amount', $this->amount, PDO::PARAM_STR);
        $stmt->bindValue(':date', $this->date, PDO::PARAM_STR);
        $stmt->bindValue(':comment', $this->comment, PDO::PARAM_STR);

        return $stmt->execute();        
    } 
    
    public function delete() 
	{
        $this->id = filter_input(INPUT_POST, 'id');

        $sql = "DELETE FROM incomes WHERE id = :id";
								
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

        $sql = "UPDATE incomes
        SET amount = :amount, date_of_income = :date, income_comment = :comment";
        
        if (isset($this->category)) {
            $sql .= ', income_category_assigned_to_user_id = :idOfIncomeCategory';
        }

        $sql .= "\nWHERE id = :id";      		
												
		$db = static::getDB();
        $stmt = $db->prepare($sql);        
        
        $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);
        $stmt->bindValue(':amount', $this->amount, PDO::PARAM_STR);
        $stmt->bindValue(':date', $this->date, PDO::PARAM_STR);
        $stmt->bindValue(':comment', $this->comment, PDO::PARAM_STR); 

        if (isset($this->category)){
            $stmt->bindValue(':idOfIncomeCategory',  $this->category, PDO::PARAM_INT);
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
        else{$sql = "UPDATE incomes_category_assigned_to_users SET name = :new_name WHERE id = :id";
    
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
        else{$sql = "INSERT INTO incomes_category_assigned_to_users VALUES (NULL, :user_id, :new_name)";           
    
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
               
       if($this->deleteAllIncomesFromCategory()){
            $sql = "DELETE FROM incomes_category_assigned_to_users WHERE id = :id";

            $db = static::getDB();

            $stmt = $db->prepare($sql);    
            $stmt->bindValue(':id', $this->category, PDO::PARAM_INT);            
        
            return $stmt->execute();  
       }        
    }

    protected function deleteAllIncomesFromCategory() 
    {
        $sql = "DELETE FROM incomes WHERE income_category_assigned_to_user_id = :id";
								
		$db = static::getDB();
        
        $stmt = $db->prepare($sql);        
        $stmt->bindValue(':id', $this->category, PDO::PARAM_INT);
        
        return $stmt->execute(); 
    }

    protected function existCategory() 
    {
        $sql = "SELECT * FROM incomes_category_assigned_to_users WHERE user_id = :user_id AND name = :new_name";
		
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
    
    public static function deleteAllIncomes()
	{
		$sql = "DELETE FROM incomes WHERE user_id = :user_id";
								
        $db = static::getDB();
        $stmt = $db->prepare($sql);    
       
        $stmt->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);        

        return $stmt->execute();   
	}

    public static function deleteAllIncomeCategories()
	{
        if(static::deleteAllIncomes()){
            $sql = "DELETE FROM incomes_category_assigned_to_users WHERE user_id = :user_id";
								
            $db = static::getDB();
            $stmt = $db->prepare($sql);    
           
            $stmt->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);        
    
            return $stmt->execute();
        }		
	} 
}