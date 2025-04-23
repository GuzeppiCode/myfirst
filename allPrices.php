<div id="priceTableContainer">           table will show here  </div>
<script>
const TheCompanies = ["APS", "AXR", "BMIT", "BOV", "CVS", "FIM", "GHM", "GO", "HLI", "HRV", "HSB", "IHI", "LOM", "LQS", "LSI", "LSR", "MDI", "MDS", "MIA", "MLT", "MMS", "MPC", "MSC", "MTP", "MZ", "PG", "PZC", "RS2", "RS2P", "SFC", "STS", "TML", "TRI", "VBL"];


async function fetchPrice(selectedCompany) {
    try {
    //  const response = await fetch(`sharePrice.php?company=${selectedCompany}`);
         const response = await fetch(`sharePrice.php?company=${encodeURIComponent(selectedCompany)}`);
        if (!response.ok) throw new Error('Price fetch failed');
        const priceText = await response.text();
        const currentPrice = parseFloat(priceText);
        if (isNaN(currentPrice)) throw new Error('Invalid price received');
        return currentPrice;
    } catch (error) {
        return 0; // Default to 0 if error occurs
    }
}

async function fetchAllPrices() {
    const companyPrices = await Promise.all(
        TheCompanies.map(async (company) => {
            const price = await fetchPrice(company);
            return { company, price };
        })
    );
    console.log(companyPrices); // This is your new array with company and fetched price
    return companyPrices;
}


////////////////////////
function displayPrices(prices) {
    const container = document.getElementById('priceTableContainer');
    
    const table = document.createElement('table');
    table.border = '1';
    table.style.borderCollapse = 'collapse';
    table.style.marginTop = '20px';

    const headerRow = document.createElement('tr');
    headerRow.innerHTML = '<th style="padding: 8px;">Company</th><th style="padding: 8px;">Price</th>';
    table.appendChild(headerRow);

    prices.forEach(({ company, price }) => {
        const row = document.createElement('tr');
        row.innerHTML = `<td style="padding: 8px;">${company}</td><td style="padding: 8px;">${price.toFixed(2)}</td>`;
        table.appendChild(row);
    });

    container.innerHTML = ''; // Clear existing content
    container.appendChild(table);
}

////////////////////////////

// Call it
fetchAllPrices().then(displayPrices);

</script>

