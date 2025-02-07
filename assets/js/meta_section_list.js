import 'datatables.net-responsive-bs5'
import 'datatables.net-fixedheader-bs5'
import 'datatables.net-bs5/css/dataTables.bootstrap5.min.css'
import 'datatables.net-fixedheader-bs5/css/fixedHeader.bootstrap5.min.css'
import 'datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css'
import routes from './fos_routes.js';
import Router from '@toyokumo/fos-router';
import {submitForm} from './utils.js'
import feather from 'feather-icons';

Router.setRoutingData(routes);

$(function() {
    var modalId = '#modalMetaSection';
    var formId = '#formMetaSection';

    var dt = $('#meta_section_table').DataTable({
        "responsive": true,
        "paging": false,
        "processing": true,
        "serverSide": true,
        "order": false,
        "searching": false,
        "ajax": {
            "url": Router.generate('app_admin_meta_sections_ajax_list')
        },
        "columns": [
            {"data": "Page", "orderable": false},
            {"data": "MetaTitle", "orderable": false},
            {"data": "MetaDescription", "orderable": false},
            {"data": "Actions", "orderable": false, 'className': 'text-end'}
        ]
    });

    dt.on('draw', function() {
        feather.replace();
        $('.btn-edit').on('click', function(e) {
            e.preventDefault();
            var $self = $(this);
            var $tr = $self.closest('tr');
            var row = dt.row($tr);
            var data = row.data();
            var id = $tr.attr('id');

            $(modalId).find(formId).attr('action', Router.generate('app_admin_meta_sections_update', {id: id}));
            $('#page_type').val(data['Page']);
            $('#meta_title').val(data['MetaTitle']);
            $('#meta_description').val(data['MetaDescription']);
            $(modalId).modal('show');
        });
    });

    submitForm(formId, function(response, $form) {
        $form.trigger("reset");
        $form.closest('.modal').modal('hide');
        dt.ajax.reload(null, false);
    });
});