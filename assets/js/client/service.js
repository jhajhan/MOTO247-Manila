let services = [];
let servicesCurrentPage = 1;
const servicesPerPage = 6; // Adjust based on your layout

// Fetch Services from API
function fetchServices() {
    $.ajax({
        url: '/services',  // Adjust this to your correct PHP file location
        method: 'GET',
        dataType: 'json',
        success: function (data) {
            if (data && Array.isArray(data.services)) { 
                services = data.services;
                displayServices(servicesCurrentPage);
                setupServicesPagination();
            } else {
                console.error("Invalid response structure:", data);
            }
        },
        error: function (error) {
            console.error('Error fetching services:', error);
        }
    });
}

// Display Services with Pagination
function displayServices(page) {
    const servicesList = $("#services-list");
    servicesList.empty(); // Clear previous items

    let start = (page - 1) * servicesPerPage;
    let end = start + servicesPerPage;
    let paginatedServices = services.slice(start, end);

    if (paginatedServices.length === 0) {
        servicesList.html("<p>No services available.</p>");
        return;
    }

    paginatedServices.forEach(service => {
        let serviceHTML = `
            <div class="card">
                <div class="content">
                    <h3>${service.name}</h3>
                    <p>${service.description}</p>
                </div>
            </div>
        `;
        servicesList.append(serviceHTML);
    });

    updateServicesPaginationControls();
}

function setupServicesPagination() {
    const totalPages = Math.ceil(services.length / servicesPerPage);
    console.log("Total Services:", services.length, "Total Pages:", totalPages); // Debugging

    const paginationContainer = $("#pagination");
    paginationContainer.empty();

    // Previous button
    if (servicesCurrentPage > 1) {
        paginationContainer.append(`<a href="#" class="prev" data-page="${servicesCurrentPage - 1}"><ion-icon name="arrow-back"></ion-icon></a>`);
    } else {
        paginationContainer.append(`<a href="#" class="prev disabled" data-page="${servicesCurrentPage - 1}"><ion-icon name="arrow-back"></ion-icon></a>`);
    }

    // Page number links
    for (let i = 1; i <= totalPages; i++) {
        let activeClass = i === servicesCurrentPage ? "active" : "";
        paginationContainer.append(`<a href="#" class="page-link ${activeClass}" data-page="${i}">${i}</a>`);
    }

    // Next button
    if (servicesCurrentPage < totalPages) {
        paginationContainer.append(`<a href="#" class="next" data-page="${servicesCurrentPage + 1}"><ion-icon name="arrow-forward"></ion-icon></a>`);
    } else {
        paginationContainer.append(`<a href="#" class="next disabled" data-page="${servicesCurrentPage + 1}"><ion-icon name="arrow-forward"></ion-icon></a>`);
    }

    // Add click functionality for page number and arrows
    $(".page-link").click(function (e) {
        e.preventDefault();
        servicesCurrentPage = parseInt($(this).attr("data-page"));
        displayServices(servicesCurrentPage);
        setupServicesPagination(); // Re-render pagination
    });

    $(".prev, .next").click(function (e) {
        e.preventDefault();
        if (!$(this).hasClass("disabled")) {
            servicesCurrentPage = parseInt($(this).attr("data-page"));
            displayServices(servicesCurrentPage);
            setupServicesPagination(); // Re-render pagination
        }
    });

    updateServicesPaginationControls(); // Ensure the active page is updated
}

function updateServicesPaginationControls() {
    $(".page-link").removeClass("active");
    $(`.page-link[data-page='${servicesCurrentPage}']`).addClass("active");

    // Disable Previous button if on first page
    if (servicesCurrentPage === 1) {
        $(".prev").addClass("disabled").removeAttr("href");
    } else {
        $(".prev").removeClass("disabled").attr("href", "#");
    }

    // Disable Next button if on last page
    const totalPages = Math.ceil(services.length / servicesPerPage);
    if (servicesCurrentPage === totalPages) {
        $(".next").addClass("disabled").removeAttr("href");
    } else {
        $(".next").removeClass("disabled").attr("href", "#");
    }
}



// Initialize
$(document).ready(function () {
    fetchServices();
});
