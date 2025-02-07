$(document).ready(function() {

    fetchReportsAnalytics();


    
});

function fetchReportsAnalytics() {
    const aggregation = $("#chartType").val();

    const queryString = $.param({ aggregation });
   
    $.ajax({
        url: '/admin/reports-analytics?' + queryString,
        method: 'GET',
        type: 'application/json',
        success: function(response) {
            displaySalesReport(response.sales);
            displaySalesTrendReport(response.earnings);
            displayPaymentMethodReport(response.payment_comparison);
            displayOrderStatus(response.order_status);
            displaySalesComparison(response.sales_comparison);
            displayTopSelling(response.top_products, response.top_services);

        }

    })
}

$('#chartType').on('change', function() {
    const aggregation = $("#chartType").val();

    const queryString = $.param({ aggregation });
   
    $.ajax({
        url: '/admin/reports-analytics?' + queryString,
        method: 'GET',
        contentType: 'application/json',
        success: function(response) {
             displaySalesReport(response.sales);
        }

    })

});


function displaySalesReport(data) { // bar chart // daily

    // Ensure 'data' contains both 'labels' and 'sales' arrays
    if (!data.labels || !data.sales || !Array.isArray(data.labels) || !Array.isArray(data.sales)) {
        console.error('Invalid data structure:', data);
        return; // If the structure is not valid, stop execution
    }

    // Now we can use the 'labels' and 'sales' arrays
    const labels = data.labels; // Array of months
    const salesData = data.sales; // Array of sales (e.g., gross_profit)

    console.log('Labels:', labels); // Verify the months
    console.log('Sales Data:', salesData); // Verify the sales data

    var ctx = document.getElementById('salesChart').getContext('2d');

    if (window.chart) {
        window.chart.destroy();
    }

    window.chart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,  // Use the 'labels' array for x-axis labels
            datasets: [{
                label: 'Sales Report',
                data: salesData,  // Use the 'sales' array for y-axis data
                backgroundColor: 'rgba(75, 192, 192, 0.2)', // Background color for the bars
                borderColor: 'rgba(75, 192, 192, 1)', // Border color for the bars
                borderWidth: 1,
                hoverBackgroundColor: 'rgba(75, 192, 192, 0.4)', // Hover effect for bars
                hoverBorderColor: 'rgba(75, 192, 192, 1)' // Border color on hover
            }]
        },
        options: {
            responsive: true, // Make the chart responsive
            maintainAspectRatio: false, // Allow the chart to adjust its aspect ratio on screen size change
            animation: {
                duration: 1000, // Animation duration in milliseconds
                easing: 'easeOutBounce' // Easing effect for animation
            },
            plugins: {
                tooltip: {
                    enabled: true, // Enable tooltips
                    mode: 'index', // Display tooltips for all bars on the same index
                    intersect: false, // Show tooltips even when not hovering directly over a bar
                    backgroundColor: 'rgba(0, 0, 0, 0.7)', // Tooltip background color
                    titleColor: 'white', // Title color inside tooltip
                    bodyColor: 'white' // Body color inside tooltip
                },
                legend: {
                    position: 'top', // Position the legend at the top
                    labels: {
                        font: {
                            size: 14 // Set font size for the legend labels
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,  // Start the y-axis at zero
                    grid: {
                        color: 'rgba(0, 0, 0, 0.1)', // Gridline color
                        lineWidth: 1 // Gridline width
                    },
                    ticks: {
                        font: {
                            size: 12 // Font size for y-axis ticks
                        }
                    }
                },
                x: {
                    grid: {
                        display: false // Hide x-axis gridlines
                    },
                    ticks: {
                        font: {
                            size: 12 // Font size for x-axis ticks
                        }
                    }
                }
            }
        }
    });
}


