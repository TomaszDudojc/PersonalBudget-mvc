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
        $sql = "SELECT * FROM expenses_category_assigned_to_users WHERE user_id = :user_id";

        $db = static::getDB();
		$expenseCategories = $db->prepare($sql);
        $expenseCategories->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);        
		$expenseCategories->execute();

        return $expenseCategories->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getPaymentMethodsOfUser()
    {
        $sql = "SELECT * FROM payment_methods_assigned_to_users WHERE user_id = :user_id";

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
        $idOfExpenseCategory = filter_input(INPUT_POST, 'category'); 
        $idOfPaymentMethod = filter_input(INPUT_POST, 'method');      
        $this->comment =  filter_input(INPUT_POST, 'comment');      
       
        $sql = "INSERT INTO expenses VALUES (NULL, :user_id, :idOfExpenseCategory, :idOfPaymentMethod, :amount, :date, :comment )";    		
												
		$db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
        $stmt->bindValue(':idOfExpenseCategory',  $idOfExpenseCategory, PDO::PARAM_INT);
        $stmt->bindValue(':idOfPaymentMethod',  $idOfPaymentMethod, PDO::PARAM_INT);
        $stmt->bindValue(':amount', $this->amount, PDO::PARAM_STR);
        $stmt->bindValue(':date', $this->date, PDO::PARAM_STR);
        $stmt->bindValue(':comment', $this->comment, PDO::PARAM_STR);

        return $stmt->execute();        
    }	
}
