function spellNumber(num) {
    const ones = ['zero', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine'];
  const tens = ['ten', 'eleven', 'twelve', 'thirteen', 'fourteen', 'fifteen', 'sixteen', 'seventeen', 'eighteen', 'nineteen'];
  const twentyToNinety = ['twenty', 'thirty', 'forty', 'fifty', 'sixty', 'seventy', 'eighty', 'ninety'];
  const bigNums = ['hundred', 'thousand', 'lakh', 'crore'];
  
  if (num >= 0 && num < 10) {
    return ones[num];
  } else if (num >= 10 && num < 20) {
    return tens[num - 10];
  } else if (num >= 20 && num < 100) {
    const tensDigit = Math.floor(num / 10);
    const onesDigit = num % 10;
    const tensString = twentyToNinety[tensDigit - 2];
    const onesString = ones[onesDigit];
    return onesString ? `${tensString}-${onesString}` : tensString;
  } else if (num >= 100 && num < 1000) {
    const hundredsDigit = Math.floor(num / 100);
    const tensDigit = Math.floor((num % 100) / 10);
    const onesDigit = num % 10;
    const hundredsString = `${ones[hundredsDigit]} ${bigNums[0]}`;
    const tensString = tensDigit > 1 ? ` ${twentyToNinety[tensDigit - 2]}` : '';
    const onesString = onesDigit ? ` ${ones[onesDigit]}` : '';
    return `${hundredsString}${tensString}${onesString}`.trim();
  } else if (num >= 1000 && num < 100000) {
    const divisor = 1000;
    const quotient = Math.floor(num / divisor);
    const remainder = num % divisor;
    const quotientString = spellNumber(quotient) + ` ${bigNums[1]}`;
    const remainderString = remainder ? ` ${spellNumber(remainder)}` : '';
    return `${quotientString}${remainderString}`.trim();
  } else if (num >= 100000 && num < 10000000) {
    const divisor = 100000;
    const quotient = Math.floor(num / divisor);
    const remainder = num % divisor;
    const quotientString = spellNumber(quotient) + ` ${bigNums[2]}`;
    const remainderString = remainder ? ` ${spellNumber(remainder)}` : '';
    return `${quotientString}${remainderString}`.trim();
  } else if (num >= 10000000 && num < 100000000) {
    const divisor = 10000000;
    const quotient = Math.floor(num / divisor);
    const remainder = num % divisor;
    const quotientString = spellNumber(quotient) + ` ${bigNums[3]}`;
    const remainderString = remainder ? ` ${spellNumber(remainder)}` : '';
    return `${quotientString}${remainderString}`.trim();
  } else {
    return 'Number out of range';
  }
}

  
function generateTable(investType, principal, growthRate, numberOfYears){
    const table = document.createElement("table");
    table.className = "table";
  
    // Add the header row
    const headers = ["Term", "Principal", "Return", "In Word"];
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
    let returnAmount = principal;
    // Add one row for each term
    for (let i = 0; i < numberOfYears; i++) {
        const terms = i + 1;
        // Calculate the future value of the investment

        const row = document.createElement("tr");

        const columnTerm = document.createElement("td");
        columnTerm.appendChild(document.createTextNode(terms));
        row.appendChild(columnTerm);

        const columnPrincipal = document.createElement("td");
        columnPrincipal.appendChild(document.createTextNode(parseFloat(returnAmount).toFixed(2)));
        row.appendChild(columnPrincipal);

        returnAmount = parseFloat(returnAmount) + (returnAmount * growthRate / 100);
        const columnReturn = document.createElement("td");
        columnReturn.appendChild(document.createTextNode(parseFloat(returnAmount).toFixed(2)));
        row.appendChild(columnReturn);

        const columnReturnWord = document.createElement("td");
        columnReturnWord.appendChild(document.createTextNode(spellNumber(parseInt(returnAmount))));
        row.appendChild(columnReturnWord);
        
        if(investType === 'sip'){
            returnAmount += parseFloat(principal);
            if(terms%12 === 0){
                tBody.appendChild(row);
            }
        }else{
            tBody.appendChild(row);
        }
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