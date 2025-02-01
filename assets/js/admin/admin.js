/*-----final code-----*/




let list = document.querySelectorAll(".navigation li a");

function activeLink() {
    list.forEach((item) => {
        item.parentElement.classList.remove("hovered");
    });
    this.parentElement.classList.add("hovered");

    // show corresponding section
    const sectionId = this.getAttribute("data-section");
    showSection(sectionId);
}

list.forEach((item) => item.addEventListener("click", activeLink));

function showSection(sectionId) {
    const sections = document.querySelectorAll(".section");
    sections.forEach((section) => {
        if (section.id === sectionId) {
            section.classList.add("active");
        } else {
            section.classList.remove("active");
        }
    });
}

/*--------------------------------------------------------------menu toggle--------------------------------------------------------*/
let toggle = document.querySelector(".toggle");
let navigation = document.querySelector(".navigation");
let main = document.querySelector(".main");
let sidebar = document.querySelector('.navigation');
let topbar = document.querySelector('.topbar');

// Add event listener to the toggle button
toggle.onclick = function () {
    navigation.classList.toggle("active");
    main.classList.toggle("active");
    sidebar.classList.toggle('active'); // Toggle the active class for the sidebar
    topbar.classList.toggle('active');  // Toggle the active class for the top bar
};

document.addEventListener("DOMContentLoaded", () => {
    showSection("dashboard");
});

/*----------------------------------------------------------------dashboard charts----------------------------------------------------*/
// sales trend
const ctxLine = document.getElementById('salesTrendChart').getContext('2d');
const salesTrendChart = new Chart(ctxLine, {
    type: 'line',
    data: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        datasets: [{
            label: 'Earnings (₱)',
            data: [1500, 2000, 1800, 2200, 2400, 3000, 2800, 3200, 3500, 4000, 3800, 4200],
            borderColor: '#4CAF50',
            backgroundColor: 'rgba(76, 175, 80, 0.2)',
            borderWidth: 2,
            tension: 0.3,
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'top',
            },
        },
    },
});

// payment method
const ctxPie = document.getElementById('paymentBreakdownChart').getContext('2d');
const paymentBreakdownChart = new Chart(ctxPie, {
    type: 'pie',
    data: {
        labels: ['GCash', 'Cash'],
        datasets: [{
            data: [45, 25],
            backgroundColor: ['#36A2EB', '#4CAF50'],
            hoverOffset: 10,
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'top',
            },
        },
        aspectRatio: 1.3, 
    },
});


/*--------------------------------------------------------------------products and services crud------------------------------------------*/
// document.addEventListener('DOMContentLoaded', () => {
//     const productServiceTableBody = document.getElementById('productServiceTableBody');
//     const addProductServiceBtn = document.getElementById('addProductServiceBtn');
//     const modal = document.getElementById('productModal');
//     const cancelBtn = document.getElementById('cancelBtn');
//     const productServiceForm = document.getElementById('productServiceForm');
//     const filterBtn = document.getElementById('filterBtn'); 
//     const typeFilter = document.getElementById('typeFilter');
//     const stockFilter = document.getElementById('stockFilter');
//     const minPrice = document.getElementById('minPrice');
//     const maxPrice = document.getElementById('maxPrice');
//     const closeEditAddModal = document.getElementById("close-edit-add-modal");
    
