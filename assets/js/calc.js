function generateTable(investType, principal, growthRate, numberOfYears){
    const table = document.createElement("table");
    table.className = "table";
  
    // Add the header row
    const headers = ["Term", "Principal", "Return"];
    const tHead = document.createElement("thead");
    const headerRow = document.createElement("tr");
    headers.map((item)=>{
        const headerColumn = document.createElement("th");
        headerColumn.appendChild(document.createTextNode(item));
        headerRow.appendChild(headerColumn);
    });
    tHead.appendChild(headerRow);
    table.appendChild(tHead);
    const tBody = document.createElement("tbody");
    const monthlySIP = investType === "sip" ? principal : 0;
    // Add one row for each term
    for (let i = 0; i < numberOfYears; i++) {
        const terms = i + 1;
        // Calculate the future value of the investment

        const row = document.createElement("tr");

        const columnTerm = document.createElement("td");
        columnTerm.appendChild(document.createTextNode(terms));
        row.appendChild(columnTerm);

        const columnPrincipal = document.createElement("td");
        columnPrincipal.appendChild(document.createTextNode(parseInt(principal)));
        row.appendChild(columnPrincipal);

        principal = parseInt(principal) + (principal * growthRate / 100);
        const columnReturn = document.createElement("td");
        columnReturn.appendChild(document.createTextNode(parseInt(principal)));
        row.appendChild(columnReturn);
        tBody.appendChild(row);
        principal = principal + monthlySIP;
    }
    table.appendChild(tBody);
  
    // Append the table to the document
    
    tableContent.appendChild(table);
}

function calculateCompoundInterest() {
    let tableContent = document.getElementById('tableContent')
    tableContent.innerHTML = "";
    // Get the form data
    const investType = document.getElementById("investType").value;
    let principal = document.getElementById('principal').value;
    let growthRate = document.getElementById('growthRate').value;
    let numberOfTerms = document.getElementById('numberOfYears').value; 

    if(investType === 'sip'){
        numberOfTerms = numberOfTerms * 12;
        growthRate = growthRate / 12;
    }
    // Create a table to display the results
    generateTable(investType, principal, growthRate, numberOfTerms);
}