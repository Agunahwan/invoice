$.ajaxSetup({
  headers: {
    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
  },
});

$(document).ready(function () {
  // Preparing urls
  var dataUrl = "";
  var editUrl = "";
  var detailUrl = "";
  var deleteUrl = "";

  var columns = [
    { data: "code", name: "@nameof(Model.Code)", autoWidth: true },
    { data: "discipline", name: "@nameof(Model.Discipline)", autoWidth: true },
    {
      data: "isActive",
      name: "@nameof(Model.IsActive)",
      autoWidth: true,
      render: function (data, type, row, meta) {
        if (data == 1) {
          return "Yes";
        } else {
          return "No";
        }
      },
    },
  ];

  initGrid();
});

function addData() {
  var url = local + "/invoice/add";
  window.location = url;
}
