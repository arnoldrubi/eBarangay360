        <footer class="text-center mt-4">
          <p class="text-muted small">&copy; 2025 Copyright: Barangay Information Management System (eBarangay360)</p>
        </footer>
      </main>
    </div>
  </div>
      
  <!-- Bootstrap JS -->
  <!-- <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script> -->
  
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>

  <!-- Custom Scripts based on routes -->
  <script src="<?= BASE_URL ?>/js/common.js"></script>
  <?php if ($page === 'residents' || $page === 'add-resident') : ?>
  <script src="<?= BASE_URL ?>/js/residents.js"></script>
  <?php elseif ($page === 'add-new-blotter-report' || $page === 'blotter-reports' || $page === 'edit-blotter-report') : ?>
    <script src="<?= BASE_URL ?>js/blotter-reports.js"></script>
  <?php elseif ($page === 'households' || $page === 'add-household' || $page === 'add-household-members') : ?>
    <script src="<?= BASE_URL ?>js/households.js"></script>
  <?php elseif ($page === 'barangay-certificates' || $page === 'barangay-clearance' || $page === 'barangay-certificate-of-indigency') : ?>
  <script src="<?= BASE_URL ?>js/barangay-certificates.js"></script>
  <?php endif; ?>

  <?php if ($page === 'add-household-members' || $page === 'households') : ?>
    <script>
      document.addEventListener('DOMContentLoaded', function () {
      document.getElementById('addMore').addEventListener('click', function () {
      const container = document.getElementById('resident-dropdown-group');
      const clone = container.querySelector('.resident-dropdown').cloneNode(true);
      clone.querySelector('select').value = ''; // clear selection
      container.appendChild(clone);
    });
      document.addEventListener('click', function (e) {
      
        if (e.target.classList.contains('remove-member')) {
          const deleteButtons = document.querySelectorAll('.resident-dropdown');
          const group = e.target.closest('.resident-dropdown');
          if (deleteButtons.length > 1) {
            group.remove();
          }
        }
      });

      function updateResidentDropdowns() {
        const selectedValues = [];

        // Collect selected values
        document.querySelectorAll('.resident-select').forEach(select => {
          const val = select.value;
          if (val) selectedValues.push(val);
        });

        // Disable selected options in all dropdowns
        document.querySelectorAll('.resident-select').forEach(select => {
          const currentValue = select.value;
          Array.from(select.options).forEach(option => {
            if (option.value === '') return; // skip placeholder
            if (option.value !== currentValue && selectedValues.includes(option.value)) {
              option.disabled = true;
            } else {
              option.disabled = false;
            }
          });
        });
      }

      // Initial call
      updateResidentDropdowns();

      // Watch for change on any resident dropdown
      document.addEventListener('change', function (e) {
        if (e.target.classList.contains('resident-select')) {
          updateResidentDropdowns();
        }
      });
    });
    </script>
  <?php endif; ?>

  

<script>

  document.addEventListener('click', function (e) {
  if (e.target.classList.contains('status-update')) {
    const button = e.target;
    const newStatus = button.getAttribute('data-status');
    const dropdown = button.closest('.dropdown-menu');
    const blotterId = button.closest('.dropdown-menu').getAttribute('data-blotter-id');
    const toggleButton = dropdown.previousElementSibling;

    console.log('Updating status for blotter ID:', blotterId, 'to status:', newStatus);

    // Optional: Update the dropdown label immediately
    toggleButton.textContent = button.textContent;

    // Send the update to the server
    fetch('api/update-blotter-status.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({
        blotter_id: blotterId,
        status: newStatus
      })
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        alert('Status updated successfully!');
        // Optionally, you can refresh the page or update the UI further
        location.reload();
      } else {
        alert('Failed to update status.');
      }
    })
    .catch(err => {
      console.error('Request failed', err);
      alert('Error updating status.');
    });
  }
});

  document.getElementById('evidence').addEventListener('change', function () {
    const container = document.getElementById('preview-container');
    container.innerHTML = ''; // Clear previous previews

    console.log('Files selected:')

    Array.from(this.files).forEach(file => {
      const reader = new FileReader();

      reader.onload = function (e) {
        let preview;
        if (file.type.startsWith('image/')) {
          preview = document.createElement('img');
          preview.src = e.target.result;
          preview.className = 'img-thumbnail';
          preview.style.width = '100px';
          preview.style.height = '100px';
          preview.style.objectFit = 'cover';
        } else if (file.type.startsWith('video/')) {
          preview = document.createElement('video');
          preview.src = e.target.result;
          preview.controls = true;
          preview.className = 'img-thumbnail';
          preview.style.width = '100px';
          preview.style.height = '100px';
          preview.style.objectFit = 'cover';
        }

        container.appendChild(preview);
      };

      reader.readAsDataURL(file);
    });
  });

  // Clicking the label should trigger the file input
  document.querySelector('#evidence-label').addEventListener('click', () => {
    document.getElementById('evidence').click();
    console.log('Files selected123')
  });


</script>

