  



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