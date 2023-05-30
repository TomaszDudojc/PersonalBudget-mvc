<?php

namespace App\Models;

use PDO;
use \App\Auth;
use \App\Dates;
use \Core\View;

class Balances extends \Core\Model
{	
	public static function getIncomeCategoriesFromSelectedDateRange($startDate, $endDate)
	{		
		$sql = "SELECT incomes_category_assigned_to_users.name, incomes.income_category_assigned_to_user_id,
		SUM(incomes.amount) AS amountOfIncomes FROM incomes, incomes_category_assigned_to_users, users
		WHERE users.id=:user_id AND incomes.date_of_income BETWEEN :start_date AND :end_date
		AND users.id=incomes_category_assigned_to_users.user_id AND users.id=incomes.user_id 
		AND incomes.income_category_assigned_to_user_id=incomes_category_assigned_to_users.id 
		GROUP BY incomes.income_category_assigned_to_user_id ORDER BY amountOfIncomes DESC";

		$db = static::getDB();
		$incomeCategoriesFromSelectedPeriod = $db->prepare($sql);
        $incomeCategoriesFromSelectedPeriod->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
        $incomeCategoriesFromSelectedPeriod->bindValue(':start_date', $startDate, PDO::PARAM_STR);
        $incomeCategoriesFromSelectedPeriod->bindValue(':end_date', $endDate, PDO::PARAM_STR);   
        $incomeCategoriesFromSelectedPeriod->execute();
		
		$numberOfIncomeCategoriesFromSelectedPeriod = $incomeCategoriesFromSelectedPeriod->rowCount();
		
		if ($numberOfIncomeCategoriesFromSelectedPeriod == 0){			
			return false;	
		}
		else{			
			return $incomeCategoriesFromSelectedPeriod->fetchAll(PDO::FETCH_ASSOC);
		}		
	}
	
	public static function getAmountOfAllIncomesFromSelectedDateRange($startDate, $endDate)
	{		
		$sql = "SELECT SUM(incomes.amount) AS amountOfAllIncomes FROM incomes 
		WHERE incomes.user_id=:user_id
		AND incomes.date_of_income BETWEEN :start_date AND :end_date";		
		
		$db = static::getDB();
		$amountOfAllIncomesFromSelectedPeriod = $db->prepare($sql);
        $amountOfAllIncomesFromSelectedPeriod->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
        $amountOfAllIncomesFromSelectedPeriod->bindValue(':start_date', $startDate, PDO::PARAM_STR);
        $amountOfAllIncomesFromSelectedPeriod->bindValue(':end_date', $endDate, PDO::PARAM_STR);   
        $amountOfAllIncomesFromSelectedPeriod->execute();
        
        return $amountOfAllIncomesFromSelectedPeriod->fetch();			
	}

	public static function getIncomesFromSelectedDateRange($startDate, $endDate)
	{		
		
        $sql = "SELECT incomes_category_assigned_to_users.name, incomes.income_category_assigned_to_user_id, incomes.date_of_income, incomes.income_comment, incomes.amount, incomes.id
        FROM incomes, incomes_category_assigned_to_users, users WHERE users.id=:user_id AND incomes.date_of_income BETWEEN :start_date AND :end_date
        AND users.id=incomes_category_assigned_to_users.user_id AND users.id=incomes.user_id 
        AND incomes.income_category_assigned_to_user_id=incomes_category_assigned_to_users.id 
        ORDER BY incomes.date_of_income";       
        
        $db = static::getDB();
        $incomesFromSelectedPeriod = $db->prepare($sql);
        $incomesFromSelectedPeriod->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
        $incomesFromSelectedPeriod->bindValue(':start_date', $startDate, PDO::PARAM_STR);
        $incomesFromSelectedPeriod->bindValue(':end_date', $endDate, PDO::PARAM_STR);   
        $incomesFromSelectedPeriod->execute();
        
        return $incomesFromSelectedPeriod->fetchAll(PDO::FETCH_ASSOC); 		
	}
	
