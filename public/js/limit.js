



const expenseForm = document.querySelector(`#expenseForm`);
expenseForm.addEventListener("change", async (e) => { 
    const category = document.querySelector(`#categorySelect option:checked`).text;    
   try{
        const res = await fetch(`../api/limit/${category}`);
        const limit = await res.json();
        //alert(data);        
        document.querySelector(`#limitInfo`).value = limit;
   }catch (e) {
    console.log(`ERRROR`, e)
   }        
}); 