//     // sample data
//     let productServiceList = [
//         { id: '001', name: 'Laptop', type: 'Product', price: 500, stock: 10, description: 'A high-performance laptop', image: 'laptop.jpg' },
//         { id: '002', name: 'Repair Service', type: 'Service', price: 50, stock: 'N/A', description: 'Car repair service', image: 'repair-service.jpg' },
//         { id: '003', name: 'Headphones', type: 'Product', price: 100, stock: 0, description: 'Noise-canceling headphones', image: 'headphones.jpg' },
//         { id: '004', name: 'Cleaning Service', type: 'Service', price: 30, stock: 'N/A', description: 'House cleaning service', image: 'cleaning-service.jpg' },
//         { id: '005', name: 'Smartphone', type: 'Product', price: 300, stock: 25, description: 'Latest model with high performance', image: 'smartphone.jpg' },
//         { id: '006', name: 'Web Development Service', type: 'Service', price: 500, stock: 'N/A', description: 'Build your website from scratch', image: 'webdev-service.jpg' },
//         { id: '007', name: 'Tablet', type: 'Product', price: 150, stock: 50, description: 'Portable tablet with powerful specs', image: 'tablet.jpg' },
//         { id: '008', name: 'Cleaning Service', type: 'Service', price: 40, stock: 'N/A', description: 'Home and office cleaning', image: 'cleaning-service2.jpg' },
//         { id: '009', name: 'Keyboard', type: 'Product', price: 80, stock: 30, description: 'Mechanical keyboard with RGB lighting', image: 'keyboard.jpg' },
//         { id: '010', name: 'Graphic Design Service', type: 'Service', price: 200, stock: 'N/A', description: 'Custom logos and designs', image: 'graphic-design.jpg' },
//         { id: '011', name: 'Wireless Mouse', type: 'Product', price: 30, stock: 40, description: 'Ergonomic wireless mouse', image: 'mouse.jpg' },
//         { id: '012', name: 'SEO Service', type: 'Service', price: 150, stock: 'N/A', description: 'Search engine optimization for your website', image: 'seo-service.jpg' },
//         { id: '013', name: 'Bluetooth Speaker', type: 'Product', price: 70, stock: 20, description: 'Portable Bluetooth speaker with deep bass', image: 'bluetooth-speaker.jpg' },
//         { id: '014', name: 'Video Editing Service', type: 'Service', price: 250, stock: 'N/A', description: 'Professional video editing services', image: 'video-editing.jpg' },
//         { id: '015', name: 'Smartwatch', type: 'Product', price: 120, stock: 15, description: 'Fitness tracking smartwatch with GPS', image: 'smartwatch.jpg' } ,
//         { id: '012', name: 'SEO Service', type: 'Service', price: 150, stock: 'N/A', description: 'Search engine optimization for your website', image: 'seo-service.jpg' },
//         { id: '013', name: 'Bluetooth Speaker', type: 'Product', price: 70, stock: 20, description: 'Portable Bluetooth speaker with deep bass', image: 'bluetooth-speaker.jpg' },
//         { id: '014', name: 'Video Editing Service', type: 'Service', price: 250, stock: 'N/A', description: 'Professional video editing services', image: 'video-editing.jpg' },
//         { id: '015', name: 'Smartwatch', type: 'Product', price: 120, stock: 15, description: 'Fitness tracking smartwatch with GPS', image: 'smartwatch.jpg' }
//     ];
    
//     // function to display products/services in the table
//     function displayProductsServices(filteredList) {
//         productServiceTableBody.innerHTML = '';
//         const listToDisplay = filteredList || productServiceList;
        
//         listToDisplay.forEach(product => {
//             const row = document.createElement('tr');
//             row.innerHTML = `
//                 <td>${product.id}</td>
//                 <td>${product.name}</td>
//                 <td>${product.type}</td>
//                 <td>$${product.price}</td>
//                 <td>${product.stock}</td>
//                 <td>
//                     <button class="editBtn">Edit</button>
//                     <button class="deleteBtn">Delete</button>
//                 </td>
//             `;
//             productServiceTableBody.appendChild(row);

//             // add edit functionality
//             const editBtn = row.querySelector('.editBtn');
//             editBtn.addEventListener('click', () => {
//                 editProductService(product.id);
//             });

//             // add delete functionality
//             const deleteBtn = row.querySelector('.deleteBtn');
//             deleteBtn.addEventListener('click', () => {
//                 deleteProductService(product.id);
//             });
//         });
//     }

//     // function to filter products/services based on selected filters
//     function filterProductsServices() {
//         const type = typeFilter.value;
//         const stock = stockFilter.value;
//         const min = parseFloat(minPrice.value) || 0;
//         const max = parseFloat(maxPrice.value) || Infinity;

//         const filteredList = productServiceList.filter(product => {
//             const isTypeMatch = type === 'all' || product.type.toLowerCase() === type.toLowerCase();
//             const isStockMatch = stock === 'all' || (stock === 'inStock' && product.stock > 0) || (stock === 'outOfStock' && product.stock === 0);
//             const isPriceInRange = product.price >= min && product.price <= max;

//             return isTypeMatch && isStockMatch && isPriceInRange;
//         });

//         displayProductsServices(filteredList);
//     }

//     // filter button click event to trigger filtering
//     filterBtn.addEventListener('click', filterProductsServices);

