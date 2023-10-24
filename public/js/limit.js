
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
      const cashLeft = parseFloat(limit - amountOfAllExpenses - amount).toFixed(2);
      return cashLeft;                
   }
}

const setCashSpentWarning = () => {
   document.querySelector(`#cashSpent`).style.color = "yellow";
   document.querySelector(`#cashSpentDescription`).style.color = "yellow";
   document.querySelector(`#cashSpentDescription`).innerHTML = "&#129300; Month limit exceeded";  
}

const setCashSpentInfo = () => {
   document.querySelector(`#cashSpent`).style.color = "white";
   document.querySelector(`#cashSpentDescription`).style.color = "white";
   document.querySelector(`#cashSpentDescription`).innerHTML = "(category & date required)";
}

const setCashLeftWarning = () => {
   document.querySelector(`#cashLeft`).style.color = "yellow";
   document.querySelector(`#cashLeftDescription`).style.color = "yellow";
   document.querySelector(`#cashLeftDescription`).innerHTML = "&#129300; Month limit exceeded after this transaction";
}

const setCashLeftInfo = () => {
   document.querySelector(`#cashLeft`).style.color = "white";
   document.querySelector(`#cashLeftDescription`).style.color = "white";
   document.querySelector(`#cashLeftDescription`).innerHTML = "(category, date & amount required)";
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

   if((limit > 0) && (amountOfAllExpenses > limit)) {
      setCashSpentWarning();
   }      
   else {     
      setCashSpentInfo();
   }                        
}

const renderCashLeft = (cashLeft) => {    
   document.querySelector(`#cashLeft`).value = cashLeft;
   if(cashLeft < 0) { 
      setCashLeftWarning();
   }else { 
      setCashLeftInfo();
   }
}
/*
const clearInputs = () => {
   document.querySelector(`#cashLeft`).value = "";
   document.querySelector(`#amountInput`).value = "";
   document.querySelector(`#cashSpent`).value = "";

   setCashSpentInfo();
   setCashLeftInfo();
}
*/
categorySelect.addEventListener("change", async () => {
   //clearInputs();

   const category = document.querySelector(`#categorySelect option:checked`).text; 
   const limit = await getLimit(category);
   renderLimit(limit);

   const date = document.querySelector(`#dateInput`).value;
   const category_id = document.querySelector(`#categorySelect option:checked`).value;
   const amountOfAllExpenses = await getCashSpent(category_id, date);
   renderCashSpent(amountOfAllExpenses, limit);

   const amount = document.querySelector(`#amountInput`).value;
   const cashLeft = getCashLeft(amount, limit, amountOfAllExpenses);
   renderCashLeft(cashLeft);   
});

dateInput.addEventListener("change", async () => {
   //clearInputs();

   const category = document.querySelector(`#categorySelect option:checked`).text;
   const category_id = document.querySelector(`#categorySelect option:checked`).value;
   const date = document.querySelector(`#dateInput`).value;
   const limit = await getLimit(category);   
   const amountOfAllExpenses = await getCashSpent(category_id, date);
   renderCashSpent(amountOfAllExpenses, limit); 
   
   const amount = document.querySelector(`#amountInput`).value;
   const cashLeft = getCashLeft(amount, limit, amountOfAllExpenses);
   renderCashLeft(cashLeft);   
});

amountInput.addEventListener("input", async () => {
   const category = document.querySelector(`#categorySelect option:checked`).text;
   const category_id = document.querySelector(`#categorySelect option:checked`).value;
   const amount = document.querySelector(`#amountInput`).value;
   const date = document.querySelector(`#dateInput`).value;

   const limit = await getLimit(category);
   const amountOfAllExpenses = await getCashSpent(category_id, date);   
   const cashLeft = getCashLeft(amount, limit, amountOfAllExpenses);

   renderCashSpent(amountOfAllExpenses, limit);
   renderCashLeft(cashLeft);   
});
