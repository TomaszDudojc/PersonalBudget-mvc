<?php

namespace App\Models;

use PDO;
use \App\Auth;
use \App\Dates;
use \Core\View;

class Balances extends \Core\Model
{
    public static function getIncomeCategories()
	{			
		$db = static::getDB();
		$currentMonth = Dates::getCurrentMonth();
		//var_dump($currentMonth);

        $result= $db->query("SELECT incomes_category_assigned_to_users.name, incomes.income_category_assigned_to_user_id, incomes.date_of_income, incomes.amount, SUM(incomes.amount) AS amountOfIncomes FROM incomes, incomes_category_assigned_to_users, users  WHERE  users.id=$_SESSION[user_id] AND incomes.date_of_income LIKE'$currentMonth-%'  AND  users.id=incomes_category_assigned_to_users.user_id AND users.id=incomes.user_id AND incomes.income_category_assigned_to_user_id=incomes_category_assigned_to_users.id GROUP BY incomes.income_category_assigned_to_user_id ORDER BY AmountOfIncomes DESC");		

		$numberOfincomeCategories = $result->rowCount();

		if ($numberOfincomeCategories==0){
			//var_dump($numberOfincomeCategories);
			return false;	
		}		 		
	
		else{
			$incomeCategories= $result->fetchAll(PDO::FETCH_ASSOC);						
			//var_dump($incomeCategories);
			return $incomeCategories;
		}		
	}	

	public static function getAmountOfAllIncomes()
	{	
		$db = static::getDB();
		$currentMonth = Dates::getCurrentMonth();

		$amountOfAllIncomes= $db->query("SELECT SUM(incomes.amount) AS AmountOfAllIncomes FROM incomes WHERE  incomes.user_id=$_SESSION[user_id] AND incomes.date_of_income LIKE '$currentMonth-%' ")->fetch();			
				
		//var_dump( $amountOfAllIncomes);
		return $amountOfAllIncomes;
	}
	
	
	public static function getIncomesInCategory()
	{
		$db = static::getDB();
		$currentMonth = Dates::getCurrentMonth();

		$incomesInCategory=$db->query("SELECT incomes_category_assigned_to_users.name, incomes.income_category_assigned_to_user_id, users.name, incomes.date_of_income, incomes.income_comment, incomes.amount FROM incomes, incomes_category_assigned_to_users, users  WHERE  users.id='$_SESSION[user_id]' AND incomes.date_of_income LIKE'$currentMonth-%'  AND  users.id=incomes_category_assigned_to_users.user_id AND users.id=incomes.user_id AND incomes.income_category_assigned_to_user_id=incomes_category_assigned_to_users.id  ORDER BY incomes.date_of_income")->fetchAll(PDO::FETCH_ASSOC);

		//var_dump( $incomesInCategory);
		return $incomesInCategory;
	}
}

