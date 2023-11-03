
const el = document.getElementById('categorySelect');
const box = document.getElementById('limitForm');
el.addEventListener('change', function handleChange(event) {
	if (`#categorySelect option:checked`) {
		//box.style.visibility = 'visible';
      box.style.display = 'block';
	} else {
		//box.style.visibility = 'hidden';
      box.style.display = 'none';
	}
});

const categorySelect = document.querySelector(`#categorySelect`);
const dateInput = document.querySelector(`#dateInput`);
const amountInput = document.querySelector(`#amountInput`);

const getLimit = async(category) => {   
   try{
      const res = await fetch(`../api/limit/${category}`);
      const limit = await res.json();      
      return limit;         
   }catch (e) {
      console.log(`ERRROR`, e)
   } 
}

const getCashSpent = async(category_id, date) => { 
   try{
      const res = await fetch(`../api/limit/${category_id}/${date}`);
      const amountOfAllExpenses = await res.json();      
      return amountOfAllExpenses;          
   }catch (e) {
   console.log(`ERRROR`, e)
   }
}

const getCashLeft = (amount, limit, amountOfAllExpenses) => {
   if (limit > 0) {      
      const cashLeft = (limit - amountOfAllExpenses - amount).toFixed(2);
      return cashLeft;                
   }
}

const setCashSpentWarning = () => {   
   document.querySelector(`#cashSpent`).className = "text-warning bg-dark border-warning rounded"; 
   document.querySelector(`#cashSpentDescription`).className = "text-warning"; 
   document.querySelector(`#cashSpentDescription`).innerHTML = '<i class="icon-gauge ml-1"></i>'+"Month limit exceeded";
       
   document.querySelector(`#cashSpentProgress`).className = "progress-bar bg-warning text-dark"; 
   document.querySelector(`#cashSpentProgressBig`).className = "progress bg-warning text-dark border-warning";  
}

const setCashSpentInfo = () => {
   document.querySelector(`#cashSpent`).className = "text-white bg-dark rounded"; 
   document.querySelector(`#cashSpentDescription`).className = "text-white"; 
   document.querySelector(`#cashSpentDescription`).innerHTML = "(category & date required)";   
   
   document.querySelector(`#cashSpentProgress`).className = "progress-bar";
   document.querySelector(`#cashSpentProgressBig`).className = "progress bg-dark";
}

const setCashLeftWarning = () => {
   document.querySelector(`#cashLeft`).className = "text-warning bg-dark border-warning rounded"; 
   document.querySelector(`#cashLeftDescription`).className = "text-warning"; 
   document.querySelector(`#cashLeftDescription`).innerHTML = '<i class="icon-gauge ml-1"></i>'+"Month limit exceeded after this transaction";
        
   document.querySelector(`#cashLeftProgress`).className = "progress-bar text-dark";
   document.querySelector(`#cashLeftProgressBig`).className = "progress bg-warning text-dark border-warning";
}

const setCashLeftInfo = () => {
   document.querySelector(`#cashLeft`).className = "text-white bg-dark rounded"; 
   document.querySelector(`#cashLeftDescription`).className = "text-white"; 
   document.querySelector(`#cashLeftDescription`).innerHTML = "(category & date required)";
   
   document.querySelector(`#cashLeftProgress`).className = "progress-bar";
   document.querySelector(`#cashLeftProgressBig`).className = "progress bg-dark";  
}

const renderLimit = (limit) => {
   if(limit == 0)  {
      document.querySelector(`#limitInfo`).value = "";      
   }
   else{
      document.querySelector(`#limitInfo`).value = limit;
   } 
}

const renderCashSpent = (amountOfAllExpenses, limit) => {    
   document.querySelector(`#cashSpent`).value = amountOfAllExpenses;
   
   if(limit > 0){
      document.querySelector(`#cashSpentProgress`).style.width = (amountOfAllExpenses/limit)*100+"%";   
      document.querySelector(`#cashSpentProgress`).innerHTML = ((amountOfAllExpenses/limit)*100).toFixed(2)+"%"; 
      document.querySelector(`#cashSpentProgressBig`).style.display = 'block';
      document.querySelector(`#cashSpentProgressBig`).style.height = "25px";      
   }else{
      document.querySelector(`#cashSpentProgressBig`).style.display = 'none';
   }

   if((limit > 0) && ((limit - amountOfAllExpenses) < 0)) {
      setCashSpentWarning();          
   }else {     
      setCashSpentInfo();
   }
}

