<?php

namespace App\Models;

use PDO;
use \App\Auth;
use \App\Dates;
use \Core\View;

class Balances extends \Core\Model
{/////////////ICOMES/////////////////////////////////////////////////////////////////////////////////////////////////////
    public static function getIncomeCategories($period)
	{			
		$db = static::getDB();
		//$currentMonth = Dates::getCurrentMonth();
		var_dump($period);

        $result= $db->query("SELECT incomes_category_assigned_to_users.name, incomes.income_category_assigned_to_user_id, incomes.date_of_income, incomes.amount, SUM(incomes.amount) AS amountOfIncomes FROM incomes, incomes_category_assigned_to_users, users  WHERE  users.id=$_SESSION[user_id] AND incomes.date_of_income LIKE'$period-%'  AND  users.id=incomes_category_assigned_to_users.user_id AND users.id=incomes.user_id AND incomes.income_category_assigned_to_user_id=incomes_category_assigned_to_users.id GROUP BY incomes.income_category_assigned_to_user_id ORDER BY amountOfIncomes DESC");		

		$numberOfIncomeCategories = $result->rowCount();
		var_dump($numberOfIncomeCategories);
		if ($numberOfIncomeCategories==0){			
			return false;	
		}		 		
	
		else{
			$incomeCategories= $result->fetchAll(PDO::FETCH_ASSOC);						
			//var_dump($incomeCategories);
			return $incomeCategories;
		}		
	}	

	public static function getAmountOfAllIncomes($period)
	{	
		$db = static::getDB();
		//$currentMonth = Dates::getCurrentMonth();

		$amountOfAllIncomes= $db->query("SELECT SUM(incomes.amount) AS amountOfAllIncomes FROM incomes WHERE  incomes.user_id=$_SESSION[user_id] AND incomes.date_of_income LIKE '$period-%' ")->fetch();			
				
		//var_dump( $amountOfAllIncomes);
		return $amountOfAllIncomes;
	}
	
	public static function getIncomesInCategory($period)
	{
		$db = static::getDB();
		//$currentMonth = Dates::getCurrentMonth();

		$incomesInCategory=$db->query("SELECT incomes_category_assigned_to_users.name, incomes.income_category_assigned_to_user_id, users.name, incomes.date_of_income, incomes.income_comment, incomes.amount FROM incomes, incomes_category_assigned_to_users, users  WHERE  users.id='$_SESSION[user_id]' AND incomes.date_of_income LIKE'$period-%'  AND  users.id=incomes_category_assigned_to_users.user_id AND users.id=incomes.user_id AND incomes.income_category_assigned_to_user_id=incomes_category_assigned_to_users.id  ORDER BY incomes.date_of_income")->fetchAll(PDO::FETCH_ASSOC);

		//var_dump( $incomesInCategory);
		return $incomesInCategory;
	}	

/////////////EXPENSES///////////////////////////////////////////////////////////////////////////////////////////////////	
	public static function getExpenseCategories($period)
	{			
		$db = static::getDB();
		//$currentMonth = Dates::getCurrentMonth();
		//var_dump($currentMonth);

        $result= $db->query("SELECT expenses_category_assigned_to_users.name, expenses.expense_category_assigned_to_user_id, expenses.date_of_expense, expenses.amount, SUM(expenses.amount) AS amountOfExpenses FROM expenses, expenses_category_assigned_to_users, users  WHERE  users.id=$_SESSION[user_id] AND expenses.date_of_expense LIKE'$period-%'  AND  users.id=expenses_category_assigned_to_users.user_id AND users.id=expenses.user_id AND expenses.expense_category_assigned_to_user_id=expenses_category_assigned_to_users.id GROUP BY expenses.expense_category_assigned_to_user_id ORDER BY amountOfExpenses DESC");		

		$numberOfExpenseCategories = $result->rowCount();
		//var_dump($numberOfExpenseCategories);
		if ($numberOfExpenseCategories==0){			
			return false;	
		}		 		
	
		else{
			$expenseCategories= $result->fetchAll(PDO::FETCH_ASSOC);						
			//var_dump($expenseCategories);
			return $expenseCategories;
		}		
	}	

	public static function getAmountOfAllExpenses($period)
	{	
		$db = static::getDB();
		//$currentMonth = Dates::getCurrentMonth();

		$amountOfAllExpenses= $db->query("SELECT SUM(expenses.amount) AS amountOfAllExpenses FROM expenses WHERE  expenses.user_id=$_SESSION[user_id] AND expenses.date_of_expense LIKE '$period-%' ")->fetch();			
				
		//var_dump($amountOfAllExpenses);
		return $amountOfAllExpenses;
	}	
	
	public static function getExpensesInCategory($period)
	{
		$db = static::getDB();
		//$currentMonth = Dates::getCurrentMonth();

		$expensesInCategory=$db->query("SELECT expenses_category_assigned_to_users.name, expenses.expense_category_assigned_to_user_id, users.name, expenses.date_of_expense, expenses.expense_comment, expenses.amount FROM expenses, expenses_category_assigned_to_users, users  WHERE  users.id='$_SESSION[user_id]' AND expenses.date_of_expense LIKE'$period-%'  AND  users.id=expenses_category_assigned_to_users.user_id AND users.id=expenses.user_id AND expenses.expense_category_assigned_to_user_id=expenses_category_assigned_to_users.id  ORDER BY expenses.date_of_expense")->fetchAll(PDO::FETCH_ASSOC);

		//var_dump( $expensesInCategory);
		return $expensesInCategory;
	}
}

