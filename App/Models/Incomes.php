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
}
