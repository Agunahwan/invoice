$.ajaxSetup({
  headers: {
    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
  },
});

$(document).ready(function () {
  // Populate Dropdown
  getListClient();
  getListItem();

  if (invoiceItems.length > 0) {
    populateGridItems();
  }
});

function clearFormItem() {
  populateListItem();
  $("#Quantity").val("");
}

function addItem() {
  clearFormItem();

  $("#item-dialog").modal("show");
}

function onCancel() {
  var url = local + "/";
  window.location = url;
}

function onSaveItem() {
  var selectedItem = $("#Item").val();
  var quantity = $("#Quantity").val();

  if (selectedItem && parseInt(quantity)) {
    var invoiceItem = items.find((x) => x.id == selectedItem);

    var item = {
      item: invoiceItem,
      quantity: parseInt(quantity),
    };

    invoiceItems.push(item);
    populateGridItems();

    $("#item-dialog").modal("hide");
  }
}

async function getListClient() {
  var url = local + "/client/all";

  var data = await getData(url);

  // Populate Client
  var client = $("#InvoiceFor");
  client.find("option").remove();
  client.append($("<option />").val("").text("Pilih Client ..."));
  $.each(data, function () {
    if (this.id == $("#ClientIdSelected").val()) {
      client.append(
        $("<option />").val(this.id).text(this.name).attr("selected", true)
      );
    } else {
      client.append($("<option />").val(this.id).text(this.name));
    }
  });
}

async function getListItem() {
  var url = local + "/item/all";

  var data = await getData(url);
  items = data;

  // Populate Client
  populateListItem();
}

function populateListItem() {
  var item = $("#Item");
  item.find("option").remove();
  item.append($("<option />").val("").text("Pilih Item ..."));
  $.each(items, function () {
    item.append($("<option />").val(this.id).text(this.description));
  });
}

function populateGridItems() {
  // Prepare for calculate all amount
  var subtotal = 0;
  var totalTax = 0;
  var tax = parseInt($("#Tax").val());
  var totalPayments = 0;

  $("#dataItem .list tr").remove();
  console.log("Data:" + JSON.stringify(invoiceItems));
  $.each(invoiceItems, function (index, invoiceItem) {
    if (invoiceItem.item) {
      var amount = invoiceItem.quantity * invoiceItem.item.unit_price;

      if (isView == 1) {
        var item = `<tr>
            <td> ${invoiceItem.item.item_type.type}</td>
            <td> ${invoiceItem.item.description} </td>
            <td> ${invoiceItem.quantity} </td>
            <td> ${invoiceItem.item.unit_price} </td>
            <td> ${amount} </td>
        </tr>`;
      } else {
        var item = `<tr>
            <td> ${invoiceItem.item.item_type.type}</td>
            <td> ${invoiceItem.item.description} </td>
            <td> ${invoiceItem.quantity} </td>
            <td> ${invoiceItem.item.unit_price} </td>
            <td> ${amount} </td>
            <td><a class='btn btn-sm btn-danger' onclick='onDeleteItem(${index})'>Delete</a></td>
        </tr>`;
      }
      $("#dataItem .list").append(item);

      subtotal += amount;
    }
  });
  totalTax = (subtotal * tax) / 100;
  totalPayments = subtotal + totalTax;

  // Set Payment Data
  $("#Subtotal").val(subtotal);
  $("#TotalTax").val(totalTax);
  $("#TotalPayments").val(totalPayments);
}

function onDeleteItem(index) {
  invoiceItems.splice(index, 1);

  populateGridItems();
}

function onChangePayment() {
  var totalPayments = $("#TotalPayments").val();
  var payments = $("#Payments").val();
  var amountDue = totalPayments - payments;

  $("#AmountDue").val(amountDue);
}

function onSave() {
  var title = "Add Invoice";

  // Preparing Url
  var redirectUrl = local + "/";
  var url = local + "/invoice/save";
  var isPaid = parseInt($("#AmountDue").val()) <= 0 ? 1 : 0;

  // Preparing data
  var data = {
    id: $("#InvoiceId").val(),
    company_id: $("#CompanyId").val(),
    client_id: $("#InvoiceFor").val(),
    issue_date: $("#IssueDate").val(),
    due_date: $("#DueDate").val(),
    subject: $("#Subject").val(),
    subtotal: $("#Subtotal").val(),
    tax: $("#TotalTax").val(),
    total_payments: $("#TotalPayments").val(),
    payments: $("#Payments").val(),
    amount_due: $("#AmountDue").val(),
    is_paid: isPaid,
    items: invoiceItems,
  };

  // Save data
  save(title, url, data, redirectUrl);
}