//     // function to add new product or service
//     addProductServiceBtn.addEventListener('click', () => {
//         document.getElementById('modalTitle').textContent = 'ADD NEW PRODUCT OR SERVICE';
//         modal.style.display = 'block';
//         productServiceForm.reset();
//     });

//     // function to save new product or service (add/edit)
//     productServiceForm.addEventListener('submit', (e) => {
//         e.preventDefault();
//         const id = productServiceList.length + 1;
//         const name = document.getElementById('productName').value;
//         const type = document.getElementById('productType').value;
//         const price = parseFloat(document.getElementById('productPrice').value);
//         const stock = document.getElementById('productStock').value;
//         const description = document.getElementById('productDescription').value;
//         const image = document.getElementById('productImage').files[0] ? document.getElementById('productImage').files[0].name : 'default.jpg';

//         // add new product/service
//         productServiceList.push({ id: id.toString(), name, type, price, stock, status: 'Active', description, image });
//         displayProductsServices();
//         modal.style.display = 'none';
//     });

//     // function to edit a product or service
//     function editProductService(id) {
//         const product = productServiceList.find(p => p.id === id);
//         document.getElementById('modalTitle').textContent = 'EDIT PRODUCT OR SERVICE';
//         document.getElementById('productName').value = product.name;
//         document.getElementById('productType').value = product.type;
//         document.getElementById('productPrice').value = product.price;
//         document.getElementById('productStock').value = product.stock;
//         document.getElementById('productDescription').value = product.description;
//         modal.style.display = 'block';

//         // remove the product and re-add with new details on form submission
//         productServiceForm.onsubmit = function (e) {
//             e.preventDefault();
//             product.name = document.getElementById('productName').value;
//             product.type = document.getElementById('productType').value;
//             product.price = parseFloat(document.getElementById('productPrice').value);
//             product.stock = document.getElementById('productStock').value;
//             product.description = document.getElementById('productDescription').value;

//             displayProductsServices();
//             modal.style.display = 'none';
//         };
//     }

//     // function to delete a product or service with confirmation
//     function deleteProductService(id) {
//         const product = productServiceList.find(p => p.id === id); 

//         if (product) {
//             const confirmation = confirm(`Are you sure you want to delete this product/service: "${product.name}"?`);

//             if (confirmation) {
//                 productServiceList = productServiceList.filter(p => p.id !== id);

             
//                 displayProductsServices();
            
//                 alert(`Product/Service "${product.name}" has been deleted.`);
//             }
//         }
//     }

//     // Cancel Button
//     cancelBtn.addEventListener('click', () => {
//         modal.style.display = 'none';
//     });

//     // Close Edit Modal
//     closeEditAddModal.addEventListener("click", function () {
//         modal.style.display = "none";
//     });

//     // Initialize the display of products and services
//     displayProductsServices();
// });


/*--------------------------------------------------------------------------------sales management-----------------------------------------------------------------------*/
// const toggleSalesBtn = document.getElementById("toggle-sales-btn");
// const onlineSalesSection = document.getElementById("online-sales");
// const physicalSalesSection = document.getElementById("physical-sales");
// const editModal = document.getElementById("edit-modal");
// const closeAddModalBtn = document.getElementById("close-add-modal");
// const closeEditModal = document.getElementById("close-edit-modal");
// const editForm = document.getElementById("edit-form");
// const addPhysicalOrderBtn = document.getElementById("add-physical-order-btn");
// const addPhysicalOrderModal = document.getElementById("add-physical-order-modal");
// const cancelAddOrderBtn = document.getElementById("cancel-add-order-btn");
// const cancelEditOrderBtn = document.getElementById("cancel-edit-order-btn");
// const confirmDeleteBtn = document.getElementById("confirm-delete-btn");
// const cancelDeleteBtn = document.getElementById("cancel-delete-btn");

