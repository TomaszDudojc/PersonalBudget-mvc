<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\Incomes;
use \App\Auth;
use \App\Flash;


class Income extends Authenticated
{

    public function indexAction()
    {
        View::renderTemplate('Income/index.html', [							
			'incomeCategories' => Incomes::getIncomeCategoriesOfUser()				
		]);
    }

    
}