


document.addEventListener('DOMContentLoaded', function () {
  setupAddressChain('complainant-province', 'complainant-municipality-city', 'complainant_barangay');
  setupAddressChain('suspect-province', 'suspect-municipality-city', 'suspect-barangay');
  

});

  $('.blotter-delete-btn').on('click', function (e) {
  e.preventDefault();

  const id = $(this).data('id');
  if (!confirm('Are you sure you want to delete this blotter report?')) return;

  $.post('../src/actions/delete-blotter-report.php', { id: id }, function (response) {
    if (response === 'success') {
      alert('Blotter report deleted.');
      location.reload(); // Or remove row via JS
    } else {
      alert('Failed to delete blotter report: ' + response);
    }
  });
});

$(".letter-date-container").click(function(){
    const modal_id = $(this).closest(".modal").attr("id")
    const summon_date = $(this).next(`.letter-date`).find(`input`)[0];
    console.log(summon_date)
    summon_date.showPicker()
})

$(".letter-date input").change(function(){
    const letter_date_container = $(this).closest(".letter-date").prev(".letter-date-container");
    const summon_date = this.value;
    if(summon_date){
        const [year, month, day] = this.value.split("-");
        const date = `${getOrdinal(Number(day))}, day of ${toMonth(month-1)} ${year}`
        // console.log($(`#${modal_id} .letter-date-placeholder`))
        $(letter_date_container).find(`.letter-date-placeholder`).hide()
        $(letter_date_container).find(`.letter-date-value`).html(date).show()
    }else{
        $(letter_date_container).find(`.letter-date-placeholder`).show()
        $(letter_date_container).find(`.letter-date-value`).hide()
    }
})

$(".export-letter").click(function(){
    const modal_id = $(this).closest(".modal").attr("id");
    console.log($(`#${modal_id} .letter-date`), "aval")
    if($(`#${modal_id} .letter-date input`).val()){
        printElem(`#${modal_id} .letter`);
        return;
    }
    alert("Error", "No date selected")
})

$("[contenteditable]").focusout(function(){
    if(!this.textContent){
        $(this).empty();
    }
})


$(document).ready(function() {
  // Listen for the Bootstrap modal 'show' event
  $('.print-letter').on('click', function(event) {
    // Get the button that triggered the modal

    // Find the parent dropdown menu from the button
    const dropdownMenu = $(this).closest('.dropdown-menu');

    // Get the value of the data-complainant-full-name attribute
    const complainantName = dropdownMenu.data('complainant-full-name');
    const suspectName = dropdownMenu.data('suspect-full-name');
    const blotterCode = dropdownMenu.data('blotter-code');
    const modalID = $(this).data('bs-target');
    console.log(`${modalID} ${complainantName}`);

    // Find the element in the modal where you want to display the name
    const modalBody = $(modalID).find('.modal-body');


    // Update the content of that element
    modalBody.find('.complainant-name-placeholder').text(complainantName);
    modalBody.find('.suspect-name-placeholder').text(suspectName);
    modalBody.find('.blotter-code-placeholder').text(blotterCode);
  });
});

