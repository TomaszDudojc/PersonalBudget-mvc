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
		var_dump($currentMonth);
			
		//$incomeCategoriesAmount = $db->query("SELECT SUM(i.amount) AS amount, ica.name FROM incomes AS i, incomes_categories_assigned_to_users as ica WHERE i.user_id = {$_SESSION['user_id']} AND i.income_category_assigned_to_user_id = ica.id AND i.date_of_income BETWEEN $startDate AND $endDate GROUP BY i.income_category_assigned_to_user_id")->fetchAll(PDO::FETCH_ASSOC);


        //$result= $db->query("SELECT incomes_category_assigned_to_users.name, incomes.income_category_assigned_to_user_id, users.username, incomes.date_of_income, incomes.income_comment, incomes.amount, SUM(incomes.amount) AS AmountOfIncomes FROM incomes, incomes_category_assigned_to_users, users  WHERE  users.id='$_SESSION[id_of_logged_user]' AND incomes.date_of_income LIKE'$current_month-%'  AND  users.id=incomes_category_assigned_to_users.user_id AND users.id=incomes.user_id AND incomes.income_category_assigned_to_user_id=incomes_category_assigned_to_users.id GROUP BY incomes.income_category_assigned_to_user_id ORDER BY AmountOfIncomes DESC");
		

		//return $incomeCategoriesAmount;
		
	}	
}