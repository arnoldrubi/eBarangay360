// Same as present address functionality
// This will copy the present address to the permanent address fields
function sameAsPresentAddressChain(sameAsPresent,permanentBlock,presentProvince, presentCity, presentBarangay,presentStreet,presentZone,presentLandmark,permanentProvince, permanentCity, permanentBarangay,permanentStreet,permanentZone,permanentLandmark) {
  sameAsPresent.addEventListener('change', function () {
    if (this.checked) {
      // Step 1: Copy province
      permanentProvince.value = presentProvince.value;

      // Step 2: Trigger loading of permanent cities
      fetch(`api/get-cities.php?province_id=${presentProvince.value}`)
        .then(res => res.json())
        .then(cities => {
          permanentCity.innerHTML = '<option value="">Select City</option>';
          cities.forEach(city => {
            const opt = document.createElement('option');
            opt.value = city.city_municipal_id;
            opt.text = city.name;
            if (city.city_municipal_id == presentCity.value) opt.selected = true;
            permanentCity.appendChild(opt);
          });

          // Step 3: After cities are loaded, fetch barangays
          return fetch(`api/get-barangays.php?city_municipal_id=${presentCity.value}`);
        })
        .then(res => res.json())
        .then(barangays => {
          permanentBarangay.innerHTML = '<option value="">Select Barangay</option>';
          barangays.forEach(b => {
            const opt = document.createElement('option');
            opt.value = b.barangay_id;
            opt.text = b.name;
            if (b.barangay_id == presentBarangay.value) opt.selected = true;
            permanentBarangay.appendChild(opt);
          });

          // Step 4: Copy text fields
          permanentStreet.value = presentStreet.value;
          permanentZone.value = presentZone.value;
          permanentLandmark.value = presentLandmark.value;

          // Hide permanent block
          permanentBlock.style.display = 'none';
        });
    } else {
      // Unchecked → Show block again
      permanentBlock.style.display = 'block';
    }
  });
}

// Function to set up address chain for edit form
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

  
  //reusable function for opening and capturing usig device camera
function startCamera(video, canvas, captureBtn, context) {

  captureBtn.addEventListener('click', () => {
      // Request camera access
  navigator.mediaDevices.getUserMedia({ video: true })
      .then(stream => {
      video.srcObject = stream;
      })
      .catch(err => {
      alert("Unable to access the camera: " + err.message);
      });
    if (video.classList.contains('d-none')) {
      // If video is hidden, show it and reset canvas
      video.classList.remove('d-none');
      canvas.classList.add('d-none');
      captureBtn.innerHTML = '<i class="add-resident-subheading-icon material-symbols-outlined md-18 text-light">camera</i> Capture Photo';
      openCamera();
    } else {
      // If video is visible, capture the image
      context.drawImage(video, 0, 0, canvas.width, canvas.height);
      canvas.classList.remove('d-none');

      const imageDataURL = canvas.toDataURL('image/png');
      // assign to hidden field
      document.getElementById('captured_photo').value = imageDataURL;

      video.classList.add('d-none');
      captureBtn.textContent = 'Retake Photo';
    }
  });
}


  $('.delete-btn').on('click', function (e) {
  e.preventDefault();

  const id = $(this).data('id');
  if (!confirm('Are you sure you want to delete this resident?')) return;

  $.post('../src/actions/delete-resident.php', { id: id }, function (response) {
    if (response === 'success') {
      alert('Resident deleted.');
      location.reload(); // Or remove row via JS
    } else {
      alert('Failed to delete resident: ' + response);
    }
  });
});


