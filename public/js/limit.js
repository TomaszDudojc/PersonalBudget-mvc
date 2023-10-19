
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

const getLimitValue = async(category_id, date) => { 
   try{
      const res = await fetch(`../api/limit/${category_id}/${date}`);
      const amountOfAllExpenses = await res.json();
      return amountOfAllExpenses;           
   }catch (e) {
   console.log(`ERRROR`, e)
   }
}

const getCashLeft = (amount, limit, amountOfAllExpenses) => {
   if ((limit > 0) && (amount > 0)) {
      const cashLeft = parseFloat(limit - amountOfAllExpenses - amount).toFixed(2);
      return cashLeft;                
   }
}

const setLimitValueWarning = () => {
   document.querySelector(`#limitValue`).style.color = "#E9B64A";
   document.querySelector(`#limitValueLabel`).style.color = "#E9B64A";
   document.querySelector(`#limitValueLabel`).innerHTML = "<b>Limit value </b>&#129300; Month limit exceeded";
}

const setLimitValueInfo = () => {
   document.querySelector(`#limitValue`).style.color = "#209781";
   document.querySelector(`#limitValueLabel`).style.color = "#26282E";
   document.querySelector(`#limitValueLabel`).innerHTML = "<b>Limit value </b>(category & date required)";
}

const setCashLeftWarning = () => {
   document.querySelector(`#cashLeft`).style.color = "#E9B64A";
   document.querySelector(`#cashLeftLabel`).style.color = "#E9B64A";
   document.querySelector(`#cashLeftLabel`).innerHTML = "<b>Cash left </b>&#129300; Month limit exceeded after this transaction";
}

const setCashLeftInfo = () => {
   document.querySelector(`#cashLeft`).style.color = "#209781";
   document.querySelector(`#cashLeftLabel`).style.color = "#26282E";
   document.querySelector(`#cashLeftLabel`).innerHTML = "<b>Cash left </b>(category, date & amount required)";
}

const renderLimit = (limit) => {
   if(limit == 0)  {
      document.querySelector(`#limitInfo`).value = "";      
   }
   else{
      document.querySelector(`#limitInfo`).value = limit;
   } 
}

const renderLimitValue = (amountOfAllExpenses, limit) => {    
   document.querySelector(`#limitValue`).value = amountOfAllExpenses;  

   if((limit > 0) && (amountOfAllExpenses > limit)) { 
      setLimitValueWarning();
   }else {     
      setLimitValueInfo();
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

const clearInputs = () => {
   document.querySelector(`#cashLeft`).value = "";
   document.querySelector(`#amountInput`).value = "";
   document.querySelector(`#limitValue`).value = "";

   setLimitValueInfo();
   setCashLeftInfo();
}

categorySelect.addEventListener("change", async () => {
   clearInputs();

   const category = document.querySelector(`#categorySelect option:checked`).text; 
   const limit = await getLimit(category);

   renderLimit(limit);
});

dateInput.addEventListener("change", async () => {
   clearInputs();

   const category = document.querySelector(`#categorySelect option:checked`).text;
   const category_id = document.querySelector(`#categorySelect option:checked`).value;
   const date = document.querySelector(`#dateInput`).value;

   const limit = await getLimit(category);   
   const amountOfAllExpenses = await getLimitValue(category_id, date);

   renderLimitValue(amountOfAllExpenses, limit);  
});

amountInput.addEventListener("input", async () => {
   const category = document.querySelector(`#categorySelect option:checked`).text;
   const category_id = document.querySelector(`#categorySelect option:checked`).value;
   const amount = document.querySelector(`#amountInput`).value;
   const date = document.querySelector(`#dateInput`).value;

   const limit = await getLimit(category);
   const amountOfAllExpenses = await getLimitValue(category_id, date);   
   const cashLeft = getCashLeft(amount, limit, amountOfAllExpenses);

   renderLimitValue(amountOfAllExpenses, limit);
   renderCashLeft(cashLeft);   
});
