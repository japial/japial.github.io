function calculateCompoundInterest() {
    let tableContent = document.getElementById('tableContent')
    tableContent.innerHTML = "";
    // Get the form data
    let principal = document.getElementById('principal').value;
    const growthRate = document.getElementById('growthRate').value;
    const numberOfYears = document.getElementById('numberOfYears').value; 
    // Create a table to display the results
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
    }
    table.appendChild(tBody);
  
    // Append the table to the document
    
    tableContent.appendChild(table);
  }