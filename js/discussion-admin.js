function toggleSelectAll() {
  const selectAllCheckbox = document.getElementById('selectAll');
  const checkboxes = document.querySelectorAll('.post-checkbox');

  checkboxes.forEach(checkbox => {
    checkbox.checked = selectAllCheckbox.checked;
  });

  updateActionButtons();
}

function confirmAction(action) {
    const checkedBoxes = document.querySelectorAll('.post-checkbox:checked');
    if (checkedBoxes.length === 0) {
        alert('Please select at least one post.');
        return false;
    }
    
    const message = `Are you sure you want to ${action} ${checkedBoxes.length} post(s)?`;
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
    if (e.target.classList.contains('post-checkbox')) {
        const allCheckboxes = document.querySelectorAll('.post-checkbox');
        const checkedCheckboxes = document.querySelectorAll('.post-checkbox:checked');
        const selectAll = document.getElementById('selectAll');
                
        selectAll.checked = allCheckboxes.length === checkedCheckboxes.length;
        selectAll.indeterminate = checkedCheckboxes.length > 0 && checkedCheckboxes.length < allCheckboxes.length;
    }
});