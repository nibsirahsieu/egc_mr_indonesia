import 'datatables.net-responsive-bs5'
import 'datatables.net-fixedheader-bs5'
import 'datatables.net-bs5/css/dataTables.bootstrap5.min.css'
import 'datatables.net-fixedheader-bs5/css/fixedHeader.bootstrap5.min.css'
import 'datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css'
import routes from './fos_routes.js';
import Router from '@toyokumo/fos-router';

Router.setRoutingData(routes);

$(function() {
    var dt = $('#wp_request_table').DataTable({
        "layout": {
            "topStart": null,
            "bottomStart": ["pageLength", "info"]
        },
        "language": {
            "searchPlaceholder": 'Search...',
            "sSearch": '',
            "lengthMenu": '_MENU_'
        },
        "responsive": true,
        "pageLength": 100,
        "processing": true,
        "serverSide": true,
        "order": false,
        "searching": false,
        "ajax": {
            "url": Router.generate('app_admin_wp_requests_ajax_list')
        },
        "columns": [
            {"data": "Whitepaper", "orderable": false},
            {"data": "Name", "orderable": false},
            {"data": "Email", "orderable": false},
            {"data": "JobTitle", "orderable": false},
            {"data": "CompanyName", "orderable": false},
            {"data": "Country", "orderable": false},
            {"data": "CreatedAt", "orderable": false},
            {"data": "Actions", "orderable": false}
        ]
    });
    dt.on('draw', function() {
        $('.btn-detail').on('click', function(e) {
            e.preventDefault();
            var $self = $(this);
            var $tr = $self.closest('tr');
            if ($tr.hasClass('child')) {
                $tr = $tr.prev('tr.parent');
            }
            var row = dt.row($tr);
            var data = row.data();
            console.log(data);

            var $modal = $('#wp_request_modal');
            $modal.find('.wp_request_title').text(data['Whitepaper']);
            $modal.find('.wp_request_name').text(data['Name']);
            $modal.find('.wp_request_email').text(data['Email']);
            $modal.find('.wp_request_job_title').text(data['JobTitle']);
            $modal.find('.wp_request_company_name').text(data['CompanyName']);
            $modal.find('.wp_request_country').text(data['Country']);

            $modal.modal('show');
        });
    });
});