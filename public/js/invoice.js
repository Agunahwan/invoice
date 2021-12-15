$.ajaxSetup({
  headers: {
    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
  },
});

$(document).ready(function () {
  // Preparing urls
  var dataUrl = local + "/invoice/data";
  var editUrl = local + "/invoice/edit";
  var detailUrl = "";
  var deleteUrl = local + "/invoice";

  var columns = [
    { data: "id", name: "id", autoWidth: true },
    { data: "subject", name: "subject", autoWidth: true },
    { data: "issue_date", name: "issue_date", autoWidth: true },
    { data: "due_date", name: "due_date", autoWidth: true },
    { data: "subtotal", name: "subtotal", autoWidth: true },
  ];

  initGrid("dataTable", columns, dataUrl, editUrl, detailUrl, deleteUrl);
});

function addData() {
  var url = local + "/invoice/add";
  window.location = url;
}
