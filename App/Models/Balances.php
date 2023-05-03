<?php

namespace App\Models;

use PDO;
use \App\Auth;
use \App\Dates;
use \Core\View;

class Balances extends \Core\Model
{	
	/////////////ICOMES/////////////////////////////////////////////////////////////////////////////////////////////////////
    public static function getIncomeCategoriesFromSelectedPeriod($period)
	{			
		$db = static::getDB();
		//$currentMonth = Dates::getCurrentMonth();
		//var_dump($period);

        $result = $db->query("SELECT incomes_category_assigned_to_users.name, incomes.income_category_assigned_to_user_id, incomes.date_of_income, incomes.amount, SUM(incomes.amount) AS amountOfIncomes FROM incomes, incomes_category_assigned_to_users, users WHERE users.id=$_SESSION[user_id] AND incomes.date_of_income LIKE'$period-%' AND users.id=incomes_category_assigned_to_users.user_id AND users.id=incomes.user_id AND incomes.income_category_assigned_to_user_id=incomes_category_assigned_to_users.id GROUP BY incomes.income_category_assigned_to_user_id ORDER BY amountOfIncomes DESC");		

		$numberOfIncomeCategoriesFromSelectedPeriod = $result->rowCount();
		//var_dump($numberOfIncomeCategoriesFromSelectedPeriod);
		if ($numberOfIncomeCategoriesFromSelectedPeriod == 0){			
			return false;	
		}		 		
	
		else{
			$incomeCategoriesFromSelectedPeriod = $result->fetchAll(PDO::FETCH_ASSOC);						
			//var_dump($incomeCategoriesFromSelectedPeriod);
			return $incomeCategoriesFromSelectedPeriod;
		}		
	}	

	public static function getAmountOfAllIncomesFromSelectedPeriod($period)
	{	
		$db = static::getDB();
		//$currentMonth = Dates::getCurrentMonth();

		$amountOfAllIncomesFromSelectedPeriod = $db->query("SELECT SUM(incomes.amount) AS amountOfAllIncomes FROM incomes WHERE incomes.user_id=$_SESSION[user_id] AND incomes.date_of_income LIKE '$period-%'")->fetch();			
				
		//var_dump( $amountOfAllIncomesFromSelectedPeriod);
		return $amountOfAllIncomesFromSelectedPeriod;
	}
	
	public static function getIncomesFromSelectedPeriod($period)
	{
		$db = static::getDB();
		//$currentMonth = Dates::getCurrentMonth();

		$incomesFromSelectedPeriod =$db->query("SELECT incomes_category_assigned_to_users.name, incomes.income_category_assigned_to_user_id, users.name, incomes.date_of_income, incomes.income_comment, incomes.amount FROM incomes, incomes_category_assigned_to_users, users WHERE users.id='$_SESSION[user_id]' AND incomes.date_of_income LIKE'$period-%' AND users.id=incomes_category_assigned_to_users.user_id AND users.id=incomes.user_id AND incomes.income_category_assigned_to_user_id=incomes_category_assigned_to_users.id  ORDER BY incomes.date_of_income")->fetchAll(PDO::FETCH_ASSOC);

		//var_dump( $incomesFromSelectedPeriod);
		return $incomesFromSelectedPeriod;
	}	

/////////////EXPENSES///////////////////////////////////////////////////////////////////////////////////////////////////	
	public static function getExpenseCategoriesFromSelectedPeriod($period)
	{			
		$db = static::getDB();
		//$currentMonth = Dates::getCurrentMonth();
		//var_dump($currentMonth);

        $result = $db->query("SELECT expenses_category_assigned_to_users.name, expenses.expense_category_assigned_to_user_id, expenses.date_of_expense, expenses.amount, SUM(expenses.amount) AS amountOfExpenses FROM expenses, expenses_category_assigned_to_users, users WHERE users.id=$_SESSION[user_id] AND expenses.date_of_expense LIKE'$period-%' AND users.id=expenses_category_assigned_to_users.user_id AND users.id=expenses.user_id AND expenses.expense_category_assigned_to_user_id=expenses_category_assigned_to_users.id GROUP BY expenses.expense_category_assigned_to_user_id ORDER BY amountOfExpenses DESC");		

		$numberOfExpenseCategoriesFromSelectedPeriod = $result->rowCount();
		//var_dump($numberOfExpenseCategoriesFromSelectedPeriod);
		if ($numberOfExpenseCategoriesFromSelectedPeriod == 0){			
			return false;	
		}		 		
	
		else{
			$expenseCategoriesFromSelectedPeriod = $result->fetchAll(PDO::FETCH_ASSOC);						
			//var_dump($expenseCategoriesFromSelectedPeriod);
			return $expenseCategoriesFromSelectedPeriod;
		}		
	}	

