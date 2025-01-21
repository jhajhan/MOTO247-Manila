fetch('/admin/sales')
    .then(response => response.json())
    .then(data => {})
    .catch(error => console.error(error));