// const onlineSalesData = [
//     { id: 1, customerId: "C001", name: "John Doe", product: "Laptop", orderDate: "2025-01-15", paymentMethod: "cash", paymentStatus: "pending", status: "pending" },
//     { id: 2, customerId: "C002", name: "Jane Smith", product: "Phone", orderDate: "2025-01-14", paymentMethod: "paid-gcash", paymentStatus: "paid", status: "ready" },
//     { id: 3, customerId: "C003", name: "Mark Johnson", product: "Headphones", orderDate: "2025-01-13", paymentMethod: "cash", paymentStatus: "pending", status: "picked-up" },
//     { id: 4, customerId: "C004", name: "Emily Davis", product: "Tablet", orderDate: "2025-01-12", paymentMethod: "paid-cash", paymentStatus: "paid", status: "received" },
//     { id: 5, customerId: "C005", name: "Sophia White", product: "Smartwatch", orderDate: "2025-01-11", paymentMethod: "paid-cash", paymentStatus: "paid", status: "completed" },
//     { id: 6, customerId: "C006", name: "Liam Green", product: "Bluetooth Speaker", orderDate: "2025-01-10", paymentMethod: "paid-gcash", paymentStatus: "paid", status: "ready" },
//     { id: 7, customerId: "C007", name: "Olivia Brown", product: "Keyboard", orderDate: "2025-01-09", paymentMethod: "cash", paymentStatus: "pending", status: "pending" },
//     { id: 8, customerId: "C008", name: "Lucas Black", product: "Monitor", orderDate: "2025-01-08", paymentMethod: "paid-gcash", paymentStatus: "paid", status: "picked-up" },
//     { id: 9, customerId: "C009", name: "Amelia White", product: "Phone Case", orderDate: "2025-01-07", paymentMethod: "cash", paymentStatus: "pending", status: "received" },
//     { id: 10, customerId: "C010", name: "Benjamin Green", product: "Wireless Charger", orderDate: "2025-01-06", paymentMethod: "paid-cash", paymentStatus: "paid", status: "completed" },
//     { id: 11, customerId: "C011", name: "Emma Wilson", product: "Laptop Bag", orderDate: "2025-01-05", paymentMethod: "paid-gcash", paymentStatus: "paid", status: "received" },
//     { id: 12, customerId: "C012", name: "Jacob Lee", product: "Mouse", orderDate: "2025-01-04", paymentMethod: "cash", paymentStatus: "pending", status: "pending" },
//     { id: 13, customerId: "C013", name: "Ava Martinez", product: "Webcam", orderDate: "2025-01-03", paymentMethod: "paid-cash", paymentStatus: "paid", status: "completed" },
//     { id: 14, customerId: "C014", name: "Ethan Harris", product: "Router", orderDate: "2025-01-02", paymentMethod: "paid-gcash", paymentStatus: "paid", status: "ready" },
//     { id: 15, customerId: "C015", name: "Isabella Clark", product: "Gaming Chair", orderDate: "2025-01-01", paymentMethod: "cash", paymentStatus: "pending", status: "pending" },
//     { id: 16, customerId: "C016", name: "Mason Young", product: "Keyboard", orderDate: "2024-12-31", paymentMethod: "paid-cash", paymentStatus: "paid", status: "picked-up" },
//     { id: 17, customerId: "C017", name: "Lily Lee", product: "Monitor Stand", orderDate: "2024-12-30", paymentMethod: "paid-gcash", paymentStatus: "paid", status: "received" },
//     { id: 18, customerId: "C018", name: "James Walker", product: "Smartphone", orderDate: "2024-12-29", paymentMethod: "cash", paymentStatus: "pending", status: "pending" },
//     { id: 19, customerId: "C019", name: "Charlotte King", product: "Camera", orderDate: "2024-12-28", paymentMethod: "paid-cash", paymentStatus: "paid", status: "ready" },
//     { id: 20, customerId: "C020", name: "Lucas Scott", product: "USB-C Cable", orderDate: "2024-12-27", paymentMethod: "paid-gcash", paymentStatus: "paid", status: "completed" }
// ];

