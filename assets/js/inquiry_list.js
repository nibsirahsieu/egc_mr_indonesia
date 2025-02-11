import 'datatables.net-responsive-bs5'
import 'datatables.net-fixedheader-bs5'
import 'datatables.net-bs5/css/dataTables.bootstrap5.min.css'
import 'datatables.net-fixedheader-bs5/css/fixedHeader.bootstrap5.min.css'
import 'datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css'
import routes from './fos_routes.js';
import Router from '@toyokumo/fos-router';

Router.setRoutingData(routes);

$(function() {
    var dt = $('#inquiries_table').DataTable({
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
            "url": Router.generate('app_admin_inquries_ajax_list')
        },
        "columns": [
            {"data": "NameWithTitle", "orderable": false},
            //{"data": "Email", "orderable": false},
            //{"data": "JobTitle", "orderable": false},
            {"data": "CompanyWithEmail", "orderable": false},
            {"data": "Country", "orderable": false},
            {"data": "PhoneNumber", "orderable": false},
            {"data": "FromPage", "orderable": false},
            {"data": "CreatedAt", "orderable": false},
            {"data": "Actions", "orderable": false, 'class': 'text-end'}
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

            var $modal = $('#inquiry_modal');
            $modal.find('.inquiry_name').text(data['Name']);
            $modal.find('.inquiry_email').text(data['Email']);
            $modal.find('.inquiry_job_title').text(data['JobTitle']);
            $modal.find('.inquiry_company_name').text(data['CompanyName']);
            $modal.find('.inquiry_country').text(data['Country']);
            $modal.find('.inquiry_phone_number').text(data['PhoneNumber']);
            $modal.find('.inquiry_message').text(data['FullMessage']);
            $modal.find('.inquiry_source').text(data['FromPage'] || 'N/A');

            $modal.modal('show');
        });
    });
});