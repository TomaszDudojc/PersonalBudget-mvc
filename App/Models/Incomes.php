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
        $sql = "SELECT * FROM incomes_category_assigned_to_users WHERE user_id = :user_id";

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
        $idOfIncomeCategory = filter_input(INPUT_POST, 'category');    
        $this->comment =  filter_input(INPUT_POST, 'comment');      
       
        $sql = "INSERT INTO incomes VALUES (NULL, :user_id, :idOfIncomeCategory, :amount, :date, :comment )";    		
												
		$db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
        $stmt->bindValue(':idOfIncomeCategory',  $idOfIncomeCategory, PDO::PARAM_INT);
        $stmt->bindValue(':amount', $this->amount, PDO::PARAM_STR);
        $stmt->bindValue(':date', $this->date, PDO::PARAM_STR);
        $stmt->bindValue(':comment', $this->comment, PDO::PARAM_STR);

        return $stmt->execute();        
    }	
}