// const physicalSalesData = [
//     { id: 1, customerId: "C005", name: "Sophia Green", product: "Shirt", orderDate: "2025-01-10", paymentMethod: "cash", paymentStatus: "pending", status: "pending" },
//     { id: 2, customerId: "C006", name: "Jack Black", product: "Jeans", orderDate: "2025-01-09", paymentMethod: "paid-gcash", paymentStatus: "paid", status: "ready" },
//     { id: 3, customerId: "C007", name: "Ava Blue", product: "Jacket", orderDate: "2025-01-08", paymentMethod: "cash", paymentStatus: "paid", status: "picked-up" },
//     { id: 4, customerId: "C008", name: "Liam Gray", product: "Sneakers", orderDate: "2025-01-07", paymentMethod: "paid-cash", paymentStatus: "pending", status: "received" },
//     { id: 5, customerId: "C009", name: "Zoe White", product: "Sweater", orderDate: "2025-01-06", paymentMethod: "paid-gcash", paymentStatus: "paid", status: "completed" },
//     { id: 6, customerId: "C010", name: "Ethan Black", product: "Cap", orderDate: "2025-01-05", paymentMethod: "cash", paymentStatus: "pending", status: "pending" },
//     { id: 7, customerId: "C011", name: "Emma Green", product: "Dress", orderDate: "2025-01-04", paymentMethod: "paid-gcash", paymentStatus: "paid", status: "ready" },
//     { id: 8, customerId: "C012", name: "Oliver Brown", product: "Belt", orderDate: "2025-01-03", paymentMethod: "paid-cash", paymentStatus: "paid", status: "picked-up" },
//     { id: 9, customerId: "C013", name: "Isabella White", product: "Pants", orderDate: "2025-01-02", paymentMethod: "cash", paymentStatus: "pending", status: "received" },
//     { id: 10, customerId: "C014", name: "Mason Blue", product: "T-shirt", orderDate: "2025-01-01", paymentMethod: "paid-gcash", paymentStatus: "paid", status: "completed" }
// ];

// // toggle for online and physical sales
// toggleSalesBtn.addEventListener("click", function () {
//     const isOnlineVisible = onlineSalesSection.style.display !== "none";

//     onlineSalesSection.style.display = isOnlineVisible ? "none" : "block";
//     physicalSalesSection.style.display = isOnlineVisible ? "block" : "none";

//     toggleSalesBtn.textContent = isOnlineVisible
//         ? "SWITCH TO ONLINE SALES"
//         : "SWITCH TO PHYSICAL SALES";
// });

// // filter function for sales tables
// function applyFilter() {
//     const onlineStatusFilter = document.getElementById("online-status-filter").value;
//     const onlinePaymentMethodFilter = document.getElementById("online-payment-method-filter").value;
//     const onlinePaymentStatusFilter = document.getElementById("online-payment-status-filter").value;

//     const physicalStatusFilter = document.getElementById("physical-status-filter").value;
//     const physicalPaymentMethodFilter = document.getElementById("physical-payment-method-filter").value;
//     const physicalPaymentStatusFilter = document.getElementById("physical-payment-status-filter").value;

//     // online sales filter
//     const filteredOnlineSales = onlineSalesData.filter((order) => {
//         return (onlineStatusFilter ? order.status === onlineStatusFilter : true) &&
//                (onlinePaymentMethodFilter ? order.paymentMethod === onlinePaymentMethodFilter : true) &&
//                (onlinePaymentStatusFilter ? order.paymentStatus === onlinePaymentStatusFilter : true);
//     });

//     // physical sales filter
//     const filteredPhysicalSales = physicalSalesData.filter((order) => {
//         return (physicalStatusFilter ? order.status === physicalStatusFilter : true) &&
//                (physicalPaymentMethodFilter ? order.paymentMethod === physicalPaymentMethodFilter : true) &&
//                (physicalPaymentStatusFilter ? order.paymentStatus === physicalPaymentStatusFilter : true);
//     });

//     populateOnlineSalesTable(filteredOnlineSales);
//     populatePhysicalSalesTable(filteredPhysicalSales);
// }

// document.getElementById("online-apply-filters-btn").addEventListener("click", applyFilter);
// document.getElementById("physical-apply-filters-btn").addEventListener("click", applyFilter);

// // populate online sales
// function populateOnlineSalesTable(data = onlineSalesData) {
//     const tbody = document.querySelector("#online-sales-table tbody");
//     tbody.innerHTML = ""; 

//     data.forEach((order) => {
//         const row = document.createElement("tr");
//         row.innerHTML = `
//             <td>${order.id}</td>
//             <td>${order.customerId}</td>
//             <td>${order.name}</td>
//             <td>${order.product}</td>
//             <td>${order.orderDate}</td>
//             <td>${order.paymentMethod}</td>
//             <td>${order.paymentStatus}</td>
//             <td>${order.status}</td>
//             <td>
//                 <button class="editBtn" data-id="${order.id}">Edit</button>
//                 <button class="deleteBtn" data-id="${order.id}">Delete</button>
//             </td>
//         `;
//         tbody.appendChild(row);
//     });
//     addDeleteEventListeners(); 
// }

// // populate physical sales
// function populatePhysicalSalesTable(data = physicalSalesData) {
//     const tbody = document.querySelector("#physical-sales-table tbody");
//     tbody.innerHTML = ""; 

