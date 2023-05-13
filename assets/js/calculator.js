function calculateCompoundInterest(form) {
    e.preventDefault();
    // Get the form data
    var principal = form.principal.value;
    var interestRate = form.interestRate.value;
    var numberOfYears = form.numberOfYears.value;
  
    // Calculate the future value of the investment
    var futureValue = principal * Math.pow(1 + interestRate / 100, numberOfYears);
  
    // Create a table to display the results
    var table = document.createElement("table");
    table.style.width = "100%";
  
    // Add the header row
    var headerRow = document.createElement("tr");
    headerRow.appendChild(document.createTextNode("Term"));
    headerRow.appendChild(document.createTextNode("Principal"));
    headerRow.appendChild(document.createTextNode("Interest Rate"));
    headerRow.appendChild(document.createTextNode("Number of Years"));
    headerRow.appendChild(document.createTextNode("Future Value"));
    table.appendChild(headerRow);
  
    // Add one row for each term
    for (var i = 0; i < numberOfYears; i++) {
      var row = document.createElement("tr");
      row.appendChild(document.createTextNode(i + 1));
      row.appendChild(document.createTextNode(principal));
      row.appendChild(document.createTextNode(interestRate + "%"));
      row.appendChild(document.createTextNode(i + 1));
      row.appendChild(document.createTextNode(futureValue));
      table.appendChild(row);
    }
  
    // Append the table to the document
    document.body.appendChild(table);
  }

const form = document.getElementById("myForm");
calculateCompoundInterest(form);