$(document).ready(function() {
    // Get the current date
    var today = new Date();

    // Format the date to yyyy-mm-dd
    var formattedDate = today.toISOString().split('T')[0];

    // Set the value of the input to the current date
    $('#dateInput').val(formattedDate);
     
    fetchDashboardData();

});

$("#month").on('change', function(){
    const date = $('#dateInput').val();
    const month = $('#month').val();

    // Construct the query string
    const queryString = $.param({ date, month });

    $.ajax({
        url: '/admin/dashboard?' + queryString,  // Append query string to the URL
        method: "GET",
        contentType: 'application/json',
        success: function(data) {
    
           
            $('#total-profit-monthly').html(data.monthlyProfit);
        
        },
        error: function(error) {
            console.log(error);
        }
    });
})

$(".dateInput").on('change', function(){
    const date = $('.dateInput').val();
    const month = $('#month').val();


    // Construct the query string
    const queryString = $.param({ date, month });

    $.ajax({
        url: '/admin/dashboard?' + queryString,  // Append query string to the URL
        method: "GET",
        contentType: 'application/json',
        success: function(data) {
    
           
            $('#total-sales').html(data.totalSales);
        
        },
        error: function(error) {
            console.log(error);
        }
    });
})

function fetchDashboardData() { 
    const date = $('#dateInput').val();
    const month = $('#month').val();

    // Construct the query string
    const queryString = $.param({ date, month });

    $.ajax({
        url: '/admin/dashboard?' + queryString,  // Append query string to the URL
        method: "GET",
        contentType: 'application/json',
        success: function(data) {
    
            $('#total-sales').html(data.totalSales);
            $('#total-profit-monthly').html(data.monthlyProfit);
            $('#total-profit-daily').html(data.dailyProfit['total_profit']);
            $('#total-products-services').html(data.totalProductsServices['no_products_services']);
            $('#total-orders').html(data.totalOrders['no_orders']);

            displayPaymentMethod(data.paymentMethod);
            displaySalesTrend(data.salesTrend);
            displayRecentOrders(data.recentOrders);
        },
        error: function(error) {
            console.log(error);
        }
    });
}


function displaySalesTrend(data) {
    
    const ctx = document.getElementById('salesTrendChart').getContext('2d');

    // Destroy existing chart instance if it exists (prevents duplication issues)
    if (window.salesTrendChart instanceof Chart) {
        window.salesTrendChart.destroy();
    }

    const labels = data.map(item => item.month);
    const dataValues = data.map(item => item.gross_profit);

    window.salesTrendChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Total Earnings (₱)',
                data: dataValues,
                borderColor: '#36A2EB', // Line color
                backgroundColor: 'rgba(54, 162, 235, 0.2)', // Light fill color
                borderWidth: 2,
                pointBackgroundColor: '#007bff', // Point color
                pointRadius: 5,
                pointHoverRadius: 7,
                tension: 0.3, // Smooth line effect
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        font: {
                            size: 14,
                            weight: 'bold'
                        }
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(tooltipItem) {
                            let value = tooltipItem.raw;
                            return `₱${value.toLocaleString()}`;
                        }
                    }
                }
            },
            scales: {
                x: {
                    title: {
                        display: true,
                        text: 'Month',
                        font: {
                            size: 14,
                            weight: 'bold'
                        }
                    }
                },
                y: {
                    title: {
                        display: true,
                        text: 'Earnings (₱)',
                        font: {
                            size: 14,
                            weight: 'bold'
                        }
                    },
                    ticks: {
                        callback: function(value) {
                            return `₱${value.toLocaleString()}`;
                        }
                    }
                }
            }
        }
    });
}


function displayRecentOrders(recentOrders) {
    const tableBody = $('.tableBody tbody');
    tableBody.empty(); // Clear the table before adding new rows

    // Loop through the orders and append rows to the table
    recentOrders.forEach(order => {
        // Split the products string into an array of product names
        const productList = order.products ? order.products.split(',') : [];
        
        // Create a single row for each order
        const row = $('<tr>');

        // Join the products into a single string, separating them by commas or new lines
        const productNames = productList.join('<br/>'); // <br/> for line breaks between products

        // Add data to the row, including a dynamically generated status span
        row.append(
            `<td>${order.full_name}</td>
            <td>${productNames}</td> <!-- Group products in a single cell -->
            <td>₱${order.total_amount}</td>
            <td>${order.payment_status}</td> <!-- This column is now payment status -->
            <td><span class="status ${(order.status)}">${order.status}</span></td>`  
        );

        // Append the row to the table body
        tableBody.append(row);
    });
}


function displayPaymentMethod(paymentMethod) {
    const ctxPie = document.getElementById('paymentBreakdownChart').getContext('2d');

    // Destroy the previous chart instance if it exists (prevents duplication issues)
    if (window.paymentBreakdownChart instanceof Chart) {
        window.paymentBreakdownChart.destroy();
    }

    if (!paymentMethod || paymentMethod.length === 0) {
        console.warn("No payment data available.");
        return;
    }

    // Extract payment method names and order counts
    const labels = paymentMethod.map(item => item.payment_method);
    const dataValues = paymentMethod.map(item => parseInt(item.numberOfOrders));

    // Generate unique colors dynamically
    const backgroundColors = [
        '#36A2EB', '#4CAF50', '#FFCE56', '#FF6384', '#9966FF', '#8D6E63', '#D4E157', '#AB47BC'
    ].slice(0, labels.length); // Adjust color count to match data length

    // Create the Pie Chart
    window.paymentBreakdownChart = new Chart(ctxPie, {
        type: 'pie',
        data: {
            labels: labels, // Set labels dynamically
            datasets: [{
                data: dataValues, // Set values dynamically
                backgroundColor: backgroundColors, // Apply generated colors
                hoverOffset: 12, // Smooth hover effect
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        font: {
                            size: 14,
                            weight: 'bold'
                        }
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(tooltipItem) {
                            let value = tooltipItem.raw;
                            let total = dataValues.reduce((acc, val) => acc + val, 0);
                            let percentage = ((value / total) * 100).toFixed(2);
                            return `${tooltipItem.label}: ${value} orders (${percentage}%)`;
                        }
                    }
                }
            }
        }
    });
}

