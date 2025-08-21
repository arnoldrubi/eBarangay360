function fillResidentNames(res,prefix) {    

    presentProvince = document.querySelector(`select[name="${prefix}_province"]`); 
    presentCity = document.querySelector(`select[name="${prefix}_city_municipality"]`);
    presentBarangay = document.querySelector(`select[name="${prefix}_barangay"]`);

    // Replace these with your actual input field IDs
    document.querySelector(`input[name="${prefix}_first_name"]`).value = res.first_name;
    document.querySelector(`input[name="${prefix}_middle_name"]`).value = res.middle_name;
    document.querySelector(`input[name="${prefix}_last_name"]`).value = res.last_name;

    // Optional: set hidden resident_id field
    document.querySelector(`input[name="resident_id"]`).value = res.id;
}

const residentSearchInput = document.querySelector('#residentSearch');
const suggestionsBox = document.querySelector('#residentSuggestions');


residentSearchInput.addEventListener('input', function () {
    console.log('Input changed:', this.value);
    const query = this.value.trim();
    if (query.length < 2) {
    suggestionsBox.innerHTML = '';
    return;
    }

    fetch(`api/search-residents.php?term=${encodeURIComponent(query)}`)
    .then(res => res.json())
    .then(data => {
        suggestionsBox.innerHTML = '';
        data.forEach(resident => {
        const item = document.createElement('button');
        item.type = 'button';
        item.className = 'list-group-item list-group-item-action';
        item.textContent = `${resident.last_name}, ${resident.first_name} ${resident.middle_name}`;
        item.addEventListener('click', () => {
            fillResidentNames(resident,'resident');
            suggestionsBox.innerHTML = '';
            residentSearchInput.value = item.textContent;
        });
        suggestionsBox.appendChild(item);
        });
    });
});
