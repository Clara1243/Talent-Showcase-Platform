function toggleSelectAll() {
  const selectAllCheckbox = document.getElementById('selectAll');
  const checkboxes = document.querySelectorAll('.user-checkbox');

  checkboxes.forEach(checkbox => {
    checkbox.checked = selectAllCheckbox.checked;
  });

  updateActionButtons();
}

function toggleProductSelectAll() {
  const selectAllCheckbox = document.getElementById('ProductSelectAll');
  const checkboxes = document.querySelectorAll('.product-checkbox');

  checkboxes.forEach(checkbox => {
    checkbox.checked = selectAllCheckbox.checked;
  });

  updateActionButtons();
}

function confirmAction(action) {
    const checkedBoxes = document.querySelectorAll('.user-checkbox:checked');
    if (checkedBoxes.length === 0) {
        alert('Please select at least one user.');
        return false;
    }
    
    const message = `Are you sure you want to ${action} ${checkedBoxes.length} user(s)?`;
    return confirm(message);
}

function confirmProductAction(action) {
    const checkedBoxes = document.querySelectorAll('.product-checkbox:checked');
    if (checkedBoxes.length === 0) {
        alert('Please select at least one product.');
        return false;
    }
    
    const message = `Are you sure you want to ${action} ${checkedBoxes.length} product(s)?`;
    return confirm(message);
}

function performSearch() {
    const searchInput = document.getElementById('searchInput');
    const searchValue = searchInput.value.trim();
    const urlParams = new URLSearchParams(window.location.search);
    
    if (searchValue) {
        urlParams.set('search', searchValue);
    } else {
        urlParams.delete('search');
    }
    
    urlParams.set('page', '1');
    
    window.location.href = '?' + urlParams.toString();
}

document.getElementById('searchInput').addEventListener('input', function(e) {
    clearTimeout(this.timer);
    this.timer = setTimeout(() => {
        performSearch();
    }, 300);
});

document.getElementById('searchInput').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        e.preventDefault();
        clearTimeout(this.timer);
        performSearch();
    }
});

document.addEventListener('change', function(e) {
    if (e.target.classList.contains('user-checkbox')) {
        const allCheckboxes = document.querySelectorAll('.user-checkbox');
        const checkedCheckboxes = document.querySelectorAll('.user-checkbox:checked');
        const selectAll = document.getElementById('selectAll');
                
        selectAll.checked = allCheckboxes.length === checkedCheckboxes.length;
        selectAll.indeterminate = checkedCheckboxes.length > 0 && checkedCheckboxes.length < allCheckboxes.length;
    }
});

function performProductSearch() {
    const searchInput = document.getElementById('searchProductInput');
    const searchValue = searchInput.value.trim();
    const urlParams = new URLSearchParams(window.location.search);
    
    if (searchValue) {
        urlParams.set('searchProduct', searchValue);
    } else {
        urlParams.delete('searchProduct');
    }
    
    urlParams.set('productPage', '1');
    
    window.location.href = '?' + urlParams.toString();
}

document.getElementById('searchProductInput').addEventListener('input', function(e) {
    clearTimeout(this.timer);
    this.timer = setTimeout(() => {
        performProductSearch();
    }, 300);
});

document.getElementById('searchProductInput').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        e.preventDefault();
        clearTimeout(this.timer);
        performProductSearch();
    }
});

document.addEventListener('change', function(e) {
    if (e.target.classList.contains('product-checkbox')) {
        const allCheckboxes = document.querySelectorAll('.product-checkbox');
        const checkedCheckboxes = document.querySelectorAll('.product-checkbox:checked');
        const selectAll = document.getElementById('ProductSelectAll');
                
        selectAll.checked = allCheckboxes.length === checkedCheckboxes.length;
        selectAll.indeterminate = checkedCheckboxes.length > 0 && checkedCheckboxes.length < allCheckboxes.length;
    }
});