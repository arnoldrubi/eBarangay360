  // 🔧 Define the reusable function FIRST
function setupAddressChain(provinceId, cityId, barangayId) {
const prov = document.getElementById(provinceId);
const city = document.getElementById(cityId);
const barangay = document.getElementById(barangayId);

prov.addEventListener('change', function () {
    fetch(`api/get-cities.php?province_id=${this.value}`)
    .then(res => res.json())
    .then(data => {
        city.innerHTML = '<option>Select City</option>';
        barangay.innerHTML = '<option>Select Barangay</option>';
        data.forEach(item => {
        city.innerHTML += `<option value=\"${item.city_municipal_id}\">${item.name}</option>`;
        });
    });
});

city.addEventListener('change', function () {
    fetch(`api/get-barangays.php?city_municipal_id=${this.value}`)
    .then(res => res.json())
    .then(data => {
        barangay.innerHTML = '<option>Select Barangay</option>';
        data.forEach(item => {
        barangay.innerHTML += `<option value=\"${item.barangay_id}\">${item.name}</option>`;
        });
    });
});
}

function setupForEditAddressChain(provinceId, cityId, barangayId, provSelector, citySelector, brgySelector) {
  // Load cities
  fetch(`api/get-cities.php?province_id=${provinceId}`)
    .then(res => res.json())
    .then(cities => {
      citySelector.innerHTML = '<option value="">Select City/Municipality</option>';
      cities.forEach(c => {
        const opt = document.createElement('option');
        opt.value = c.city_municipal_id;
        opt.textContent = c.name;
        if (c.city_municipal_id == cityId) opt.selected = true;
        citySelector.appendChild(opt);
      });

      // Now fetch barangays based on selected city
      return fetch(`api/get-barangays.php?city_municipal_id=${cityId}`);
    })
    .then(res => res.json())
    .then(barangays => {
      brgySelector.innerHTML = '<option value="">Select Barangay</option>';
      barangays.forEach(b => {
        const opt = document.createElement('option');
        opt.value = b.barangay_id;
        opt.textContent = b.name;
        if (b.barangay_id == barangayId) opt.selected = true;
        brgySelector.appendChild(opt);
      });
    });
}