	public static function getAmountOfAllExpensesFromSelectedPeriod($period)
	{	
		$db = static::getDB();
		//$currentMonth = Dates::getCurrentMonth();

		$amountOfAllExpensesFromSelectedPeriod = $db->query("SELECT SUM(expenses.amount) AS amountOfAllExpenses FROM expenses WHERE expenses.user_id=$_SESSION[user_id] AND expenses.date_of_expense LIKE '$period-%' ")->fetch();			
				
		//var_dump($amountOfAllExpensesFromSelectedPeriod);
		return $amountOfAllExpensesFromSelectedPeriod;
	}	
	
	public static function getExpensesFromSelectedPeriod($period)
	{
		$db = static::getDB();
		//$currentMonth = Dates::getCurrentMonth();

		$expensesFromSelectedPeriod =$db->query("SELECT expenses_category_assigned_to_users.name, expenses.expense_category_assigned_to_user_id, users.name, expenses.date_of_expense, expenses.expense_comment, expenses.amount FROM expenses, expenses_category_assigned_to_users, users WHERE users.id='$_SESSION[user_id]' AND expenses.date_of_expense LIKE'$period-%' AND users.id=expenses_category_assigned_to_users.user_id AND users.id=expenses.user_id AND expenses.expense_category_assigned_to_user_id=expenses_category_assigned_to_users.id  ORDER BY expenses.date_of_expense")->fetchAll(PDO::FETCH_ASSOC);

		//var_dump($expensesFromSelectedPeriod);
		return $expensesFromSelectedPeriod;
	}

	public static function getStartingDate()
	{
		if (isset($_POST['startingDate']))
		{			
			$startingDate = filter_input(INPUT_POST, 'startingDate');			
		}
		
		//var_dump($startingDate);
		return $startingDate;
	}

	public static function getEndDate()
	{
		if (isset($_POST['endDate']))
		{			
			$endDate = filter_input(INPUT_POST, 'endDate');			
		}
		
		//var_dump($endDate);		
		return $endDate;	
	}

	public static function getIncomeCategoriesFromSelectedDateRange($startingDate, $endDate)
	{		
		$db = static::getDB();			
	
		$result = $db->query("SELECT incomes_category_assigned_to_users.name, incomes.income_category_assigned_to_user_id, incomes.date_of_income, incomes.amount, SUM(incomes.amount) AS amountOfIncomes FROM incomes, incomes_category_assigned_to_users, users WHERE users.id=$_SESSION[user_id] AND incomes.date_of_income BETWEEN '$startingDate' AND '$endDate' AND users.id=incomes_category_assigned_to_users.user_id AND users.id=incomes.user_id AND incomes.income_category_assigned_to_user_id=incomes_category_assigned_to_users.id GROUP BY incomes.income_category_assigned_to_user_id ORDER BY amountOfIncomes DESC");		
	
		$numberOfIncomeCategoriesFromSelectedPeriod = $result->rowCount();
		//var_dump($numberOfIncomeCategoriesFromSelectedPeriod);
		if ($numberOfIncomeCategoriesFromSelectedPeriod == 0){			
			return false;	
		}		 		
		
		else{
			$incomeCategoriesFromSelectedPeriod = $result->fetchAll(PDO::FETCH_ASSOC);						
			//var_dump($incomeCategoriesFromSelectedPeriod);
			return $incomeCategoriesFromSelectedPeriod;
		}			
	}
	
	public static function getAmountOfAllIncomesFromSelectedDateRange($startingDate, $endDate)
	{		
		$db = static::getDB();			
	
		$amountOfAllIncomesFromSelectedPeriod = $db->query("SELECT SUM(incomes.amount) AS amountOfAllIncomes FROM incomes WHERE incomes.user_id=$_SESSION[user_id] AND incomes.date_of_income BETWEEN '$startingDate' AND '$endDate'")->fetch();		
		//var_dump($amountOfAllIncomesFromSelectedPeriod);
		return $amountOfAllIncomesFromSelectedPeriod;			
	}

	public static function getIncomesFromSelectedDateRange($startingDate, $endDate)
	{		
		$db = static::getDB();
				
		$incomesFromSelectedPeriod =$db->query("SELECT incomes_category_assigned_to_users.name, incomes.income_category_assigned_to_user_id, users.name, incomes.date_of_income, incomes.income_comment, incomes.amount FROM incomes, incomes_category_assigned_to_users, users WHERE users.id='$_SESSION[user_id]' AND incomes.date_of_income BETWEEN '$startingDate' AND '$endDate' AND users.id=incomes_category_assigned_to_users.user_id AND users.id=incomes.user_id AND incomes.income_category_assigned_to_user_id=incomes_category_assigned_to_users.id ORDER BY incomes.date_of_income")->fetchAll(PDO::FETCH_ASSOC);	
		//var_dump( $incomesFromSelectedPeriod);
		return $incomesFromSelectedPeriod;					
	}
	
