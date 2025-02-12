import './summernote/summernote-bs5.min.js'
import './summernote/summernote-bs5.min.css'
import 'lite-uploader'
import 'parsleyjs'
import 'bootstrap-maxlength'
import autosize from 'autosize'
import routes from './fos_routes.js';
import Router from '@toyokumo/fos-router';

Router.setRoutingData(routes);

//https://jasonwatmore.com/vanilla-js-slugify-a-string-in-javascript
function slugify(input) {
  if (!input)
      return '';

  // make lower case and trim
  var slug = input.toLowerCase().trim();

  // remove accents from charaters
  slug = slug.normalize('NFD').replace(/[\u0300-\u036f]/g, '')

  // replace invalid chars with spaces
  slug = slug.replace(/[^a-z0-9\s-]/g, ' ').trim();

  // replace multiple spaces or hyphens with a single hyphen
  slug = slug.replace(/[\s-]+/g, '-');

  return slug;
}

// Parsley custom validation
window.Parsley.addValidator('validateSlug', {
  validateString: function(value, requirement, instance) {
    var $form = instance.parent.$element;
    var excludeId = $form.attr('data-post-id') || '';
    var data = {
        slug: value,
        excludeId: excludeId
    };
    var xhr = $.post(Router.generate('app_admin_insights_check_slug'), data);
    return xhr.then(function(json, requirement) {
        if(json.slug_exists) {
            return $.Deferred().reject("Slug already exists");
        }
    })
  },
  // The following error message will still show if the xhr itself fails
  // (404 because zip does not exist, network error, etc.)
  messages: {en: 'Slug already exists.'}
});

$(function() {
    $('#btn_slugify').on('click', function(e) {
      e.preventDefault();
      var title = $('#title').val();
      var slug = slugify(title);
      $('#slug').val(slug);
    });

    $('[maxlength]').maxlength();
    autosize($('#summary'));
    $('#content').summernote({
        height: 340,
        toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'underline', 'clear']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['table', ['table']],
            ['insert', ['link', 'picture']],
            ['view', ['fullscreen', 'codeview']]
          ],
          callbacks: {
            onImageUpload: function(files) {
              var formData = new FormData();
              formData.append('file', files[0]);
              $.ajax({
                data: formData,
                type: "POST",
                url: "/upload-files?&blurhash=1&filter=post_lg,post_md,post_sm",
                cache: false,
                processData: false,
                contentType: false,
                beforeSend: function() {
                  $('#content_spinner').removeClass('d-none');
                },
                success: function(r) {
                    var response = JSON.parse(r);
                    var image = $('<img>').attr({
                      'src': response.relative_url,
                      'data-file-id': response.id,
                      'data-file-synced': false,
                      'data-lazy-load': true,
                      'style': 'width: 100%'
                    });
                    $('#content').summernote("insertNode", image[0]);
                    $('#content_spinner').addClass('d-none');
                }
              });
            },
            onMediaDelete: function(target) {
              var fileId = $(target[0]).attr('data-file-id');
              if (fileId === undefined) {
                return;
              }
              $.ajax({
                type: 'DELETE',
                url: Router.generate('app_upload_files_delete', {'id': fileId}),
                dataType: 'json'
              });
            }
          }
    });

    $('input[name="typeId"]').on('change', function(e) {
        var $this = $(this);
        var label = $this.data('label');
        var $wpContainer = $('#whitepaper_container');

        if (label === 'Whitepaper') {
            $wpContainer.removeClass('d-none');
            $wpContainer.find('label.control-label').addClass('required');
            $wpContainer.find("#file_id").prop('required', true);
        } else {
            $wpContainer.addClass('d-none');
            $wpContainer.find('label.control-label').removeClass('required');
            $wpContainer.find("#file_id").prop('required', false);
        }
    });

    var $headerImagePbar = $('#header_image_pb');
    $("#header_file").liteUploader({
        url: "/upload-files?&blurhash=1&filter=header_xlg,header_sm",
        ref: 'file'
    })
    .on("lu:before", function (e, {files}) {
      Array.prototype.forEach.call(files, function (file) {
        const reader = new FileReader();
  
        reader.onload = function (e) {
          const image = document.querySelector("#hero_image");
          image.src = e.target.result;
        };
  
        reader.readAsDataURL(file);
      });
    })
    .on("lu:progress", function (e, {percentage}) {
      $headerImagePbar
        .css("width", percentage + "%")
        .attr("aria-valuenow", percentage)
        .text(percentage + "%");
    })
    .on("lu:success", function (e, {response}) {
      var data = JSON.parse(response);
      $('#header_image_id').val(data.id);
    });
  
    $("#header_file").on('change', function () {
      $(this).data("liteUploader").startUpload();
    });

    var $wpFilePbar = $('#file_pb');
    $("#whitepaper_file").liteUploader({
        url: "/upload-files",
        ref: 'file'
    })
    .on("lu:progress", function (e, {percentage}) {
      $wpFilePbar
        .css("width", percentage + "%")
        .attr("aria-valuenow", percentage)
        .text(percentage + "%");
    })
    .on("lu:success", function (e, {response}) {
      var data = JSON.parse(response);
      $('#file_id').val(data.id);
      var $filenameText = $('#whitepaper_container').find('.original_name')
      if ($filenameText.length > 0) {
        $filenameText.text(data.original_name);
      }
      $.post('/upload-files/'+ data.id +'/generate-cover', function(res) {
        $('#thumbnail_id').val(res.id);
      });
    });
  
    $("#whitepaper_file").on('change', function () {
      $(this).data("liteUploader").startUpload();
    });

    $('textarea').on('autosize:resized', function() {
      $(this).trigger('maxlength.reposition');
    });

    $('#btn_draft').on('click', function(e) {
      e.preventDefault();
      var $form = $(this).closest('form');
      $form.attr('target', '_blank');

      $('#status').val(0);
      $form.trigger('submit');
    });
    $('#btn_publish').on('click', function(e) {
      e.preventDefault();
      var $form = $(this).closest('form');
      $form.removeAttr('target');

      $('#status').val(1);
      $form.trigger('submit');
    });

    if ($('#file_id').val()) {
      $('input[name="typeId"]').trigger('change');
    }
});