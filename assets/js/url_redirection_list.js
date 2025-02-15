import 'datatables.net-responsive-bs5'
import 'datatables.net-fixedheader-bs5'
import 'datatables.net-bs5/css/dataTables.bootstrap5.min.css'
import 'datatables.net-fixedheader-bs5/css/fixedHeader.bootstrap5.min.css'
import 'datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css'
import './confirm.js'
import '../styles/confirm.css'
import routes from './fos_routes.js';
import Router from '@toyokumo/fos-router';
import {submitForm} from './utils.js'
import feather from 'feather-icons';

Router.setRoutingData(routes);

$(function() {
    var modalId = '#modalRedirectUrl';
    var formId = '#formRedirectUrl';
    $.jConfirm.defaults.theme = 'white';

    var dt = $('#url_redirection_table').DataTable({
        "layout": {
            "topStart": null,
            "bottomStart": ["pageLength", "info"]
        },
        "language": {
            "searchPlaceholder": 'Search...',
            "sSearch": '',
            "lengthMenu": '_MENU_',
        },
        "responsive": true,
        "pageLength": 100,
        "processing": true,
        "serverSide": true,
        "order": false,
        //"searching": false,
        "ajax": {
            "url": Router.generate('app_admin_url_redirections_ajax_list'),
            "data": function(d) {
                var filters = {};
                filters.old_url = $('#search_old_url').val();
                filters.new_url = $('#search_new_url').val();
                d.filters = filters;
            }
        },
        "columns": [
            {"data": "FormattedOldUrl", "orderable": false},
            {"data": "NewUrl", "orderable": false},
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

            $(modalId).find(formId).attr('action', Router.generate('app_admin_url_redirections_update', {id: id}));
            $('#old_url').val(data['OldUrl']);
            $('#new_url').val(data['NewUrl']);
            $(modalId).modal('show');
        });

        $('.btn-delete').jConfirm({position: 'left'}).on('confirm', function(e){
            var $btn = $(this);
            var id = $btn.closest('tr').attr('id');

            $btn.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');
            $.ajax({
                type: 'DELETE',
                url: Router.generate('app_admin_url_redirections_delete', {id: id}),
                dataType: 'json'
            }).done(function() {
                dt.ajax.reload(null, false);
            });
        });
    });

    $('.btn-new').on('click', function(e) {
        e.preventDefault();
        $(modalId).find(formId).attr('action', Router.generate('app_admin_url_redirections_create'));
        $('#old_url').val('');
        $('#new_url').val('');
        $(modalId).modal('show');
    })

    submitForm(formId, function(response, $form) {
        $form.trigger("reset");
        $form.closest('.modal').modal('hide');
        dt.ajax.reload(null, false);
    });

    $('.btn-search').on('click', function(e) {
        e.preventDefault();
        dt.draw();
    });
});