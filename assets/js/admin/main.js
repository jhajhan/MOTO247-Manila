$(document).ready(function() {
    checkRoute();
    $("nav a").click(function(e) {
        e.preventDefault();  // Prevent full page reload
        const newPath = $(this).attr("href");

        // Update the browser URL
        window.history.pushState({}, "", newPath);
        
        // Show the correct section
        checkRoute();
    });

    // Handle browser back/forward navigation
    window.onpopstate = checkRoute;


    function showSection(sectionId) {
            console.log('hello');
            $(".section").hide(); // Hide all sections
            $(sectionId).show();  // Show the selected section
        } 


    function checkRoute() {
     
            const path = window.location.pathname;
        
            if (path === "/admin/product-service") {
                
                showSection("#products-services");
                // fetchProducts();
            } else if (path === "/admin/reports-analytics") {
                showSection("#reports-analytics");
            } else if (path == "/admin/sales"){
                showSection("#sales-management")
            } else if(path == "/admin/settings"){
                showSection("#settings");
            }else {
                showSection("#dashboard"); // Default
            }
        }
});