function displaySalesTrendReport(data) {
    const ctx = document.getElementById('earningsChart').getContext('2d');

    // Destroy existing chart instance if it exists (prevents duplication issues)
    if (window.salesTrendChartReport instanceof Chart) {
        window.salesTrendChartReport.destroy();
    }

    const labels = data.map(item => item.month);
    const dataValues = data.map(item => item.gross_profit);

    window.salesTrendChartReport = new Chart(ctx, {
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


function displayPaymentMethodReport(paymentMethod) {
    const ctxPie = document.getElementById('payBreakdownChart').getContext('2d');

    // Destroy the previous chart instance if it exists (prevents duplication issues)
    if (window.paymentBreakdownChartReport instanceof Chart) {
        window.paymentBreakdownChartReport.destroy();
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
    window.paymentBreakdownChartReport = new Chart(ctxPie, {
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


function displayOrderStatus(orderStatus) {

    const labels = orderStatus.map(index => index.status);
    const data = orderStatus.map(index => index.total_orders);

    var ctx = document.getElementById('orderStatusChart').getContext('2d');

    var chart = new Chart (ctx, {
        type: 'doughnut',
        data : {
            labels: labels,
            datasets: [{
                label: 'Order Status',
                data: data
            }]
        }
    })
}

function displaySalesComparison(salesComparison) {
    var ctx = document.getElementById('salesComparisonChart').getContext('2d');

    const labels = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];


    // Generate a random color for each type of payment (can also be customized)
    function generateRandomColor() {
        return `rgba(${Math.floor(Math.random() * 256)}, ${Math.floor(Math.random() * 256)}, ${Math.floor(Math.random() * 256)}, 0.5)`;
    }

    // Create datasets for each type with improved customizations
    const datasets = salesComparison.map((typeData, index) => ({
        label: typeData.type,  // Label for each payment type
        data: typeData.sales,  // Sales data for each month
        backgroundColor: index % 2 === 0 ? 'rgba(54, 162, 235, 0.2)' : 'rgba(255, 159, 64, 0.2)',  // Alternate between blue and orange
        borderColor: index % 2 === 0 ? 'rgba(54, 162, 235, 1)' : 'rgba(255, 159, 64, 1)',  // Alternate between blue and orange
        borderWidth: 1,
        hoverBackgroundColor: 'rgba(54, 162, 235, 0.7)',  // Hover color for bars
        hoverBorderColor: 'rgba(54, 162, 235, 1)',  // Hover border color
        hoverBorderWidth: 2,  // Border width on hover
    }));
    

    // Chart configuration
    var chart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: datasets,
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,  // To allow resizing without breaking aspect ratio
            scales: {
                y: {
                    beginAtZero: true,  // Ensure the Y-axis starts from zero
                    ticks: {
                        callback: function(value) {
                            return '₱' + value.toLocaleString();  // Format Y-axis labels as currency
                        }
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Month'  // X-axis label for months
                    }
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        // Custom tooltip to display values with additional formatting
                        label: function(tooltipItem) {
                            return tooltipItem.dataset.label + ': ₱' + tooltipItem.raw.toLocaleString();
                        }
                    }
                },
                legend: {
                    position: 'top',  // Position the legend at the top
                    labels: {
                        font: {
                            size: 14  // Adjust font size for better readability
                        }
                    }
                },
            },
            animation: {
                duration: 1000,  // Animation duration for rendering the bars
            }
        }
    });
}


function displayTopSelling(topProducts, topServices) {
    // Determine the maximum length between products and services
    const maxLength = Math.max(topProducts.length, topServices.length);

    let tableContent = '';
    for (let i = 0; i < maxLength; i++) {
        const product = topProducts[i] || { name: '-', quantity: '-' };  // Default if undefined
        const service = topServices[i] || { name: '-', quantity: '-' };  // Default if undefined

        tableContent += `
            <tr>
                <td>${product.name}</td>
                <td>${product.quantity}</td>
                <td>${service.name}</td>
                <td>${service.quantity}</td>
            </tr>
        `;
    }

    // Append the rows to the table
    $('#topSellingTable tbody').html(tableContent);
}