document.addEventListener('DOMContentLoaded', function () {

  // Call the function for both address chains
  setupAddressChain('birth_province', 'birth_city', 'birth_barangay');
  setupAddressChain('permanent_province', 'permanent_city', 'permanent_barangay');
  setupAddressChain('present_province', 'present_city', 'present_barangay');

  // Handle the "Same as Present Address" checkbox
  // This will copy the present address to the permanent address fields
  const sameAsPresent = document.getElementById('sameAsPresent');
  const permanentBlock = document.getElementById('permanentAddressBlock');

  const presentProvince = document.getElementById('present_province');
  const presentCity = document.getElementById('present_city');
  const presentBarangay = document.getElementById('present_barangay');

  const permanentProvince = document.getElementById('permanent_province');
  const permanentCity = document.getElementById('permanent_city');
  const permanentBarangay = document.getElementById('permanent_barangay');

  const presentStreet = document.getElementById('present_street');
  const presentZone = document.getElementById('present_zone');
  const presentLandmark = document.getElementById('present_landmark');

  const permanentStreet = document.getElementById('permanent_street');
  const permanentZone = document.getElementById('permanent_zone');
  const permanentLandmark = document.getElementById('permanent_landmark');

  sameAsPresentAddressChain(sameAsPresent, permanentBlock, presentProvince, presentCity, presentBarangay, presentStreet, presentZone, presentLandmark, permanentProvince, permanentCity, permanentBarangay, permanentStreet, permanentZone, permanentLandmark);

   // set to operate camera
  const video = document.getElementById('cameraPreview');
  const canvas = document.getElementById('snapshotCanvas');
  const captureBtn = document.getElementById('captureBtn');
  const context = canvas.getContext('2d');
  
  // Calculate age based on date of birth
  const birthdateInput = document.getElementById('birthdate');
  const ageInput = document.getElementById('age');

  birthdateInput.addEventListener('change', function () {
    const dob = new Date(this.value);
    const today = new Date();

    let age = today.getFullYear() - dob.getFullYear();
    const hasHadBirthdayThisYear =
      today.getMonth() > dob.getMonth() ||
      (today.getMonth() === dob.getMonth() && today.getDate() >= dob.getDate());

    if (!hasHadBirthdayThisYear) {
      age -= 1;
    }

    ageInput.value = age >= 0 ? age : '';
  });

  // Form validation from Bootstrap
  const form = document.querySelector('form');

  form.addEventListener('submit', function (e) {
    let isValid = true;

    // Clear previous errors
    form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));

    // Loop through all required fields
    const requiredFields = form.querySelectorAll('[required]');
    requiredFields.forEach(field => {
      const value = field.value.trim();

      // Special case for checkboxes/radios
      if ((field.type === 'checkbox' || field.type === 'radio') && !field.checked) {
        isValid = false;
        field.classList.add('is-invalid');
      } else if (value === '') {
        isValid = false;
        field.classList.add('is-invalid');
      }
    });

    if (!isValid) {
      e.preventDefault(); // Prevent submission
      alert('Please fill out all required fields.');
    }
  });
});