//     data.forEach((order) => {
//         const row = document.createElement("tr");
//         row.innerHTML = `
//             <td>${order.id}</td>
//             <td>${order.customerId}</td>
//             <td>${order.name}</td>
//             <td>${order.product}</td>
//             <td>${order.orderDate}</td>
//             <td>${order.paymentMethod}</td>
//             <td>${order.paymentStatus}</td>
//             <td>${order.status}</td>
//             <td>
//                 <button class="editBtn" data-id="${order.id}">Edit</button>
//                 <button class="deleteBtn" data-id="${order.id}">Delete</button>
//             </td>
//         `;
//         tbody.appendChild(row);
//     });
//     addDeleteEventListeners();
// }

// // function to delete an order with confirmation
// document.addEventListener('DOMContentLoaded', function() {

//     function deleteOrder(event, tableType) {
//         const row = event.target.closest('tr'); 
//         const orderId = row.querySelector('td:nth-child(1)').textContent; 

//         const confirmation = confirm(`Are you sure you want to delete Order ID ${orderId}?`);
        
//         if (confirmation) {
//             row.remove();
//             alert(`Order ID ${orderId} has been deleted.`);
//         }
//     }

//     const onlineDeleteButtons = document.querySelectorAll('#online-sales-table .deleteBtn');
//     onlineDeleteButtons.forEach(button => {
//         button.addEventListener('click', function(event) {
//             deleteOrder(event, 'online');
//         });
//     });

//     const physicalDeleteButtons = document.querySelectorAll('#physical-sales-table .deleteBtn');
//     physicalDeleteButtons.forEach(button => {
//         button.addEventListener('click', function(event) {
//             deleteOrder(event, 'physical');
//         });
//     });
// });


// // function to add delete event listeners to buttons
// function addDeleteEventListeners() {
//     const deleteBtns = document.querySelectorAll('.deleteBtn');
//     deleteBtns.forEach(btn => {
//         btn.addEventListener('click', function () {
//             const id = parseInt(this.getAttribute('data-id'));
//             deleteSale(id); // Call deleteSale function with ID
//         });
//     });
// }

// // for editing the order
// let currentEditOrder = null;
// document.addEventListener("click", function (e) {
//     if (e.target && e.target.classList.contains("editBtn")) {
//         const orderId = e.target.dataset.id;  
//         const dataArray = onlineSalesSection.style.display !== "none" ? onlineSalesData : physicalSalesData;

//         currentEditOrder = dataArray.find((order) => order.id === parseInt(orderId));
//         if (currentEditOrder) {
//             document.getElementById("edit-payment-method").value = currentEditOrder.paymentMethod;
//             document.getElementById("edit-payment-status").value = currentEditOrder.paymentStatus;
//             document.getElementById("edit-status").value = currentEditOrder.status;
//             editModal.style.display = "block";
//         }
//     }
// });

// // save edit changes
// editForm.addEventListener("submit", function (e) {
//     e.preventDefault();
//     if (currentEditOrder) {
//         currentEditOrder.paymentMethod = document.getElementById("edit-payment-method").value;
//         currentEditOrder.paymentStatus = document.getElementById("edit-payment-status").value;
//         currentEditOrder.status = document.getElementById("edit-status").value;

//         if (onlineSalesSection.style.display !== "none") {
//             populateOnlineSalesTable();
//         } else {
//             populatePhysicalSalesTable();
//         }

//         editModal.style.display = "none";
//         currentEditOrder = null;
//     }
// });

// // for read only orderid and customerid
// addPhysicalOrderBtn.addEventListener("click", function () {
//     addPhysicalOrderModal.style.display = "block";
//     const newOrderId = physicalSalesData.length + 1;
//     document.getElementById("physical-order-id").value = "ORD-" + newOrderId;

//     const newCustomerId = "C0" + (physicalSalesData.length + 1);
//     document.getElementById("physical-customer-id").value = newCustomerId;
// });

// const availableProducts = [
//     "Zic M9 1L",
//     "JVT Gear Oil",
//     "Speed Tuner Super Oil",
//     "Rs8 Coolant 1L",
//     "HiRC Tire Sealant"
// ];

