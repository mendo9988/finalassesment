const ListDiv = document.getElementById('list');
const searchInput = document.getElementById('searchbar');

// Function to dynamically render list to the HTM
function renderList(toDisplay) {
    ListDiv.innerHTML = '';
    if(toDisplay.length === 0) {
        ListDiv.innerHTML = '<p>No data found matching your criteria.</p>';
        return;
    }

    toDisplay.forEach(list => {
        const listElement = document.createElement('div');
        listElement.classList.add('list-item');
        listElement.innerHTML = `
        <p><strong>${list.title}</strong> (${list.date}) - ${list.subject}</p>
        `
    })
}
searchInput.addEventListener('input', function() {
    const searchTerm = searchInput.value.toLowerCase();
    const filterList = allMovies.filter(list => {
        const subjectMatch = list.title.toLowerCase().includes(searchTerm);
        const descriptionMatch = movie.description.toLowerCase().includes(searchTerm);
        return subjectMatch || descriptionMatch;

});
renderList(filterList);
    });



// function search_animal() {
//     document.getElementById("searchbar").addEventListener("keyup", function () {
//         fetch("search_ajax.php?q=" + this.value)
//             .then(res => res.text())
//             .then(html => {
//                 document.getElementById("results").innerHTML = html;
//             });
//     });
//     let input = document.getElementById('searchbar').value ;
//     input = input.toLowerCase();
//     let x = document.getElementsByClassName('animals');
//     for (i = 0; i < x.length; i++) {
//         if (!x[i].innerHTML.toLowerCase().includes(input)) {
//             x[i].computedStyleMap.display = "none";
//         }
//         else {
//             x[i].computedStyleMap.display = "list_item";
//         }
//     }
// }