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

    // updateServicesPaginationControls();
    $("html, body").animate({ scrollTop: $("#services-list").offset().top - 150 }, "fast");
}

function setupServicesPagination() {
  
    const totalPages = Math.ceil(services.length / servicesPerPage);
    console.log("Total Services:", services.length, "Total Pages:", totalPages); // Debugging

    const paginationContainer = $("#pagination");
    paginationContainer.empty();

    // If there's only 1 page, show only the page number and nothing else
    if (totalPages === 1 || totalPages === 0) {
        paginationContainer.append(`<span class="page-link active" 
                style="background:#0e7fa0; color:white; padding:8px 15px; border-radius:5px; font-weight:bold;">
                1
            </span>`);

        return;
    }

    // Previous button (only if there's more than 1 page and not on page 1)
    if (servicesCurrentPage > 1) {
        paginationContainer.append(`<a href="#" class="prev" data-page="${servicesCurrentPage - 1}">«</a>`);
    }

    // Page number links
    for (let i = 1; i <= totalPages; i++) {
        let activeClass = i === servicesCurrentPage ? "active" : "";
        paginationContainer.append(`<a href="#" class="page-link ${activeClass}" data-page="${i}">${i}</a>`);
    }

    // Next button (only if there's more than 1 page and not on the last page)
    if (servicesCurrentPage < totalPages) {
        paginationContainer.append(`<a href="#" class="next" data-page="${servicesCurrentPage + 1}">»</a>`);
    }

    // Add click functionality for page numbers and arrows
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
}



// Initialize
$(document).ready(function () {
    fetchServices();
});
