var masterDataTable = [];
var tableName = "";
var totalColumnTable = 0;

async function getData(url) {
  return $.ajax({
    url: url,
    dataType: "json",
    contentType: "application/json; charset=utf-8",
    cache: false,
    type: "GET",
  }).then((response) => response.data);
}

function showMessage(title, message, isSuccess, redirectUrl = "") {
  $("#message-data-title").html(title);
  $("#message-data-body").html(message);
  if (isSuccess) {
    $("#message-data-label").attr("class", "alert alert-success");
  } else {
    $("#message-data-label").attr("class", "alert alert-danger");
  }
  if (redirectUrl !== "") {
    $("#redirectUrl").val(redirectUrl);
  }
  $("#modal-message").modal("show");
}

function showErrorMessage(title, requestObject, errorThrown) {
  if (
    requestObject &&
    requestObject.responseJSON &&
    requestObject.responseJSON.errorMesssage
  ) {
    showMessage(title, requestObject.responseJSON.errorMesssage, false);
  } else {
    showMessage(title, errorThrown, false);
  }
}

function onClose() {
  $("#modal-message").modal("hide");

  if ($("#message-data-label").attr("class") === "alert alert-success") {
    // Redirect to index
    //window.location.href = $("#redirectUrl").val();

    var destinationUrl = window.location.origin + $("#redirectUrl").val();
    var currentUrl = window.location.href;

    if (destinationUrl == currentUrl && destinationUrl.indexOf("#") > 0) {
      window.location.reload();
    } else {
      window.location.href = $("#redirectUrl").val();
    }
  }
}

function save(title, url, data, redirectUrl) {
  $.ajax({
    url: url,
    data: data,
    cache: false,
    type: "POST",
    success: function (data, textStatus, jQxhr) {
      if (data.is_success) {
        showMessage(title, "Data berhasil disimpan", true, redirectUrl);
      } else {
        showMessage(title, data.error_messsage, false);
      }
    },
    error: function (requestObject, error, errorThrown) {
      showErrorMessage(title, requestObject, errorThrown);
    },
  });
}

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
      method: "GET",
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

function confirmDelete(id, tableId, url, redirectUrl = "") {
  tableName = tableId;
  $("#id").val(id);
  $("#url").val(url);
  if (redirectUrl !== "") $("#redirectUrl").val(redirectUrl);

  var dialog = $("#dialog-confirmation").modal("show");

  $("#btn-no").click(function () {
    dialog.modal("hide");
  });
}

function onDelete() {
  var id = $("#id").val();
  var url = $("#url").val();
  var title = "Delete Data";

  $("#dialog-confirmation").modal("hide");

  $.ajax({
    url: url + "/" + id,
    data: {
      id: id,
    },
    cache: false,
    type: "DELETE",
    success: function (data, textStatus, jQxhr) {
      if (data.is_success) {
        showMessage(title, "Data berhasil dihapus", true);
      } else {
        showMessage(title, "Data gagal dihapus", false);
      }
      refreshData(tableName);
    },
    error: function (requestObject, error, errorThrown) {
      showErrorMessage(title, requestObject, errorThrown);
    },
  });
}

function refreshData(tableId) {
  masterDataTable[tableId].ajax.reload();
}
