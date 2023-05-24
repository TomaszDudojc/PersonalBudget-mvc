<?php

namespace App\Controllers;

use \Core\View;
use \App\Auth;
use \App\Flash;
use \App\Dates;
use \App\Models\Incomes;
use \App\Models\Expenses;

class Settings extends Authenticated
{

    public function indexAction()
    {
        View::renderTemplate('Settings/index.html', [							
			'incomeCategories' => Incomes::getIncomeCategoriesOfUser()			
		]);
    }   
}