	public static function getExpenseCategoriesFromSelectedDateRange($startDate, $endDate)
	{		
		$sql = "SELECT expenses_category_assigned_to_users.name, expenses.expense_category_assigned_to_user_id,
		SUM(expenses.amount) AS amountOfExpenses FROM expenses, expenses_category_assigned_to_users, users
		WHERE users.id=:user_id AND expenses.date_of_expense BETWEEN :start_date AND :end_date
		AND users.id=expenses_category_assigned_to_users.user_id AND users.id=expenses.user_id
		AND expenses.expense_category_assigned_to_user_id=expenses_category_assigned_to_users.id
		GROUP BY expenses.expense_category_assigned_to_user_id ORDER BY amountOfExpenses DESC";

		$db = static::getDB();
		$expenseCategoriesFromSelectedPeriod = $db->prepare($sql);
        $expenseCategoriesFromSelectedPeriod->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
        $expenseCategoriesFromSelectedPeriod->bindValue(':start_date', $startDate, PDO::PARAM_STR);
        $expenseCategoriesFromSelectedPeriod->bindValue(':end_date', $endDate, PDO::PARAM_STR);   
        $expenseCategoriesFromSelectedPeriod->execute();
		
		$numberOfExpenseCategoriesFromSelectedPeriod = $expenseCategoriesFromSelectedPeriod->rowCount();
		
		if ($numberOfExpenseCategoriesFromSelectedPeriod == 0){			
			return false;	
		}
		else{			
			return $expenseCategoriesFromSelectedPeriod->fetchAll(PDO::FETCH_ASSOC);
		}
	}
	
	public static function getAmountOfAllExpensesFromSelectedDateRange($startDate, $endDate)
	{		
		$sql = "SELECT SUM(expenses.amount) AS amountOfAllExpenses FROM expenses 
		WHERE expenses.user_id=:user_id
		AND expenses.date_of_expense BETWEEN :start_date AND :end_date";		
		
		$db = static::getDB();
		$amountOfAllExpensesFromSelectedPeriod = $db->prepare($sql);
        $amountOfAllExpensesFromSelectedPeriod->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
        $amountOfAllExpensesFromSelectedPeriod->bindValue(':start_date', $startDate, PDO::PARAM_STR);
		$amountOfAllExpensesFromSelectedPeriod->bindValue(':end_date', $endDate, PDO::PARAM_STR);   
        $amountOfAllExpensesFromSelectedPeriod->execute();
        
        return $amountOfAllExpensesFromSelectedPeriod->fetch();		
	}

	public static function getExpensesFromSelectedDateRange($startDate, $endDate)
	{		
		$sql = "SELECT expenses_category_assigned_to_users.name, expenses.expense_category_assigned_to_user_id, expenses.date_of_expense, expenses.expense_comment, expenses.amount, expenses.id, expenses.payment_method_assigned_to_user_id, payment_methods_assigned_to_users.name
		FROM expenses, expenses_category_assigned_to_users, payment_methods_assigned_to_users, users WHERE users.id=:user_id 
		AND expenses.date_of_expense BETWEEN :start_date AND :end_date AND users.id=expenses_category_assigned_to_users.user_id 
		AND users.id=expenses.user_id AND expenses.expense_category_assigned_to_user_id=expenses_category_assigned_to_users.id
		AND users.id=payment_methods_assigned_to_users.user_id
		AND  payment_methods_assigned_to_users.id=expenses.payment_method_assigned_to_user_id 
		ORDER BY expenses.date_of_expense";       
        
        $db = static::getDB();
        $expensesFromSelectedPeriod = $db->prepare($sql);
        $expensesFromSelectedPeriod->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
        $expensesFromSelectedPeriod->bindValue(':start_date', $startDate, PDO::PARAM_STR);
        $expensesFromSelectedPeriod->bindValue(':end_date', $endDate, PDO::PARAM_STR);   
        $expensesFromSelectedPeriod->execute();
        
        return $expensesFromSelectedPeriod->fetchAll(PDO::FETCH_ASSOC); 
	}

	public static function getBalanceFromSelectedDateRange($startDate, $endDate)
	{		
		$amountOfAllIncomesFromSelectedPeriod = static::getAmountOfAllIncomesFromSelectedDateRange($startDate, $endDate);
		$amountOfAllExpensesFromSelectedPeriod = static::getAmountOfAllExpensesFromSelectedDateRange($startDate, $endDate);
		
		$balance = $amountOfAllIncomesFromSelectedPeriod['amountOfAllIncomes'] - $amountOfAllExpensesFromSelectedPeriod['amountOfAllExpenses'];
		$balance = number_format($balance, 2, '.', '');
		
		return $balance;			
	}	
}

