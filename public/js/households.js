  function setupZoneLandmark(householdHead) {

    householdHead.addEventListener('change', function () {
      fetch(`api/get-resident.php?id=${this.value}`)
        .then(res => res.json())
        .then(data => {
        document.getElementById('present_zone').value = data.present_zone || '';
        document.getElementById('present_street').value = data.present_street || '';
        document.getElementById('present_landmark').value = data.present_landmark || '';
        })
    });
  }


function createMemberRepeaterBlock(residentID, residentName, relationship, memberOptions, containerID, selectedResidentIds) {
  // Update the global tracker if this is a new block
  if (!selectedResidentIds.includes(residentID)) {
    selectedResidentIds.push(residentID);
  }

  // Filter out already selected IDs
  const filteredOptions = memberOptions.filter(member => 
    !selectedResidentIds.includes(member.id) || member.id === residentID // allow current selection
  );

  const membersSelect = filteredOptions.map(member => {
    const selected = residentID === member.id ? 'selected' : '';
    return `<option value="${member.id}" ${selected}>${member.full_name}</option>`;
  }).join('');

  const relationshipOptions = ['Spouse', 'Child', 'Parent', 'Sibling', 'Relative', 'Other'];
  const relationshipSelect = relationshipOptions.map(rel => {
    return `<option value="${rel}" ${relationship === rel ? 'selected' : ''}>${rel}</option>`;
  }).join('');

  const block = document.createElement('div');
  block.className = 'mb-3 member-repeater-block';

  block.innerHTML = `
    <div id="resident-dropdown-group">
      <div class="mb-3 resident-dropdown row">
        <div class="col-md-6">
          <label for="residents[]" class="form-label">Select Member</label>
          <select name="residents[]" class="form-select resident-select" required>
            <option value="">-- Select Resident --</option>
            ${membersSelect}
          </select>
        </div>
        <div class="col-md-4">
          <label for="relationship[]" class="form-label">Relationship With Head</label>
          <select name="relationship[]" class="form-select" required>
            <option value="">-- Select Relationship --</option>
            ${relationshipSelect}
          </select>
        </div>
        <div class="col-md-2 d-flex align-items-end">
          <button type="button" class="btn btn-danger btn-sm remove-member">Remove</button>
        </div>
      </div>
    </div>
  `;

  $(containerID).prepend(block);
}

document.addEventListener('DOMContentLoaded', function () {
  const householdHead = document.getElementById('household_head_id');
  setupZoneLandmark(householdHead);


}
);


$(document).ready(function () {

  $('.edit-household-btn').on('click', function () {
    const householdID = $(this).data('id');
      // Fetch data via AJAX
      fetch(`../public/api/get-household.php?id=${householdID}`)
        .then(res => res.json())
        .then(data => {
          // Set household fields
          document.getElementById('edit_household_code').value = data.household.household_code;
          document.getElementById('edit_zone').value = data.household.address_zone;
          document.getElementById('edit_street').value = data.household.address_street;
          document.getElementById('edit_landmark').value = data.household.address_landmark;
          document.getElementById('edit_household_id').value = data.household.id;
          document.getElementById('edit_ownership').value = data.household.ownership_status;

          // Populate head dropdown
          const headDropdown = document.getElementById('edit_household_head_id');
          headDropdown.innerHTML = '';
          data.headOptions.forEach(opt => {
            const option = document.createElement('option');
            option.value = opt.id;
            option.textContent = opt.full_name;
            if (opt.id == data.household.head_id) {
              option.selected = true;
            }
            headDropdown.appendChild(option);
          });
          
        });

  });

  $('.manage-members-btn').on('click', function () {
    const householdID = $(this).data('id');
      // Fetch data via AJAX
      fetch(`../public/api/get-household.php?id=${householdID}`)
        .then(res => res.json())
        .then(data => {

          document.getElementById('member_household_id').value = data.household.id;

          console.log(data.household.id);

          let selectedResidentIds = [];
          // Clear existing member repeater blocks
          for (let index = 0; index < data.household_members.length; index++) {
            selectedResidentIds.push(data.household_members[index].resident_id);
          }

          console.log(data.household_members_options)
          // Populate head dropdown
          data.household_members.forEach(member => {

            createMemberRepeaterBlock(member.resident_id,member.full_name,member.relationship_to_head,data.household_members_options,'#memberRepeaterContainer', selectedResidentIds);
        });

      });

        // On resident dropdown change
  $(document).on('change', 'select[name="residents[]"]', function () {
    let selectedResidentIds = [];

    // Collect selected IDs from all dropdowns
    $('select[name="residents[]"]').each(function () {
      const val = $(this).val();
      if (val) {
        selectedResidentIds.push(parseInt(val));
      }
    });

    // Rebuild each dropdown
    $('select[name="residents[]"]').each(function () {
      const currentSelect = $(this);
      const currentVal = currentSelect.val(); // ✅ Save selection BEFORE clearing

      const dropdownHtml = ['<option value="">-- Select Resident --</option>'];

      data.household_members.forEach(member => {
        const isSelected = member.resident_id == currentVal;
        const isDuplicate = selectedResidentIds.includes(member.resident_id) && !isSelected;

        dropdownHtml.push(`
          <option value="${member.resident_id}" 
                  ${isSelected ? 'selected' : ''} 
                  ${isDuplicate ? 'disabled style="color: #888;"' : ''}>
            ${member.full_name}${isDuplicate ? ' (Already selected)' : ''}
          </option>`);
      });

      currentSelect.html(dropdownHtml.join(''));
    });
  });




  });

  $('.delete-btn').on('click', function (e) {
  e.preventDefault();

  const id = $(this).data('id');
  if (!confirm('Are you sure you want to delete this household?')) return;

  $.post('../src/actions/delete-household.php', { id: id }, function (response) {
    if (response === 'success') {
      alert('Household deleted.');
      location.reload(); // Or remove row via JS
    } else {
      alert('Failed to delete household: ' + response);
    }
  });
});


});



