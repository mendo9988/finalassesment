const searchBox = document.getElementById("searchBox");
const suggestionsDiv = document.getElementById("suggestions");

searchBox.addEventListener("keyup", function () {
    const query = this.value.trim();

    fetch(`../public/autocomplete.php?q=${encodeURIComponent(query)}`)
        .then(response => response.json())
        .then(data => {
            suggestionsDiv.innerHTML = "";

            data.forEach(item => {
                const div = document.createElement("div");
                div.classList.add("suggestion-item");
                div.textContent = `${item.subject} `;

                div.onclick = () => {
                    searchBox.value = item.subject;
                    suggestionsDiv.innerHTML = "";
                };

                suggestionsDiv.appendChild(div);
            });
        })
        // .catch(err => console.error(err));
});


function updateStatus(ticketId, status) {
    fetch('../public/update_status.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ ticket_id: ticketId, status: status })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            console.log('Status updated');
        } else {
            alert(data.message || 'Status update failed');
        }
    })
    .catch(err => console.error(err));
}