<?php if ($page === 'add-new-blotter-report' || $page == 'edit-blotter-report') { ?>
  <script>
    setupAddressChain('complainant-province', 'complainant-municipality-city', 'complainant_barangay');
    setupAddressChain('suspect-province', 'suspect-municipality-city', 'suspect-barangay');

    function fillResidentFields(res,prefix) {    

      presentProvince = document.querySelector(`select[name="${prefix}_province"]`); 
      presentCity = document.querySelector(`select[name="${prefix}_city_municipality"]`);
      presentBarangay = document.querySelector(`select[name="${prefix}_barangay"]`);

      // Replace these with your actual input field IDs
      document.querySelector(`input[name="${prefix}_first_name"]`).value = res.first_name;
      document.querySelector(`input[name="${prefix}_middle_name"]`).value = res.middle_name;
      document.querySelector(`input[name="${prefix}_last_name"]`).value = res.last_name;
      document.querySelector(`input[name="${prefix}_dob"]`).value = res.date_of_birth;
      document.querySelector(`input[name="${prefix}_age"]`).value = res.date_of_birth ? new Date().getFullYear() - new Date(res.date_of_birth).getFullYear() : '';
      document.querySelector(`input[name="${prefix}_phone"]`).value = res.phone_number;
      document.querySelector(`input[name="${prefix}_email"]`).value = res.email;

      document.querySelector(`select[name="${prefix}_gender"]`).value = res.gender;
      document.querySelector(`select[name="${prefix}_civil_status"]`).value = res.civil_status;

      document.querySelector(`select[name="${prefix}_province"]`).value = res.present_province;
      document.querySelector(`input[name="${prefix}_zone"]`).value = res.present_zone;
      document.querySelector(`input[name="${prefix}_street"]`).value = res.present_street;
      document.querySelector(`input[name="${prefix}_landmark"]`).value = res.present_landmark;

      setupForEditAddressChain(res.present_province, res.present_city_municipality, res.present_barangay, presentProvince, presentCity, presentBarangay);
      // Optional: set hidden resident_id field
      document.querySelector(`input[name="${prefix}_resident_id"]`).value = res.id;
    }
    const residentSearchInput = document.querySelector('#residentSearch');
    const suggestionsBox = document.querySelector('#residentSuggestions');
    const residentSearchInputSuspect = document.querySelector('#residentSearch-suspect');
    const suggestionsBoxSuspect = document.querySelector('#residentSuggestions-suspect');

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
              fillResidentFields(resident,'complainant');
              suggestionsBox.innerHTML = '';
              residentSearchInput.value = item.textContent;
            });
            suggestionsBox.appendChild(item);
          });
        });
    });

  residentSearchInputSuspect.addEventListener('input', function () {
      console.log('Input changed:', this.value);
      const query = this.value.trim();
      if (query.length < 2) {
        suggestionsBoxSuspect.innerHTML = '';
        return;
      }

      fetch(`api/search-residents.php?term=${encodeURIComponent(query)}`)
        .then(res => res.json())
        .then(data => {
          suggestionsBoxSuspect.innerHTML = '';
          data.forEach(resident => {
            const item = document.createElement('button');
            item.type = 'button';
            item.className = 'list-group-item list-group-item-action';
            item.textContent = `${resident.last_name}, ${resident.first_name} ${resident.middle_name}`;
            item.addEventListener('click', () => {
              fillResidentFields(resident, 'suspect');
              suggestionsBoxSuspect.innerHTML = '';
              suggestionsBoxSuspect.value = item.textContent;
            });
            suggestionsBoxSuspect.appendChild(item);
          });
        });
    });

  </script>

<?php } ?>


<?php
  if ($page === 'edit-blotter-report') {
    $blotter_id = $_GET['blotter_id'] ?? null;
    if(isset($blotter_id)) {
      // Fetch the blotter report details from the database
      $stmt = $pdo->prepare("SELECT * FROM blotter_reports WHERE id = ?");
      $stmt->execute([$blotter_id]);
      $blotter = $stmt->fetch(PDO::FETCH_ASSOC);

      ?>
      <script>
        const complainantProvince = document.querySelector('#complainant-province');
        const complainantCity = document.querySelector('#complainant-municipality-city');
        const complainantBarangay = document.querySelector('#complainant_barangay');
        const suspectProvince = document.querySelector('#suspect-province');
        const suspectCity = document.querySelector('#suspect-municipality-city');
        const suspectBarangay = document.querySelector('#suspect-barangay');
        // set up complainant address chain
        setupForEditAddressChain('<?= htmlspecialchars($blotter['complainant_province'] ?? '') ?>', '<?= htmlspecialchars($blotter['complainant_city'] ?? '') ?>', '<?= htmlspecialchars($blotter['complainant_barangay'] ?? '') ?>', complainantProvince, complainantCity, complainantBarangay);
        // set up suspect address chain
        setupForEditAddressChain('<?= htmlspecialchars($blotter['suspect_province'] ?? '') ?>', '<?= htmlspecialchars($blotter['suspect_city'] ?? '') ?>', '<?= htmlspecialchars($blotter['suspect_barangay'] ?? '') ?>', suspectProvince, suspectCity, suspectBarangay);
      </script>
    <?php
    }
  }
?>

</body>
</html>

