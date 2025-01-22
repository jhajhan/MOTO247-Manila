$(document).ready(function() {
    fetchDashboardData();

});

function fetchDashboardData() { 
    $.ajax ({
        url: '/admin/dashboard',
        method: GET,
        success: function(data) {
            $('#total-sales').text(data.totalSales);
            $('#gross-profit').text(data.totalCustomers);
            $('#total-products').text(data.totalProducts);
            $('#total-orders').text(data.totalOrders);
            displaySalesTrend(data.salesTrend);
            displayRecentOrders(data.recentOrders);
        },
        error: function(error) {
            console.log(error);
        }
    })
}

function displaySalesTrend(data) {
    const ctx = document.getElementById('sales-trend').getContext('2d');
    const chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: data.map(sale => sale.date),
            datasets: [{
                label: 'Sales',
                data: data.map(sale => sale.total)
            }]
        }
    });
}

function displayRecentOrders() {
    const tableBody = $('#recent-orders tbody');
    tableBody.empty();

    data.forEach(order => {
        const row = $('<tr>');

        let productList = order.products.map(product => `<div>${product}</div>`).join('');

        row.append(
            `<td>${order.order_id}</td>
            <td>${order.customer_name}</td>
            <td>${productList}</td>
            <td>${order.payment_status}</td>
            <td>${order.status}</td>`
        );

        tableBody.append(row);
    });
}