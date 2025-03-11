function exportTableToExcel() {
    var table = $('#orderTable').DataTable();
    var data = table.data();
    var Array = [];
    var cols = [];
    $('#orderTable th').each(function () {
        cols.push($(this).text());
    });
  
    Array.push(cols);
    data.each(function (value, index) {
        Array.push(value);
    });
  
    var ws = XLSX.utils.aoa_to_sheet(Array);
    var wscols = [];
    cols.forEach(function (col, index) {
      var maxLength = col.length;
      data.each(function (row) {
        if (row[index] && row[index].length > maxLength) {
          maxLength = row[index].length;
        }
      });
      wscols.push({ width: maxLength + 2 });
    });
    ws['!cols'] = wscols;
  
    var wb = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(wb, ws, 'Inventory_Order');
    var wbout = XLSX.write(wb, { bookType: 'xlsx', type: 'binary' });
    saveAs(new Blob([s2ab(wbout)], { type: 'application/octet-stream' }), 'Inventory_Orders.xlsx');
  }

  function exportAsTableToExcel() {
    var table = $('#exampleExpand').DataTable();
    var data = table.data();
    var Array = [];
    var cols = [];
    $('#exampleExpand th').each(function () {
        cols.push($(this).text());
    });
  
    Array.push(cols);
    data.each(function (value, index) {
        Array.push(value);
    });
  
    var ws = XLSX.utils.aoa_to_sheet(Array);
    var wscols = [];
    cols.forEach(function (col, index) {
      var maxLength = col.length;
      data.each(function (row) {
        if (row[index] && row[index].length > maxLength) {
          maxLength = row[index].length;
        }
      });
      wscols.push({ width: maxLength + 2 });
    });
    ws['!cols'] = wscols;
  
    var wb = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(wb, ws, 'Report');
    var wbout = XLSX.write(wb, { bookType: 'xlsx', type: 'binary' });
    saveAs(new Blob([s2ab(wbout)], { type: 'application/octet-stream' }), 'report.xlsx');
  }
  
  function s2ab(s) {
    var buf = new ArrayBuffer(s.length);
    var view = new Uint8Array(buf);
    for (var i = 0; i < s.length; i++) view[i] = s.charCodeAt(i) & 0xff;
    return buf;
  }