// creating and styling the add products/services dropdown
function createProductDropdown() {
    const productListContainer = document.getElementById("product-list-container");

    const listItem = document.createElement("li");

    const selectElement = document.createElement("select");
    const defaultOption = document.createElement("option");
    defaultOption.value = "";
    defaultOption.textContent = "Select a Product/Service";
    selectElement.appendChild(defaultOption);

    availableProducts.forEach(product => {
        const option = document.createElement("option");
        option.value = product;
        option.textContent = product;
        selectElement.appendChild(option);
    });

    selectElement.style.width = "75%";
    selectElement.style.padding = "5px";
    selectElement.style.fontSize = "14px";
    selectElement.style.border = "1px solid #ccc";
    selectElement.style.borderRadius = "3px";

    const removeButton = document.createElement("button");
    removeButton.textContent = "Remove";
    removeButton.style.marginLeft = "10px";
    removeButton.style.backgroundColor = "#f44336";
    removeButton.style.color = "white";
    removeButton.style.borderRadius = "5px";
    removeButton.style.cursor = "pointer";
    removeButton.style.padding = "5px";
    removeButton.style.width = "20%";
    
    removeButton.addEventListener("mouseover", function () {
        removeButton.style.backgroundColor = "#d32f2f";
    });
    removeButton.addEventListener("mouseout", function () {
        removeButton.style.backgroundColor = "#f44336"; 
    });

    removeButton.addEventListener("click", function () {
        listItem.remove();
    });

    listItem.appendChild(selectElement);
    listItem.appendChild(removeButton);

    productListContainer.appendChild(listItem);
}

// for adding products (dropdown)
document.getElementById("add-product").addEventListener("click", function () {
    createProductDropdown();
});

// for adding new physical order
document.getElementById("add-physical-form").addEventListener("submit", function (e) {
    e.preventDefault();

    const selectedProducts = [];
    const productSelectElements = document.querySelectorAll("#product-list-container select");

    productSelectElements.forEach(select => {
        const selectedProduct = select.value;
        if (selectedProduct) {
            selectedProducts.push(selectedProduct);
        }
    });

    const newOrder = {
        id: physicalSalesData.length + 1,
        customerId: document.getElementById("physical-customer-id").value,
        name: document.getElementById("physical-customer-name").value,
        products: selectedProducts,
        orderDate: document.getElementById("physical-order-date").value,
        paymentMethod: document.getElementById("physical-payment").value,
        paymentStatus: document.getElementById("physical-payment-status").value,
        status: document.getElementById("physical-status").value,
    };

    physicalSalesData.push(newOrder);
    populatePhysicalSalesTable();
    addPhysicalOrderModal.style.display = "none";
});

// initialize table
// populateOnlineSalesTable();
// populatePhysicalSalesTable();

// close add modal
closeAddModalBtn.addEventListener("click", function () {
    addPhysicalOrderModal.style.display = "none";
});

// close edit modal
closeEditModal.addEventListener("click", function () {
    editModal.style.display = "none";
});

// cancel add order 
cancelAddOrderBtn.addEventListener('click', () => {
    addPhysicalOrderModal.style.display = 'none';
});

// cancel edit order 
cancelEditOrderBtn.addEventListener('click', () => {
    editModal.style.display = 'none';
});

// show online sales by default
onlineSalesSection.style.display = "block";
physicalSalesSection.style.display = "none";
toggleSalesBtn.textContent = "Switch to Physical Sales";


/*-----------------------------------------------------------------------------------------------------------report and analytics---------------------------------------------------------------*/
let salesChart, earningsChart, salesComparisonChart, payBreakdownChart, orderStatusChart;

