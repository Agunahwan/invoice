var masterDataTable = [];

function initGrid(
  tableId,
  columns,
  dataUrl,
  editUrl = "",
  detailUrl = "",
  deleteUrl = "",
  searching = true,
  redirectUrl = "",
  customAction = [],
  toolbar = "",
  actionFunc = undefined
) {
  var element = $("#" + tableId);

  // Prevent parameters of CRUD urls
  if (editUrl !== "") {
    if (new RegExp("[?&]").exec(editUrl)) editUrl += "&";
    else editUrl += "?";
  }
  if (detailUrl !== "") {
    if (new RegExp("[?&]").exec(detailUrl)) detailUrl += "&";
    else detailUrl += "?";
  }

  // Set DOM
  var toolbarHtml = "";
  if (toolbar !== "") {
    toolbarHtml = "<'toolbar'>";
  }

  // Initialize Grid
  masterDataTable[tableId] = element.DataTable({
    serverSide: true,
    processing: true,
    ajax: {
      url: dataUrl,
      method: "POST",
      dataType: "json",
    },
    dom:
      toolbarHtml +
      "<'headTable row'<'col-sm-6'l><'col-sm-6'f>>" +
      "<'bodyTable row'<'col-sm-12'tr>>" +
      "<'pagingTable row'<'col-sm-5'i><'col-sm-7'p>>",
    select: true,
    searching: searching,
    ordering: true,
    orderMulti: false, //Multi column order is disabled
    lengthMenu: [
      [10, 20, 50, 100],
      [10, 20, 50, 100],
    ],
    columnDefs: [
      {
        // The `data` parameter refers to the data for the cell (defined by the
        // `data` option, which defaults to the column being worked with, in
        // this case `data: 0`.
        render: function (data, type, row) {
          var buttons = "";

          if (actionFunc !== undefined) {
            buttons = actionFunc(data, type, row);
          } else {
            buttons = generateActionGrid(
              row,
              tableId,
              editUrl,
              detailUrl,
              deleteUrl,
              redirectUrl,
              customAction
            );
          }

          return buttons;
        },
        targets: totalColumnTable === 0 ? columns.length : totalColumnTable,
      },
    ],
    columns: columns,
  });

  // Set toolbar to grid
  if (toolbar !== "") {
    $("div.toolbar").html(`${toolbar}`);
  }
}

function generateActionGrid(
  row,
  tableId,
  editUrl,
  detailUrl,
  deleteUrl,
  redirectUrl,
  customAction
) {
  var buttons = "";

  if (customAction.length > 0) {
    $.each(customAction, function () {
      var button = this.data;
      var params = this.params;
      $.each(params, function () {
        button = button.replace(this.code, row[this.value]);
      });
      buttons += button + " ";
    });
  } else {
    // Default setting
    if (editUrl !== "")
      buttons +=
        "<a class='btn btn-sm btn-warning' href='" +
        editUrl +
        "id=" +
        row.id +
        "'>Edit</a> ";
    if (detailUrl !== "")
      buttons +=
        "<a class='btn btn-sm btn-primary' href='" +
        detailUrl +
        "id=" +
        row.id +
        "'>Details</a> ";
    if (deleteUrl !== "") {
      if (redirectUrl !== "")
        buttons +=
          "<a class='btn btn-sm btn-danger' onclick='confirmDelete(" +
          row.id +
          ',"' +
          tableId +
          '","' +
          deleteUrl +
          '","' +
          redirectUrl +
          "\")'>Delete</a>";
      else
        buttons +=
          "<a class='btn btn-sm btn-danger' onclick='confirmDelete(" +
          row.id +
          ',"' +
          tableId +
          '","' +
          deleteUrl +
          "\")'>Delete</a>";
    }
  }

  return buttons;
}
