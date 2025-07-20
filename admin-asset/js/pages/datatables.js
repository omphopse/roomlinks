if ( $.fn.dataTable.isDataTable( '#row-callback-datatable' ) ) {
    $('#row-callback-datatable').DataTable().clear().destroy();
}
$("#row-callback-datatable").DataTable({
    paging: true,
    searching: true,
    lengthChange: true,
    info: true,
    language: {
        paginate: {
            previous: "<i class='mdi mdi-chevron-left'>",
            next: "<i class='mdi mdi-chevron-right'>"
        }
    },
    drawCallback: function() {
        $(".dataTables_paginate > .pagination").addClass("pagination-rounded");
    }
});

if ( $.fn.dataTable.isDataTable( '#row-callback-datatable-partner-referrals' ) ) {
    $('#row-callback-datatable-partner-referrals').DataTable().clear().destroy();
}
$("#row-callback-datatable-partner-referrals").DataTable({
    paging: true,
    searching: true,
    lengthChange: true,
    info: true,
    language: {
        paginate: {
            previous: "<i class='mdi mdi-chevron-left'>",
            next: "<i class='mdi mdi-chevron-right'>"
        }
    },
    drawCallback: function() {
        $(".dataTables_paginate > .pagination").addClass("pagination-rounded");
    }
});

if ( $.fn.dataTable.isDataTable( '#row-callback-datatable-withdrawal' ) ) {
    $('#row-callback-datatable-withdrawal').DataTable().clear().destroy();
}
$("#row-callback-datatable-withdrawal").DataTable({
    paging: true,
    searching: true,
    lengthChange: true,
    info: true,
    language: {
        paginate: {
            previous: "<i class='mdi mdi-chevron-left'>",
            next: "<i class='mdi mdi-chevron-right'>"
        }
    },
    drawCallback: function() {
        $(".dataTables_paginate > .pagination").addClass("pagination-rounded");
    }
});

if ( $.fn.dataTable.isDataTable( '#row-callback-datatable-wallet' ) ) {
    $('#row-callback-datatable-wallet').DataTable().clear().destroy();
}
$("#row-callback-datatable-wallet").DataTable({
    paging: true,
    searching: true,
    lengthChange: true,
    info: true,
    language: {
        paginate: {
            previous: "<i class='mdi mdi-chevron-left'>",
            next: "<i class='mdi mdi-chevron-right'>"
        }
    },
    drawCallback: function() {
        $(".dataTables_paginate > .pagination").addClass("pagination-rounded");
    }
});