function initializeCharts() {
    // sales chart
    const salesCtx = document.getElementById("salesChart").getContext("2d");
    salesChart = new Chart(salesCtx, {
        type: "bar",
        data: { labels: ["Day 1", "Day 2", "Day 3", "Day 4", "Day 5", "Day 6", "Day 7"], datasets: [{ label: "Sales", data: [100, 200, 150, 140, 160, 170, 150], backgroundColor: "blue" }] },
    });

    // earnings chart
    const earningsCtx = document.getElementById("earningsChart").getContext("2d");
    earningsChart = new Chart(earningsCtx, {
        type: "line",
        data: {
            labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
            datasets: [{ label: "Earnings", data: [1200, 1500, 1100, 1800, 2000, 1700, 1600, 1900, 2100, 2300, 2200, 2500], borderColor: "green", fill: false }],
        },
    });

    // sales comparison
    const comparisonCtx = document.getElementById("salesComparisonChart").getContext("2d");
    salesComparisonChart = new Chart(comparisonCtx, {
        type: "bar",
        data: {
            labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
            datasets: [
                { label: "Online Sales", data: [300, 400, 350], backgroundColor: "orange" },
                { label: "Physical Sales", data: [200, 300, 250], backgroundColor: "purple" },
            ],
        },
    });

    // payment breakdown
    const payBreakdownCtx = document.getElementById("payBreakdownChart").getContext("2d");
    payBreakdownChart = new Chart(payBreakdownCtx, {
        type: "pie",
        data: {
            labels: ["GCash", "Cash"],
            datasets: [
                {
                    label: "Payments",
                    data: [5000, 3000],
                    backgroundColor: ["cyan", "orange"],
                    hoverOffset: 4,
                },
            ],
        },
        options: {
            plugins: {
                legend: {
                    position: "top",
                },
            },
            aspectRatio: 1.8, 
        },
    });

    // order status
    const orderStatusCtx = document.getElementById("orderStatusChart").getContext("2d");
    orderStatusChart = new Chart(orderStatusCtx, {
        type: "doughnut",
        data: {
            labels: ["Pending", "Ready for Pickup", "Picked Up", "Received", "Completed"],
            datasets: [
                {
                    label: "Orders",
                    data: [30, 20, 10, 15, 25],
                    backgroundColor: ["red", "blue", "green", "purple", "orange"],
                },
            ],
        },
        options: {
            plugins: {
                legend: {
                    display: true, 
                },
            },
            cutout: '50%',
            maintainAspectRatio: false, 
        },
    });

}

// for sales report
function updateSalesChart() {
    const chartType = document.getElementById("chartType").value;
    let labels, data;
    if (chartType === "daily") {
        labels = ["Day 1", "Day 2", "Day 3", "Day 4", "Day 5", "Day 6", "Day 7"];
        data = [100, 200, 150];
    } else if (chartType === "weekly") {
        labels = ["Week 1", "Week 2", "Week 3", "Week 4"];
        data = [500, 600, 550, 700];
    } else if (chartType === "monthly") {
        labels = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
        data = [1200, 1500, 1100, 1500, 1300, 1345, 1254, 1234, 1234, 1289, 1554, 2222];
    }
    salesChart.data.labels = labels;
    salesChart.data.datasets[0].data = data;
    salesChart.update();
}


const topSellingData = [
    { product: "Product 1", service: "Service A", quantitySold: 150 },
    { product: "Product 2", service: "Service B", quantitySold: 120 },
    { product: "Product 3", service: "Service C", quantitySold: 100 },
    { product: "Product 4", service: "Service D", quantitySold: 90 },
    { product: "Product 5", service: "Service E", quantitySold: 80 },
];

// initialize the charts
window.onload = function () {
    initializeCharts();
    populateTopSellingTable();
};


/*-----------------------------------------------------------------------------------------settings-----------------------------------------------------------------------------*/
const navLinks = document.querySelectorAll('#settings-nav a');
const sections = document.querySelectorAll('.section-content');

// initially hide all sections
sections.forEach(section => {
    section.style.display = 'none';
});

// set default section
const defaultSectionId = 'user-management'; 
const defaultSection = document.getElementById(defaultSectionId);

if (defaultSection) {
    defaultSection.style.display = 'block'; // Show the default section
}

// set the corresponding navigation link as active
navLinks.forEach(link => {
    const targetSectionId = link.getAttribute('data-section');
    if (targetSectionId === defaultSectionId) {
        link.classList.add('active');
    }
});

// add click event listener to each navigation link
navLinks.forEach(link => {
    link.addEventListener('click', function (event) {
        event.preventDefault(); 
 
        const targetSectionId = this.getAttribute('data-section');

        sections.forEach(section => {
            section.style.display = 'none';
        });
        
        const targetSection = document.getElementById(targetSectionId);
        if (targetSection) {
            targetSection.style.display = 'block';
        }
       
        navLinks.forEach(nav => nav.classList.remove('active'));

        this.classList.add('active');
    });
});


/*-------------------------------------------------------------------------------------------sign out------------------------------------------------------------------------------*/
function confirmSignOut() {
    const isConfirmed = confirm("Are you sure you want to sign out?");
    if (isConfirmed) {
        // connect here
        alert("You have signed out.");
    } else {
        alert("Sign out cancelled.");
    }
}


