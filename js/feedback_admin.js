
let allSelected = false;


document.addEventListener('DOMContentLoaded', function() {
    updateActionButtons();
    
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = '0';
            setTimeout(() => {
                alert.style.display = 'none';
            }, 300);
        }, 3000);
    });
});


function toggleSelectAll() {
    const selectAllCheckbox = document.getElementById('selectAllCheckbox');
    const checkboxes = document.querySelectorAll('.feedback-checkbox');
    
    allSelected = !allSelected;
    
    if (allSelected) {
        selectAllCheckbox.classList.add('checked');
        checkboxes.forEach(checkbox => {
            checkbox.checked = true;
        });
    } else {
        selectAllCheckbox.classList.remove('checked');
        checkboxes.forEach(checkbox => {
            checkbox.checked = false;
        });
    }
    
    updateActionButtons();
}

function updateSelectAll() {
    const checkboxes = document.querySelectorAll('.feedback-checkbox');
    const checkedBoxes = document.querySelectorAll('.feedback-checkbox:checked');
    const selectAllCheckbox = document.getElementById('selectAllCheckbox');
    
    if (checkedBoxes.length === checkboxes.length && checkboxes.length > 0) {
        selectAllCheckbox.classList.add('checked');
        allSelected = true;
    } else {
        selectAllCheckbox.classList.remove('checked');
        allSelected = false;
    }
    
    updateActionButtons();
}

function selectFeedback(feedbackId) {
    const currentUrl = new URL(window.location);
    currentUrl.searchParams.set('selected', feedbackId);
    const filterValue = document.getElementById('feedback_filter').value;
    if (filterValue) {
        currentUrl.searchParams.set('type', filterValue);
    }
    
    window.location.href = currentUrl.toString();
}

function filterFeedback() {
    const filterValue = document.getElementById('feedback_filter').value;
    const currentUrl = new URL(window.location);
    
    if (filterValue) {
        currentUrl.searchParams.set('type', filterValue);
    } else {
        currentUrl.searchParams.delete('type');
    }
    
    currentUrl.searchParams.delete('selected');
    
    window.location.href = currentUrl.toString();
}

function updateActionButtons() {
    const checkedBoxes = document.querySelectorAll('.feedback-checkbox:checked');
    const buttons = document.querySelectorAll('.action-buttons .btn');
    
    buttons.forEach(button => {
        if (checkedBoxes.length > 0) {
            button.style.opacity = '1';
            button.style.pointerEvents = 'auto';
            button.disabled = false;
        } else {
            button.style.opacity = '0.5';
            button.style.pointerEvents = 'none';
            button.disabled = true;
        }
    });
}

function confirmAction(actionName) {
    const checkedBoxes = document.querySelectorAll('.feedback-checkbox:checked');
    
    if (checkedBoxes.length === 0) {
        alert(`Please select feedback items to ${actionName}.`);
        return false;
    }
    
    if (actionName === 'delete') {
        return confirm(`Are you sure you want to delete ${checkedBoxes.length} feedback item(s)? This action cannot be undone.`);
    }
    
    return confirm(`Are you sure you want to ${actionName} ${checkedBoxes.length} feedback item(s)?`);
}

window.addEventListener('load', function() {
    window.scrollTo(0, 0);
});