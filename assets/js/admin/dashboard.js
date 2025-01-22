fetch('/admin/dashboard')
    .then(response => response.json())
    .then(data => {

        document.getElementById('total-sales').textContent = `${data.totalSales}`;
        document.getElementById('total-gross-profit').textContent = `${data.grossProfit}`;
        document.getElementById('total-orders').textContent = `${data.totalOrders}`;
        document.getElementById('total-products').textContent = `${data.totalProductsServices}`;
        displaySalesTrend(data.salesTrend);
        displayRecentOrders(data.recentOrders);

    })
    .catch(error => console.error(error));


function displaySalesTrend(salesTrend) {  // example only
    const ctx = document.getElementById('sales-trend').getContext('2d');
    const chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: salesTrend.map(sale => sale.date),
            datasets: [{
                label: 'Sales',
                data: salesTrend.map(sale => sale.sales),
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
}

function displayRecentOrders(recentOrders) {  // example only
    const tbody = document.getElementById('recent-orders');
    recentOrders.forEach(order => {
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td>${order.orderNumber}</td>
            <td>${order.date}</td>
            <td>${order.customer}</td>
            <td>${order.total}</td>
        `;
        tbody.appendChild(tr);
    });
}