	public static function getExpenseCategoriesFromSelectedDateRange($startingDate, $endDate)
	{		
		$db = static::getDB();			
	
		$result = $db->query("SELECT expenses_category_assigned_to_users.name, expenses.expense_category_assigned_to_user_id, expenses.date_of_expense, expenses.amount, SUM(expenses.amount) AS amountOfExpenses FROM expenses, expenses_category_assigned_to_users, users WHERE users.id=$_SESSION[user_id] AND expenses.date_of_expense BETWEEN '$startingDate' AND '$endDate' AND users.id=expenses_category_assigned_to_users.user_id AND users.id=expenses.user_id AND expenses.expense_category_assigned_to_user_id=expenses_category_assigned_to_users.id GROUP BY expenses.expense_category_assigned_to_user_id ORDER BY amountOfExpenses DESC");		
	
		$numberOfExpenseCategoriesFromSelectedPeriod = $result->rowCount();
		//var_dump($numberOfExpenseCategoriesFromSelectedPeriod);
		if ($numberOfExpenseCategoriesFromSelectedPeriod == 0){			
			return false;	
		}		 		
		
		else{
			$expenseCategoriesFromSelectedPeriod = $result->fetchAll(PDO::FETCH_ASSOC);						
			//var_dump($expenseCategoriesFromSelectedPeriod);
			return $expenseCategoriesFromSelectedPeriod;
		}			
	}
	
	public static function getAmountOfAllExpensesFromSelectedDateRange($startingDate, $endDate)
	{		
		$db = static::getDB();			
	
		$amountOfAllExpensesFromSelectedPeriod = $db->query("SELECT SUM(expenses.amount) AS amountOfAllExpenses FROM expenses WHERE expenses.user_id=$_SESSION[user_id] AND expenses.date_of_expense BETWEEN '$startingDate' AND '$endDate'")->fetch();						
		//var_dump($amountOfAllExpensesFromSelectedPeriod);
		return $amountOfAllExpensesFromSelectedPeriod;
			
	}

	public static function getExpensesFromSelectedDateRange($startingDate, $endDate)
	{		
		$db = static::getDB();			
	
		$expensesFromSelectedPeriod = $db->query("SELECT expenses_category_assigned_to_users.name, expenses.expense_category_assigned_to_user_id, users.name, expenses.date_of_expense, expenses.expense_comment, expenses.amount FROM expenses, expenses_category_assigned_to_users, users WHERE users.id='$_SESSION[user_id]' AND expenses.date_of_expense BETWEEN '$startingDate' AND '$endDate' AND users.id=expenses_category_assigned_to_users.user_id AND users.id=expenses.user_id AND expenses.expense_category_assigned_to_user_id=expenses_category_assigned_to_users.id  ORDER BY expenses.date_of_expense")->fetchAll(PDO::FETCH_ASSOC);	
		//var_dump($expensesFromSelectedPeriod);
		return $expensesFromSelectedPeriod;				
	}

	public static function getBalanceFromSelectedDateRange($startingDate, $endDate)
	{		
		$amountOfAllIncomesFromSelectedPeriod = static::getAmountOfAllIncomesFromSelectedDateRange($startingDate, $endDate);
		$amountOfAllExpensesFromSelectedPeriod = static::getAmountOfAllExpensesFromSelectedDateRange($startingDate, $endDate);
		//var_dump($amountOfAllExpensesFromSelectedPeriod);
		//var_dump($amountOfAllIncomesFromSelectedPeriod);
		$balance = $amountOfAllIncomesFromSelectedPeriod['amountOfAllIncomes'] - $amountOfAllExpensesFromSelectedPeriod['amountOfAllExpenses'];
		$balance = number_format($balance, 2, '.', '');
		//var_dump($balance);
		return $balance;			
	}

	public static function getBalanceFromSelectedPeriod($period)
	{
		$amountOfAllIncomesFromSelectedPeriod = static::getAmountOfAllIncomesFromSelectedPeriod($period);
		$amountOfAllExpensesFromSelectedPeriod = static::getAmountOfAllExpensesFromSelectedPeriod($period);
		//var_dump($amountOfAllExpensesFromSelectedPeriod);
		//var_dump($amountOfAllIncomesFromSelectedPeriod);
		$balance = $amountOfAllIncomesFromSelectedPeriod['amountOfAllIncomes'] - $amountOfAllExpensesFromSelectedPeriod['amountOfAllExpenses'];
		$balance = number_format($balance, 2, '.', '');
		//var_dump($balance);
		return $balance;
	}
}