const renderCashLeft = (cashLeft, limit) => {    
   document.querySelector(`#cashLeft`).value = cashLeft;

   if(limit > 0){      
      document.querySelector(`#cashLeftProgress`).style.width = (cashLeft/limit)*100+"%";      
      document.querySelector(`#cashLeftProgress`).innerHTML = ((cashLeft/limit)*100).toFixed(2)+"%";
      document.querySelector(`#cashLeftProgressBig`).style.display = 'block';
      document.querySelector(`#cashLeftProgressBig`).style.height = "25px"; 
   }else{
      document.querySelector(`#cashLeftProgressBig`).style.display = 'none';
   }

   if(cashLeft < 0) { 
      setCashLeftWarning();      
   }else { 
      setCashLeftInfo();
   }
}
/*
const clearCashInputs = () => {
   //document.querySelector(`#cashLeft`).value = "";
   //document.querySelector(`#amountInput`).value = "";
   //document.querySelector(`#cashSpent`).value = "";

   document.querySelector(`#cashLeftProgress`).innerHTML = "";
   document.querySelector(`#cashSpentProgress`).innerHTML = "";

   document.querySelector(`#cashLeftProgress`).style.width = 0+"%";
   document.querySelector(`#cashSpentProgress`).style.width = 0+"%";   

   setCashSpentInfo();
   setCashLeftInfo();
}
*/
const clearCashSpentInputs = () => {  
   document.querySelector(`#cashSpentProgress`).innerHTML = "";
  
   document.querySelector(`#cashSpentProgress`).style.width = 0+"%";   

   setCashSpentInfo();  
}

const clearCashLeftInputs = () => { 
   document.querySelector(`#cashLeftProgress`).innerHTML = "";
   
   document.querySelector(`#cashLeftProgress`).style.width = 0+"%";
   
   setCashLeftInfo();
}

categorySelect.addEventListener("change", async () => {
   //clearInputs();
   clearCashSpentInputs();
   clearCashLeftInputs();

   const category = document.querySelector(`#categorySelect option:checked`).text; 
   const limit = await getLimit(category);
   renderLimit(limit);

   const date = document.querySelector(`#dateInput`).value;
   const category_id = document.querySelector(`#categorySelect option:checked`).value;
   const amountOfAllExpenses = await getCashSpent(category_id, date);
   renderCashSpent(amountOfAllExpenses, limit);

   const amount = document.querySelector(`#amountInput`).value;
   const cashLeft = getCashLeft(amount, limit, amountOfAllExpenses);
   renderCashLeft(cashLeft, limit);   
});

dateInput.addEventListener("change", async () => {
   //clearInputs();
   clearCashSpentInputs();
   clearCashLeftInputs();

   const category = document.querySelector(`#categorySelect option:checked`).text;
   const category_id = document.querySelector(`#categorySelect option:checked`).value;
   const date = document.querySelector(`#dateInput`).value;
   const limit = await getLimit(category);   
   const amountOfAllExpenses = await getCashSpent(category_id, date);
   renderCashSpent(amountOfAllExpenses, limit); 
   
   const amount = document.querySelector(`#amountInput`).value;
   const cashLeft = getCashLeft(amount, limit, amountOfAllExpenses);
   renderCashLeft(cashLeft, limit);   
});

amountInput.addEventListener("input", async () => {
   //clearInputs();
   clearCashLeftInputs();

   const category = document.querySelector(`#categorySelect option:checked`).text;
   const category_id = document.querySelector(`#categorySelect option:checked`).value;
   const amount = document.querySelector(`#amountInput`).value;
   const date = document.querySelector(`#dateInput`).value;

   const limit = await getLimit(category);
   const amountOfAllExpenses = await getCashSpent(category_id, date);   
   const cashLeft = getCashLeft(amount, limit, amountOfAllExpenses);

   //renderCashSpent(amountOfAllExpenses, limit);
   renderCashLeft(cashLeft, limit);   
});
