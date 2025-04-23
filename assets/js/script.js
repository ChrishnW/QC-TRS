function Login() {
  const credentials = new FormData(document.getElementById('loginDetails'));
  $.ajax({
    method: 'POST',
    url: 'include/login.php',
    data: credentials,
    processData: false,
    contentType: false,
    success: function (response) {

      if (response == 'Admin') {
        window.location.href = 'pages/admin_dashboard.php';
      } 
      else if (response == 'Requestor') {
        window.location.href = 'pages/requestor_dashboard.php';
      }
      else if (response == 'Editor') {
        window.location.href = 'pages/editor_dashboard.php';
      }
      else if (response == 'Approver') {
        window.location.href = 'pages/approver_dashboard.php';
      }
      else if (response == 'Auditor') {
        window.location.href = 'pages/auditor_dashboard.php';
      }
      else {
        alert(response);
      }
    }
  });
}

// function Login() {
//   window.location.href = 'pages/index.php';
// }

// $.fn.dataTable.ext.type.order['date-custom-pre'] = function (d) {
//   var months = {
//     "January": 1,
//     "February": 2,
//     "March": 3,
//     "April": 4,
//     "May": 5,
//     "June": 6,
//     "July": 7,
//     "August": 8,
//     "September": 9,
//     "October": 10,
//     "November": 11,
//     "December": 12
//   };
//   var dateParts = d.split(' ');
//   return new Date(dateParts[2], months[dateParts[0]] - 1, dateParts[1].replace(',', ''));
// };

// function display(itemID) {
//   $.ajax({
//     url: '../include/query.php',
//     method: 'POST',
//     data: {
//       displayMold: true,
//       itemID: itemID
//     },
//     success: function (response) {
//       $('#moldDetails').html(response);
//       $('#moldModal').modal('show');
//     }
//   });
// }