import 'datatables.net-responsive-bs5'
import 'datatables.net-fixedheader-bs5'
import 'datatables.net-bs5/css/dataTables.bootstrap5.min.css'
import 'datatables.net-fixedheader-bs5/css/fixedHeader.bootstrap5.min.css'
import 'datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css'
import './confirm.js'
import '../styles/confirm.css'
import routes from './fos_routes.js';
import Router from '@toyokumo/fos-router';

Router.setRoutingData(routes);

$(function() {
    $.jConfirm.defaults.theme = 'white';

    var dt = $('#insights_table').DataTable({
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
        "ajax": {
            "url": Router.generate('app_insight_ajax_list')
        },
        "columns": [
            {"data": "Category", "orderable": false},
            {"data": "Title", "orderable": false},
            {"data": "Author", "orderable": false},
            {"data": "Status", "orderable": false},
            {"data": "PublishedAt", "orderable": false},
            {"data": "Actions", "orderable": false, 'className': 'text-end'}
        ]
    });

    dt.on('draw', function() {
        $('.btn-delete').jConfirm({position: 'left'}).on('confirm', function(e){
            var $btn = $(this);
            var url = $btn.attr('href');
            $btn.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');
            $.ajax({
                type: 'DELETE',
                url: url,
                dataType: 'json'
            }).done(function() {
                dt.ajax.reload(null, false);
            });
        });
        $('.btn-publish-unpublish').jConfirm({position: 'left'}).on('confirm', function(e){
            var $btn = $(this);
            var url = $btn.attr('href');
            var text = $btn.text();

            $btn.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');
            $.ajax({
                type: 'POST',
                url: url,
                dataType: 'json'
            }).done(function() {
                $btn.text(text);
                dt.ajax.reload(null, false);
            });
        });
    });
});