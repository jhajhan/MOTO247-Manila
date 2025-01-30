$(document).ready(function() {

    fetchReportsAnalytics();


    
});

function fetchReportsAnalytics() {
    $.ajax({
        url: '/admin/reports-analytics',
        method: 'GET',
        type: 'application/json',
        success: function(response) {
            displaySalesReport(response.salesReport);
            displaySalesTrends(response.salesTrends);
            displayPaymentMethod(response.paymentMethod);
            displayOrderStatus(response.orderStatus);
            displaySalesComparison(response.salesComparison);
            displayTopProducts(response.topProducts);
            displayTopServices(response.topServices);
        }

    })
}

$('#sales-report-dropdown').on('change', function() {
    let selectedValue = $(this).val();
    fetchSalesReport(selectedValue);

});


function fetchSalesReport(selectedValue) {
    $.ajax({
        url: '/admin/sales-report',
        method: 'GET',
        data: { selectedValue: selectedValue },
        success: function(response) {
            
        }
    })
}

function displaySalesReport(salesReport) { //bar chart //daily

    var ctx = document.getElementById('sales-report').getContext('2d');
    var chart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: salesReport.labels,
            datasets: [{
                label: 'Sales Report',
                data: salesReport.data,
                backgroundColor: white,
                borderColor: black,
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

function displaySalesTrends(salesTrends) {

    var ctx = document.getElementById('sales-trends').getContext('2d');

    var chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: salesTrends.labels,
            datasets: [{
                label: 'Sales Trend',
                data: salesTrends.data,

            }]
        }
    }) 
}


function displayPaymentMethod (paymentMethod) {

    var ctx = document.getElementById('payment-method').getContext('2d');

    var chart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: paymentMethod.labels,
            datasets: [{
                label: 'Payment Method',
                data: paymentMethod.data
            }]
        }
    })
}

function displayOrderStatus(orderStatus) {
    var ctx = document.getElementById('order-status').getContext('2d');

    var chart = new Chart (ctx, {
        type: 'doughnut',
        data : {
            labels: orderStatus.labels,
            datasets: [{
                label: 'Order Status',
                data: orderStatus.data
            }]
        }
    })
}

function displaySalesComparison(salesComparison) {
    var ctx = document.getElementById('order-status').getContext('2d');

    var chart = new Chart (ctx, {
        type: 'bar',
        data : {
            labels: salesComparison.labels,
            datasets: [{
                label: 'Physical Sales',
                data: orderStatus.physical_sales
            },
            {
                label: 'Online Sales',
                data: orderStatus.online_status
            }]
        }
    })
}

function displayTopProducts() {

}

function displayTopProducts() {

}