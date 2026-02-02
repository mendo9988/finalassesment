const searchBox = document.getElementById("searchBox");
const suggestionsDiv = document.getElementById("suggestions");

// Debounce function to limit API calls
let debounceTimer;
function debounce(func, delay) {
    return function(...args) {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => func.apply(this, args), delay);
    };
}

// Autocomplete functionality
if (searchBox && suggestionsDiv) {
    searchBox.addEventListener("keyup", debounce(function () {
        const query = this.value.trim();

        // Clear suggestions if search is empty
        if (query.length === 0) {
            suggestionsDiv.innerHTML = "";
            suggestionsDiv.style.display = "none";
            return;
        }
        // Show loading state
        suggestionsDiv.innerHTML = '<div class="suggestion-item loading">Loading...</div>';
        suggestionsDiv.style.display = "block";

        fetch(`../public/autocomplete.php?q=${encodeURIComponent(query)}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                suggestionsDiv.innerHTML = "";
                data.forEach(item => {
                    const div = document.createElement("div");
                    div.classList.add("suggestion-item");
                    div.textContent = item.subject;

                    div.onclick = () => {
                        searchBox.value = item.subject;
                        suggestionsDiv.innerHTML = "";
                        suggestionsDiv.style.display = "none";
                    };

                    suggestionsDiv.appendChild(div);
                });
            })
            .catch(err => {
                console.error('Autocomplete error:', err);
                suggestionsDiv.innerHTML = '<div class="suggestion-item error">Error loading suggestions</div>';
            });
    }, 300)); // 300ms delay

    // Close suggestions when clicking outside
    document.addEventListener("click", function(event) {
        if (!searchBox.contains(event.target) && !suggestionsDiv.contains(event.target)) {
            suggestionsDiv.innerHTML = "";
            suggestionsDiv.style.display = "none";
        }
    });

    // Handle Enter key to submit form (close suggestions)
    searchBox.addEventListener("keydown", function(event) {
        if (event.key === "Enter") {
            suggestionsDiv.innerHTML = "";
            suggestionsDiv.style.display = "none";
        }
    });
}

// Update ticket status
function updateStatus(ticketId, status) {
    // Validate inputs
    if (!ticketId || !status) {
        alert('Invalid ticket ID or status');
        return;
    }

    // Store the select element to revert on error
    const selectElement = event.target;
    const previousValue = selectElement.getAttribute('data-previous-value') || selectElement.value;
    
    // Store current value as previous for next change
    selectElement.setAttribute('data-previous-value', status);

    fetch('../public/update_status.php', {
        method: 'POST',
        headers: { 
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ 
            ticket_id: parseInt(ticketId), 
            status: status 
        })
    })
    .then(res => {
        if (!res.ok) {
            throw new Error('Network response was not ok');
        }
        return res.json();
    })
    .then(data => {
        if (data.success) {
            console.log('Status updated successfully');
            // Optional: Show a success message
            // showNotification('Status updated successfully', 'success');
        } else {
            alert(data.message || 'Status update failed');
            // Revert to previous value on failure
            selectElement.value = previousValue;
        }
    })
    .catch(err => {
        // Revert to previous value on error
        selectElement.value = previousValue;
    });
}

// Optional: Initialize all status selects with their current value
document.addEventListener('DOMContentLoaded', function() {
    const statusSelects = document.querySelectorAll('select[onchange^="updateStatus"]');
    statusSelects.forEach(select => {
        select.setAttribute('data-previous-value', select.value);
    });
});