// Handle resident edit and delete functionality
$(document).ready(function () {

  // for data table
      $('#residents-table').DataTable();
        const table = $('#residents-table').DataTable();
        // Gender Filter
      // $('#genderFilter').on('change', function () {
      //   const gender = this.value;
      //   table.column(6).search(gender).draw(); // Gender column index
      // });

      // // Status Filter
      // $('#statusFilter').on('change', function () {
      //   const status = this.value;
      //   table.column(8).search(status).draw(); // Status column index (adjust as needed)
      // });

      // // Age Group Filter (custom range logic)
      // $('#ageFilter').on('change', function () {
      //   const selected = this.value;

      //   $.fn.dataTable.ext.search = $.fn.dataTable.ext.search.filter(f => f.name !== 'ageGroupFilter');

      //   if (selected) {
      //     $.fn.dataTable.ext.search.push(function ageGroupFilter(settings, data, dataIndex) {
      //       const age = parseInt(data[7]); // Age column index

      //       if (
      //         (selected === 'child' && age <= 17) ||
      //         (selected === 'adult' && age >= 18 && age <= 59) ||
      //         (selected === 'senior' && age >= 60)
      //       ) {
      //         return true;
      //       }

      //       return false;
      //     });
      //   }

      //   table.draw();
      // });
  // end data table
  // set to operate camera
  const video = document.getElementById('cameraPreview');
  const canvas = document.getElementById('snapshotCanvas');
  const captureBtn = document.getElementById('captureBtn');
  const context = canvas.getContext('2d');
  
  startCamera(video, canvas, captureBtn, context);

  $('.edit-btn').on('click', function () {
    const residentId = $(this).data('id');
    alert(residentId);
    // Fetch data via AJAX
    $.get('../public/api/get-resident.php', { id: residentId }, function (data) {
      const res = JSON.parse(data);

      const actualAge = new Date().getFullYear() - new Date(res.birthdate).getFullYear();

      const sameAsPresent = document.getElementById('sameAsPresent');
      const permanentBlock = document.getElementById('permanentAddressBlock');

      const birthProvince = document.getElementById('edit_birth_province');
      const birthCity = document.getElementById('edit_birth_city');
      const birthBarangay = document.getElementById('edit_birth_barangay');

      const presentProvince = document.getElementById('edit_present_province');
      const presentCity = document.getElementById('edit_present_city');
      const presentBarangay = document.getElementById('edit_present_barangay');

      const permanentProvince = document.getElementById('edit_permanent_province');
      const permanentCity = document.getElementById('edit_permanent_city');
      const permanentBarangay = document.getElementById('edit_permanent_barangay');

      const presentStreet = document.getElementById('edit_present_street');
      const presentZone = document.getElementById('edit_present_zone');
      const presentLandmark = document.getElementById('edit_present_landmark');

      const permanentStreet = document.getElementById('edit_permanent_street');
      const permanentZone = document.getElementById('edit_permanent_zone');
      const permanentLandmark = document.getElementById('edit_permanent_landmark');



      $('#edit_id').val(res.id);
      $('#edit_first_name').val(res.first_name);
      $('#edit_middle_name').val(res.middle_name);
      $('#edit_last_name').val(res.last_name);
      $('#edit_birth_province').val(res.place_of_birth_province);
      $('#edit_birth_city').val(res.place_of_birth_city_municipality);
      $('#edit_birth_barangay').val(res.birth_barangay);
      $('#edit_birthdate').val(res.date_of_birth );
      $('#edit_age').val(actualAge);
      $('#edit_present_province').val(res.present_province);
      $('#edit_present_city').val(res.present_city_municipality );
      $('#edit_present_barangay').val(res.present_barangay);
      $('#edit_present_street').val(res.present_street);
      $('#edit_present_zone').val(res.present_zone);
      $('#edit_present_landmark').val(res.present_landmark);
      $('#edit_permanent_province').val(res.permanent_province);
      $('#edit_permanent_city').val(res.permanent_city_municipality);
      $('#edit_permanent_barangay').val(res.permanent_barangay);
      $('#edit_permanent_street').val(res.permanent_street);
      $('#edit_permanent_zone').val(res.permanent_zone);
      $('#edit_permanent_landmark').val(res.permanent_landmark);
      $('#edit_email').val(res.email);
      $('#edit_gender').val(res.gender)
      $('#edit_civil_status').val(res.civil_status)
      $('#edit_phone_number').val(res.phone_number);
      $('#edit_occupation').val(res.occupation);
      $('#edit_valid_id_type').val(res.valid_id_type);
      $('#edit_valid_id_number').val(res.valid_id_number);
      $('#edit_alias_nickname').val(res.alias_nickname);
      document.getElementById("edit_aliveSwitch").checked = res.alive == 0 ? false : true;
      document.getElementById("edit_unemployed").checked = res.unemployed == 1 ? true : false;
      document.getElementById('resident-picture').src = res.photo_filename ? `../public/uploads/residents/${res.photo_filename}` : '../public/uploads/residents/default.png';
      
      // // for populating address chains
      setupForEditAddressChain(res.place_of_birth_province, res.place_of_birth_city_municipality, res.place_of_birth_barangay, birthProvince, birthCity, birthBarangay);
      setupForEditAddressChain(res.present_province, res.present_city_municipality, res.present_barangay, presentProvince, presentCity, presentBarangay);
      setupForEditAddressChain(res.permanent_province, res.permanent_city_municipality, res.permanent_barangay, permanentProvince, permanentCity, permanentBarangay);

    });
  });


    // Address chain setup for edit form
    // Call the function for both address chains
    setupAddressChain('edit_birth_province', 'edit_birth_city', 'edit_birth_barangay');
    setupAddressChain('edit_permanent_province', 'edit_permanent_city', 'edit_permanent_barangay');
    setupAddressChain('edit_present_province', 'edit_present_city', 'edit_present_barangay');

 });// End of document ready

