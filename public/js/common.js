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

  
function getOrdinal(number) {
  const suffixes = ['th','st','nd','rd','th','th','th','th','th','th'];
  if ((number % 100) >= 11 && (number % 100) <= 13) {
    return number + 'th';
  } else {
    return number + suffixes[number % 10];
  }
}


function toMonth(monthNumber) {
  const months = [
    "January", "February", "March", "April", "May", "June",
    "July", "August", "September", "October", "November", "December"
  ];
  return months[monthNumber - 1] || "";
}
function printElem(element, callback) {
  $("#printable-page").html("");
  $(element).clone().appendTo("#printable-page");
  if(callback){
    callback($("#printable-page")[0]);
  }
  window.print();
  return $("#printable-page");
}

// reusable function for print - plain text only
function printModal(modalId) {
    // Get modal content
    const modal = document.getElementById(modalId);
    const content = modal.querySelector('.modal-body').innerHTML; 
    // You can also use `.modal-content` if you want the header/footer included

    // Open a new window
    const printWindow = window.open('', '', 'width=900,height=650');
    
    // Write modal content into new window
    printWindow.document.write(`
        <html>
            <head>
                <title>Print Certificate</title>
                <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
                <style>
                    body { font-family: 'Noto Sans', sans-serif; padding: 20px; }
                </style>
            </head>
            <body>
                ${content}
            </body>
        </html>
    `);

    // Close document & trigger print
    printWindow.document.close();
    printWindow.focus();
    printWindow.print();
    printWindow.close();
}

function printElementById(elementId, title = "Print Preview") {
    const element = document.getElementById(elementId);
    if (!element) {
        console.error("Element not found: " + elementId);
        return;
    }

    const printable = element.innerHTML;
    const printWindow = window.open("", "_blank", "width=900,height=650");
    printWindow.document.open();

    printWindow.document.write(`
        <html>
            <head>
                <title>${title}</title>
                <!-- Bootstrap CSS -->
                <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
                <!-- Your custom theme.css -->
                <link href="/public/css/theme.css" rel="stylesheet">
                <style>
                    body { font-family: 'Noto Sans', sans-serif; padding: 30px; }
                    .a4 { width: 210mm; min-height: 297mm; margin: auto; }
                </style>
            </head>
            <body onload="window.print(); window.close();">
                <div class="a4">
                    ${printable}
                </div>
            </body>
        </html>
    `);

    printWindow.document.close();
}


// call the get-barangay-officials.php to get all barangay officials names and positions
// $.get('../public/api/get-barangay-officials.php', function (data) {
//   const officials = JSON.parse(data);
//   const officialList = $('#official-list');
//   officialList.empty();
//   officials.forEach(function (official) {
//     officialList.append(`<li>${official.name} - ${official.position}</li>`);
//     console.log(`Added official: ${official.first_name} - ${official.position}`);
//   });
// });

