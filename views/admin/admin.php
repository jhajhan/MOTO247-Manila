<!DOCTYPE html>
<html lang="en">
<head>
    <!---final code---->
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Sharp" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MOTO247 Management System</title>
    <link rel="stylesheet" href="/assets/css/admin.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>


    <!----------------------------------------chartjs----------------------------------->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
</head>

<!---------------------------------------------------body----------------------------------------->
<body>
    <!---------------------------------------------container---------------------------------------------------------->
    <div class="container">
        <!----------------------------------------------------navigations---------------------------------------------->
        <div class="navigation">
            <ul>
                <!------------------------------------- logo Section --------------------------->
                <li class="logo-item">
                    <a href="#">
                        <span class="logo">
                            <img src="/assets/img/logo.jpg" alt="Brand Logo">
                        </span>
                    </a>
                </li>

                <li>
                    <a href="/admin/dashboard" data-section="dashboard">
                        <span class="icon">
                            <ion-icon name="home-outline"></ion-icon>
                        </span>
                        <span title="title">Dashboard</span>
                    </a>
                </li>

                <li>
                    <a href="/admin/product-service" data-section="products-services">
                        <span class="icon">
                            <ion-icon name="cart-outline"></ion-icon>
                        </span>
                        <span title="title">Products and Services</span>
                    </a>
                </li>

                <li>
                    <a href="/admin/sales" data-section="sales-management">
                        <span class="icon">
                            <ion-icon name="document-text-outline"></ion-icon>
                        </span>
                        <span title="title">Sales Management</span>
                    </a>
                </li>

                <li>
                    <a href="/admin/reports-analytics" data-section="reports-analytics">
                        <span class="icon">
                            <ion-icon name="analytics-outline"></ion-icon>
                        </span>
                        <span title="title">Reports and Analytics</span>
                    </a>
                </li>

                <li>
                    <a href="/admin/settings" data-section="settings">
                        <span class="icon">
                            <ion-icon name="settings-outline"></ion-icon>
                        </span>
                        <span title="title">Settings</span>
                    </a>
                </li>

                <li>
                    <a class="sign-out-btn" id = 'admin-sign-out' >
                        <span class="icon">
                            <ion-icon name="log-out-outline"></ion-icon>
                        </span>
                        <span title="title">Sign Out</span>
                    </a>
                </li>
            </ul>
        </div>
        <!--------------------------------------------end of navigations----------------------------------------->
        

        <!-----------------------------------------------------main section----------------------------------->
            <div class="main">
                <!-----------------------------------------------top bar section--------------------------------->
                <div class="topbar"> 
                    <div class="toggle">
                        <ion-icon name="menu-outline"></ion-icon>
                    </div>
                    
                    <div class="search">
                        <label>
                            <input text="text" placeholder="Search Here">
                            <ion-icon name="search-outline"></ion-icon>
                        </label>
                    </div>
    
                    <div class="user">
                        <img src="/assets/img/jali.jpg" alt="">
                        <span id = 'admin-name' class="user-name"></span>
                    </div>
                </div>
                <!-------------------------------------------end of top bar-------------------->
    
                <!-------------------------------------- dashboard section ---------------------------------------->
                <div id="dashboard" class="section active"> 
                <div class="cardBox">
                    <div class="card">
                        <div>
                            <div class="cardDate">
                                <input type="date" class="dateInput" />
                            </div>
                            <div class="numbers" id = "total-sales">15,000</div>
                            <div class="cardName">Total Sales</div>
                        </div>
    
                        <div class="iconBx">
                            <ion-icon name="analytics-outline"></ion-icon>
                        </div>
                    </div>

                    <div class="card">
                        <div>
                            <div class="filter-group">
                                <select id="month">
                                    <option value="01">January</option>
                                    <option value="02">February</option>
                                    <option value="03">March</option>
                                    <option value="04">April</option>
                                    <option value="05">May</option>
                                    <option value="06">June</option>
                                    <option value="07">July</option>
                                    <option value="08">August</option>
                                    <option value="09">September</option>
                                    <option value="10">October</option>
                                    <option value="11">November</option>
                                    <option value="12">December</option>
                                </select>
                            </div>
                            <div class="numbers" id = "total-profit-monthly"></div>
                            <div class="cardName">Monthly Profit</div>
                        </div>
    
                        <div class="iconBx">
                            <ion-icon name="calendar-outline"></ion-icon>
                        </div>
                    </div>
                    
                    <div class="card">
                        <div>
                            <div class="numbers" id = "total-profit-daily"></div>
                            <div class="cardName">Daily Profit</div>
                        </div>
    
                        <div class="iconBx">
                            <ion-icon name="cash-outline"></ion-icon>
                        </div>
                    </div>
                    
                    <div class="card">
                        <div>
                            <div class="numbers" id = "total-orders"></div>
                            <div class="cardName">Total Orders</div>
                        </div>
    
                        <div class="iconBx">
                            <ion-icon name="cart-outline"></ion-icon>
                        </div>
                    </div>
    
                    <div class="card">
                        <div>
                            <div class="numbers" id = "total-products-services"></div>
                            <div class="cardName">Products and Services Available</div>
                        </div>
    
                        <div class="iconBx">
                            <ion-icon name="basket-outline"></ion-icon>
                        </div>
                    </div>
                </div>
    
                <div class="details">
                    <!---------------------------------------------------- dashboard charts------------------------->
                    <div class="charts">
                        <!-- Sales Trend Chart -->
                        <div class="chart">
                            <div class="cardHeader">
                                <h2>Earnings (Past 12 Months)</h2>
                            </div>
                            <canvas id="salesTrendChart"></canvas>
                        </div>
                
                        <!-- Payment Method Pie Chart -->
                        <div class="chart" id="doughnut-chart">
                            <div class="cardHeader">
                                <h2>Payment Method Breakdown</h2>
                            </div>
                            <canvas id="paymentBreakdownChart"></canvas>
                        </div>
                    </div>
                    
                    <!--------------------------------------------------end of charts--------------------------------------->
                    <div class="recentOrders">
                        <div class="cardHeader">
                            <h2> Recent Orders </h2>
                            <a href="#" class="btn"> View All </a>
                        </div>
                    
                        <div class="tableContainer">
                            <div class="tableHeader">
                                <table>
                                    <thead>
                                        <tr>
                                            <td> Name</td>
                                            <td> Orders </td>
                                            <td> Price </td>
                                            <td> Payment </td>
                                            <td> Status </td>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                            <div class="tableBody">
                                <table>
                                    <tbody>
                                       
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>                    
                </div>
                <!----------------------------------------------------end of table------------------------------------------>

                <!-----------------------------------------dashboard generate report-------------------------------->
                    <div class="generate-report-dashboard">
                        <button id="generateReportBtn" class="generate-btn-dashboard">Generate Report</button>
                    </div>
                </div>
                <!-----------------------------------end of dashbord section-------------------------------->


                <!-------------------------------products and service section-------------------------------->
                <div id="products-services" class="section">
                    <div class="productHeader">
                        <h2>Products and Services Management</h2>
                    </div>

                    <!-------------------------------------------------- Filter Section --------------------------------->
                    <div class="filter-section">
                        <select id="typeFilter">
                            <option value="all">Type (All)</option>
                            <option value="product">Product</option>
                            <option value="service">Service</option>
                        </select>

                        <select id="stockFilter">
                            <option value="all">Stock Status (All)</option>
                            <option value="inStock">In Stock</option>
                            <option value="outOfStock">Out of Stock</option>
                        </select>

                        <label for="priceRange">Price Range:</label>
                        <input type="number" id="minPrice" placeholder="Min" />
                        <input type="number" id="maxPrice" placeholder="Max" />

                        <!-------------------------------- Filter Button --------------------------->
                        <button id="filterBtn" class="btn">Filter</button>

                        <!---------------------- Add New Product or Service Button -------------------->
                        <button id="addProductServiceBtn" class="btn">Add New Product/Service</button>
                    </div>

                    <!-------------------------------- Product and Service Table --------------------------->
                    <div class="product-table">
                        <table>
                            <thead>
                                <tr>
                                    <td>ID</td>
                                    <td>Name</td>
                                    <td>Type</td>
                                    <td>Price</td>
                                    <td>Unit Price</td>
                                    <td>Stock</td>
                                    <td>Actions</td>
                                </tr>
                            </thead>
                            <tbody id="productServiceTableBody">
                                <!-- Products and service are in here -->
                            </tbody>
                        </table>
                    </div>

                    <!----------------------------------------------- Modal for Adding and Editing Products/Services -------------------------->
                    <div id="productModal" class="modal">
                        <div class="modal-content">
                            <span id="close-edit-add-modal" class="close-btn">&times;</span>
                            <h3 id="modalTitle">Add Product/Service</h3>
                            <form id="productServiceForm">

                                <input type = "hidden" id = "productID">

                                <label for="productName">Name:</label>
                                <input type="text" id="productName" required />
                                
                                <label for="productType">Type:</label>
                                <select id="productType" required>
                                    <option value="product">Product</option>
                                    <option value="service">Service</option>
                                </select>
                                
                                <label for="productPrice">Price:</label>
                                <input type="number" id="productPrice" required />

                                <label for="productPrice">Unit Price:</label>
                                <input type="number" id="productUnitPrice" required />
                                
                                <label for="productStock">Stock:</label>
                                <input type="number" id="productStock" required />
                                
                                <label for="productDescription">Description:</label>
                                <textarea id="productDescription" required></textarea>
                                
                                <label for="productImage">Image:</label>
                                <input type="file" id="file1" name="'file" accept="image/*" />
                                
                                <button id = 'add-product-save' type="submit">Save</button>
                                <button type="button" id="cancelBtn">Cancel</button>
                            </form>
                        </div>
                    </div>
                </div>
                <!--------------------------------end of products and services section----------------------------------->

                
                <!---------------------------------------sales management section---------------------------------->
                <div id="sales-management" class="section">
                    <div class="salesHeader">
                        <div class="button-wrapper">
                            <button id="toggle-sales-btn">Toggle Sales</button>
                            <button id="generate-report-btn-sales">Generate Report</button>
                        </div>
                        
                    </div>
                    
                    <!----------------------------------------------- online sales table --------------------------------------->
                
                    <div id="online-sales" class="sales-table">
                        <div class="onlineSalesHeader">
                            <h2>Online Sales</h2>
                        </div>
                        
                        <!-- filters -->
                        <div class="filter-container">
                            <select id="online-status-filter">
                                <option value="">Filter by Status (All)</option>
                                <option value="pending">Pending</option>
                                <option value="ready">Ready for Pickup</option>
                                <option value="picked-up">Picked Up</option>
                                <option value="received">Received</option>
                                <option value="completed">Completed</option>
                            </select>
                            <select id="online-payment-method-filter">
                                <option value="">Filter by Payment Method (All)</option>
                                <option value="cash">Cash</option>
                                <option value="gcash">GCash</option>
                            </select>
                            <select id="online-payment-status-filter">
                                <option value="">Filter by Payment Status (All)</option>
                                <option value="pending">Pending</option>
                                <option value="paid">Paid</option>
                            </select>
                            <button id="online-apply-filters-btn" class="btn">Filter</button>
                        </div>
                
                        <!-- table -->
                        <div class="table-wrapper">
                            <table id="online-sales-table">
                                <thead>
                                    <tr>
                                        <th>Order ID</th>
                                        <th>Customer ID</th>
                                        <th>Customer Name</th>
                                        <th>Phone Number</th>
                                        <th>Address</th>
                                        <th>Delivery Option</th>
                                        <th>Product/Service</th>
                                        <th>Order Date</th>
                                        <th>Payment Method</th>
                                        <th>Payment Status</th>
                                        <th>Status</th>
                                        <th>Total Amount</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id = "onlineSalesTableBody">
                                    <tr>
                                        <!-- Sales data is in here -->
                                    </tr>
                                    <!-- Additional rows here -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                
                    <!--------------------------------------------- Physical Sales Table ----------------------------------->
                
                    <div id="physical-sales" class="sales-table">
                        <div class="physicalSalesHeader">
                            <h2>Physical Sales</h2>
                        </div>
                
                        <!-- Filters -->
                        <div class="filter-container">
                            <select id="physical-status-filter">
                                <option value="">Filter by Status (All)</option>
                                <option value="pending">Pending</option>
                                <option value="ready">Ready for Pickup</option>
                                <option value="picked-up">Picked Up</option>
                                <option value="received">Received</option>
                                <option value="completed">Completed</option>
                            </select>
                            <select id="physical-payment-method-filter">
                                <option value="">Filter by Payment Method (All)</option>
                                <option value="cash">Cash</option>
                                <option value="gcash">GCash</option>
                            </select>
                            <select id="physical-payment-status-filter">
                                <option value="">Filter by Payment Status (All)</option>
                                <option value="pending">Pending</option>
                                <option value="paid">Paid</option>
                            </select>
                            <button id="physical-apply-filters-btn" class="btn">Filter</button>
                            <button id="add-physical-order-btn" class="btn">Add Order</button>
                        </div>
                
                        <!-- Table with fixed header -->
                        <div class="table-wrapper">
                            <table id="physical-sales-table">
                                <thead>
                                    <tr>
                                    <th>Order ID</th>
                                        <th>Customer ID</th>
                                        <th>Customer Name</th>
                                        <th>Phone Number</th>
                                        <th>Address</th>
                                        <th>Delivery Option</th>
                                        <th>Product/Service</th>
                                        <th>Order Date</th>
                                        <th>Payment Method</th>
                                        <th>Payment Status</th>
                                        <th>Status</th>
                                        <th>Total Amount</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id = "physicalSalesTableBody">
                                    <tr>
                                        <!-- Sales data is in here -->
                                    </tr>
                                    <!-- Additional rows here -->
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!--------------------------------------------------- Edit Modal --------------------------------------->
                    <div id="edit-modal" class="modal">
                        <div class="modal-content">
                            <span id="close-edit-modal" class="close-btn">&times;</span>
                            <h3>Edit Order</h3>
                            <form id="edit-form" method = 'PUT'>
                                <input id = "edit-id" type = "hidden">
                                <label for="edit-payment-method">Payment Method:</label>
                                <select id="edit-payment-method" required>
                                    <option value="cash">Cash</option>
                                    <option value="gcash">GCash</option>
                                </select>
                                <label for="edit-payment-status">Payment Status:</label>
                                <select id="edit-payment-status" required>
                                    <option value="pending">Pending</option>
                                    <option value="paid">Paid</option>
                                </select>
                                <label for="edit-status">Status:</label>
                                <select id="edit-status" required>
                                    <option value="pending">Pending</option>
                                    <option value="ready">Ready for Pickup</option>
                                    <option value="picked-up">Picked Up</option>
                                    <option value="received">Received</option>
                                    <option value="completed">Completed</option>
                                </select>
                                <button type="submit">Save</button>
                                <button type="button" id="cancel-edit-order-btn">Cancel</button>
                            </form>
                        </div>
                    </div>

                    <!----------------------------------------------- Add Modal for Physical Sales -------------------------------->
                    <div id="add-physical-order-modal" class="modal">
                        <div class="modal-content">
                            <span id="close-add-modal" class="close-btn">&times;</span>
                            <h3>Add Physical Order</h3>
                            <form id="add-physical-form">
                 
                                <label for="physical-order-id">Order ID:</label>
                                <input type="text" id="physical-order-id" readonly>

                                <label for="physical-customer-id">Customer ID:</label>
                                <input type="text" id="physical-customer-id" readonly>

                                <label for="physical-customer-name">Customer Name:</label>
                                <input type="text" id="physical-customer-name" required>

                                <label for="phone">Phone Number:</label>
                                <input type="tel" id="physical-phone" name="phone" 
                                    pattern="^(09|\+63)[\d]{9}$" 
                                    placeholder="Enter your phone number" required>
        
                            

                                <div id="product-list" style="margin-top: 5px;">
                                    <h4>Products/Services:</h4>
                                    <div id="product-dropdown-container">
                                        <!-- Dynamic dropdown will be inserted here -->
                                    </div>
                                    <button type="button" id="add-product">+</button>
                                </div>

                            
                                           
                                <label for="physical-order-date">Order Date:</label>
                                <input type="date" id="physical-order-date" required>

                                <label for="physical-payment">Payment Method:</label>
                                <select id="physical-payment" required>
                                    <option value="cash">Cash</option>
                                    <option value="gcash">GCash</option>
                                </select>

                                <label for="physical-payment-status">Payment Status:</label>
                                <select id="physical-payment-status" required>
                                    <option value="paid">Paid</option>
                                    <option value="pending">Pending</option>
                                </select>

                                <label for="physical-status">Status:</label>
                                <select id="physical-status" required>
                                    <option value="pending">Pending</option>
                                    <option value="ready">Ready for Pickup</option>
                                    <option value="picked-up">Picked Up</option>
                                    <option value="received">Received</option>
                                    <option value="completed">Completed</option>
                                </select>
                                
                                <button type="submit">Add Order</button>
                                <button type="button" id="cancel-add-order-btn">Cancel</button>
                            </form>
                        </div>
                    </div>
                </div>
                <!---------------------------------------end of sales management section----------------------------------------->

                <!------------------------------------------reports and analytics section------------------------->
                <div id="reports-analytics" class="section">

                    <!-- All Charts and Table in One Page -->
                    <div id="charts-container">
                        <!-- Daily/Weekly/Monthly Sales Chart -->
                        <div class="chart-section">
                            <h3>Sales Report</h3>
                            <label for="chartType">Select Chart Type:</label>
                            <select id="chartType" onchange="updateSalesChart()">
                                <option value="daily">Daily</option>
                                <option value="weekly">Weekly</option>
                                <option value="monthly">Monthly</option>
                            </select>
                            <canvas id="salesChart" ></canvas>
                        </div>
                        
                        <!-- Earnings for the Past 12 Months -->
                        <div class="chart-section">
                            <h3>Earnings (for the Past 12 Months)</h3>
                            <canvas id="earningsChart" ></canvas>
                        </div>

                        <!-- GCash vs Cash Breakdown -->
                        <div class="chart-section">
                            <h3>Payment Breakdown</h3>
                            <canvas id="payBreakdownChart"></canvas>
                        </div>
                
                        <!-- Order Status Reports -->
                        <div class="chart-section">
                            <h3>Order Status Reports</h3>
                            <canvas id="orderStatusChart"></canvas>
                        </div>

                        <!-- Table for Top Selling Products and Services -->
                        <div class="chart-section">
                            <h3>Top Selling Products and Services</h3>
                            <table id="topSellingTable">
                                <thead>
                                    <tr>
                                        <th>Products</th>
                                        <th>Quantity</th>
                                        <th>Services</th>
                                        <th>Quantity</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Product A</td>
                                        <td>10</td>
                                        <td>Service A</td>
                                        <td>120</td>
                                    </tr>
                                    <tr>
                                        <td>Product B</td>
                                        <td>20</td>
                                        <td>Service B</td>
                                        <td>100</td>
                                    </tr>
                                    <tr>
                                        <td>Product C</td>
                                        <td>14</td>
                                        <td>Service C</td>
                                        <td>90</td>
                                    </tr>
                                    <tr>
                                        <td>Product D</td>
                                        <td>4</td>
                                        <td>Service D</td>
                                        <td>80</td>
                                    </tr>
                                    <tr>
                                        <td>Product E</td>
                                        <td>6</td>
                                        <td>Service E</td>
                                        <td>70</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Online vs Physical Sales Comparison -->
                        <div class="chart-section">
                            <h3>Sales Comparison</h3>
                            <canvas id="salesComparisonChart"></canvas>
                        </div>

                        

                        <button id="generate-report-btn-reports">Generate Report</button>
                    </div>   
                </div>  
                <!--------------------------------------------end of reports and analytics setion------------------------------>

                <!---------------------------------------settings section-------------------------------------------------------->
                <div id="settings" class="section">     
                    <div class="settings-container">   
                        <ul id="settings-nav">
                            <h2>Settings</h2>
                            <p>Settings options go here.</p>
                            <li><a href="#" data-section="user-management">User Management</a></li>
                            <li><a href="#" data-section="general-store-info">General Store Information</a></li>
                            <li><a href="#" data-section="payment-info">Payment Information</a></li>
                            <li><a href="#" data-section="backup-restore">Backup and Restore</a></li>
                        </ul>
                        
                        <!-- Right-side Content Area -->
                        <div id="right-content">
                            <div id="user-management" class="section-content">
                                <!-- Edit Profile Section -->
                                <div id="edit-profile">
                                    <h3>Edit Profile Details</h3>
                                    <form id = "edit-profile-form" method="post">
                                        <div class="form-group">
                                            <label for="name">Full Name</label>
                                            <input type="text" id="settings-name" name="name" placeholder="Enter your full name" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="email">Email</label>
                                            <input type="email" id="settings-email" name="email" placeholder="Enter your email" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="password">Old Password</label>
                                            <input type="password" id="settings-oldpassword" name="password" placeholder="Enter old password" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="password">New Password</label>
                                            <input type="password" id="settings-newpassword" name="password" placeholder="Enter a new password" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="confirm-password">Re-enter New Password</label>
                                            <input type="password" id="settings-confirm-password" name="confirm-password" placeholder="Re-enter your password" required>
                                        </div>
                                        <button type="submit" class="submit-btn">Save Changes</button>
                                    </form>
                                </div>

                                 <!-- Admin Users Management Section -->
                                <div id="admin-users">
                                    <!-- Add Admin Section -->
                                    <div id="add-admin">
                                        <h3>Manage Admin Users</h3>
                                        <h4>Add Admin</h4>
                                        
                                        <form id = "add-admin-form" method="post">

                                            <div class="form-group">
                                                <label for="admin-name">Username</label>
                                                <input type="text" id="admin-username" name="admin-name" placeholder="Enter admin's full name" required>
                                            </div>

                                            <div class="form-group">
                                                <label for="admin-name">Full Name</label>
                                                <input type="text" id="admin-name" name="admin-name" placeholder="Enter admin's full name" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="admin-email">Email</label>
                                                <input type="email" id="admin-email" name="admin-email" placeholder="Enter admin's email" required>
                                            </div>

                                            <div class="form-group">
                                                <label for="password">Password</label>
                                                <input type="password" id="admin-password" name="password" placeholder="Enter password" required>
                                            </div>

                                            <button type="submit" class="submit-btn">Add Account</button>
                                        </form>
                                    </div>

                                    <!-- Existing Admins List -->
                                    <div id="remove-admins">
                                        <h4>Remove Admin</h4>
                                        <ul id="admin-list">
                                            <!-- Admins are inserted here -->
                                        </ul>
                                    </div>
                                </div>          
                            </div>

                            <div id="general-store-info" class="section-content">
                                <h3>General Store Information</h3>
                                <p>Update store information.</p>
                                <form id="edit-general-form"  method = "PUT" enctype="multipart/form-data">
                                    <div class="form-group">
                                        <label for="store-name">Store Name</label>
                                        <input type="text" id="store-name" name="store-name" placeholder="Enter store name" required>
                                    </div>
                            
                                    <div class="form-group">
                                        <label for="store-address">Store Address</label>
                                        <textarea id="store-address" name="store-address" placeholder="Enter store address" required></textarea>
                                    </div>
                            
                                    <div class="form-group">
                                        <label for="contact-number">Contact Number</label>
                                        <input type="tel" id="contact-number" name="contact-number" placeholder="Enter contact number" required>
                                    </div>
                            
                                    <div class="form-group">
                                        <label for="business-hours">Business Hours</label>
                                        <input type="text" id="business-hours" name="business-hours" placeholder="Enter business hours (e.g., 9:00 AM - 5:00 PM)" required>
                                    </div>
                            
                                    <!-- <div class="form-group">
                                        <label for="store-logo">Upload Store Logo</label>
                                        <input type="file" id="store-logo" name="store-logo" accept="image/*" >
                                    </div> -->
                            
                                    <button type="submit" class="submit-btn">Update Information</button>
                                </form>
                            </div>

                            <div id="payment-info" class="section-content">
                                <h3>Payment Information</h3>
                                <p>Manage payment settings and methods.</p>
                                <!-- GCash Information Update Section -->
                                <h4>Update GCash Information</h4>
                                <form id="edit-payment-form" method="PUT">
                                    <div class="form-group">
                                        <label for="gcash-name">Name on GCash</label>
                                        <input type="text" id="gcash-name" name="gcash-name" placeholder="Enter your name on GCash" required>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="gcash-number">GCash Number</label>
                                        <input type="tel" id="gcash-number" name="gcash-number" placeholder="Enter your GCash number" required>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="gcash-qr">Upload GCash QR Code</label>
                                        <input type="file" id="gcash-qr" name="gcash-qr" accept="image/*" >
                                    </div>

                                    <button type="submit" class="submit-btn">Update Information</button>
                                </form>
                            </div>

                            <div id="backup-restore" class="section-content">
                                <h3>Backup and Restore</h3>
                                <p>Protect your data with backup and recovery options.</p>

                                <!-- Database Backup Section -->
                                <h4>Database Backup</h4>
                                <p>Backup your system's database to protect against data loss. This will create a snapshot of your database at the time of backup, which can be restored later if needed.</p>
                                <form id="database-backup-form"  method="post">
                                    <button type="submit" class="submit-btn">Backup Database</button>
                                </form>
                                <p class="note">Note: The backup process may take a few minutes depending on the size of the database.</p>

                                <!-- Database Restore Section -->
                                <h4>Restore Database from Backup</h4>
                                <p>Restore your database from a previous backup file. Choose the backup file you want to restore from your local system.</p>
                                <form id="database-restore-form" action="#" method="post" enctype="multipart/form-data">
                                    <div class="form-group">
                                        <label for="backup-file">Choose Backup File:</label>
                                        <input type="file" id="backup-file" name="backup-file" accept=".sql,.zip,.tar,.gz" required>
                                    </div>
                                    <button type="submit" class="submit-btn">Restore Database</button>
                                </form>
                                <p class="note">Note: Restoring from a backup will overwrite the current database with the selected backup file. Proceed with caution.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <!------------------------------------------end of settings section------------------------------------>
                
                <!---------------------------------sign out section--------------------------------->
                <div id="sign-out" class="section">
                    <h2>Sign Out</h2>
                    <button id="sign-out-btn">Sign Out</button>
                </div>
                
                <!------------------------------end of sign out section----------------------------------------------->

            </div> 
            <!------------------------------------------------end of main------------------------------->
    </div>
        <!---------------------------------------------------end of container---------------->

    <!----------------------- js file -------------------------->
    <script src='/assets/js/admin/admin.js'></script>
    <script src= '/assets/js/admin/dashboard.js'></script>
    <script src= '/assets/js/admin/main.js'></script>
    <script src= '/assets/js/admin/product_service.js'></script>
    <script src= '/assets/js/admin/sales.js'></script>
    <script src= '/assets/js/admin/reports_analytics.js'></script>
    <script src= '/assets/js/admin/settings.js'></script>

    <!------------------------------------ ionicons ---------------------------->
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>
</html>

