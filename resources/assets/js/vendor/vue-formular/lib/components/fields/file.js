var merge = require('merge');
var clone = require('clone');
var Field = require('./field');

module.exports = function () {
    return merge.recursive(Field(), {
        data: function () {
            return {
                fieldType: 'file',
                uploaded: false
            }
        },
        props: {
            options: {
                type: Object,
                required: false,
                default: function () {
                    return {};
                }
            },
            name: {
                required: true
            },
            ajax: {
                type: Boolean
            },
            dest: {
                type: String,
                default: '/'
            },
            done: {
                type: Function
            },
            error: {
                type: Function
            },
            valueKey: {
                type: String,
                default: 'value'
            }
        },

        ready: function () {

            if (!this.ajax) return;

            let self = this,
                parentOptions = this.inForm() ? clone(this.getForm().options.fileOptions) : {},
                options = merge.recursive(parentOptions, this.options);

            if (!options.hasOwnProperty("formData")) options.formData = {};

            options.formData.rules = JSON.stringify(this.rules);

            if (!options.formData.hasOwnProperty('dest')) {
                options.formData.dest = this.dest;
            }

            if (!options.hasOwnProperty('done')) {
                options.done = this.done ? this.done : function (e, data) {

                    //Бутона
                    this.getForm().sending = false;
                    this.uploaded = true;
                    this.setValue(data.result[self.valueKey]);
                }.bind(this)
            }

            if (!options.hasOwnProperty('error')) {
                options.error = this.error ? this.error : function (e, data) {
                    //Бутона
                    self.getForm().sending = false;

                    /**
                     * Имаме грешка при изпращане на файла
                     *
                     */
                    self.$dispatch('vue-formular.invalid.server', {
                        data: [e.responseJSON.error],
                        ok: false,
                        status: e.status,
                        statusText: e.statusText,
                        url : e.responseJSON.url
                    })
                }
            }
            let theFile = $(this.$el).find("input[type=file]"),
                progressElement = theFile.next('.progress'),
                progressBar = progressElement.find('.progress-bar'),
                fileList = progressElement.find(`.file_${this.name}`);

            theFile.fileupload(options);

            theFile.on('fileuploadadd', function (e, data) {
                //this = input

                self.$dispatch('sent-errors.clear', {});
                //За да disable-м бутона
                self.getForm().sending = true;
                data.context = $('<div/>').appendTo(fileList);

                $.each(data.files, function (index, file) {
                    let node = $('<p/>')
                        .append($('<span/>').text(file.name));
                    if (!index) {
                        node
                            .append('<br>')
                    }
                    node.appendTo(data.context);
                });
            }).on('fileuploadprocessalways', function (e, data) {
                let index = data.index,
                    file = data.files[index],
                    node = $(data.context.children()[index]);
                // if (file.preview) {
                //     node
                //         .prepend('<br>')
                //         .prepend(file.preview);
                // }
                if (file.error) {
                    node
                        .append('<br>')
                        .append($('<span class="text-danger"/>').text(file.error));
                }
                // if (index + 1 === data.files.length) {
                //     data.context.find('button')
                //         .text('Upload')
                //         .prop('disabled', !!data.files.error);
                // }
            }).on('fileuploadprogressall', function (e, data) {

                // let progressBar = $(this).next('.progress').find('.progress-bar');

                let progress = parseInt(data.loaded / data.total * 100, 10);
                progressBar.css('width', progress + '%');
            }).on('fileuploadfail', function (e, data) {

                progressBar.css('width', '0');
                fileList.empty();
            })

/*
            $(function () {
                'use strict';
                // Change this to the location of your server-side upload handler:
                var url = window.location.hostname === 'blueimp.github.io' ?
                        '//jquery-file-upload.appspot.com/' : 'server/php/';
                    // uploadButton = $('<button/>')
                    //     .addClass('btn btn-primary')
                    //     .prop('disabled', true)
                    //     .text('Processing...')
                    //     .on('click', function () {
                    //         var $this = $(this),
                    //             data = $this.data();
                    //         $this
                    //             .off('click')
                    //             .text('Abort')
                    //             .on('click', function () {
                    //                 $this.remove();
                    //                 data.abort();
                    //             });
                    //         data.submit().always(function () {
                    //             $this.remove();
                    //         });
                    //     });
                $('#fileupload').fileupload({
                    url: url,
                    dataType: 'json',
                    autoUpload: false,
                    acceptFileTypes: /(\.|\/)(gif|jpe?g|png)$/i,
                    maxFileSize: 999000,
                    // Enable image resizing, except for Android and Opera,
                    // which actually support image resizing, but fail to
                    // send Blob objects via XHR requests:
                    disableImageResize: /Android(?!.*Chrome)|Opera/
                        .test(window.navigator.userAgent),
                    previewMaxWidth: 100,
                    previewMaxHeight: 100,
                    previewCrop: true
                }).on('fileuploadadd', function (e, data) {
                    data.context = $('<div/>').appendTo('#files');
                    $.each(data.files, function (index, file) {
                        var node = $('<p/>')
                            .append($('<span/>').text(file.name));
                        if (!index) {
                            node
                                .append('<br>')
                                .append(uploadButton.clone(true).data(data));
                        }
                        node.appendTo(data.context);
                    });
                }).on('fileuploadprocessalways', function (e, data) {
                    var index = data.index,
                        file = data.files[index],
                        node = $(data.context.children()[index]);
                    if (file.preview) {
                        node
                            .prepend('<br>')
                            .prepend(file.preview);
                    }
                    if (file.error) {
                        node
                            .append('<br>')
                            .append($('<span class="text-danger"/>').text(file.error));
                    }
                    if (index + 1 === data.files.length) {
                        data.context.find('button')
                            .text('Upload')
                            .prop('disabled', !!data.files.error);
                    }
                }).on('fileuploadprogressall', function (e, data) {
                    var progress = parseInt(data.loaded / data.total * 100, 10);
                    $('#progress .progress-bar').css(
                        'width',
                        progress + '%'
                    );
                }).on('fileuploaddone', function (e, data) {
                    $.each(data.result.files, function (index, file) {
                        if (file.url) {
                            var link = $('<a>')
                                .attr('target', '_blank')
                                .prop('href', file.url);
                            $(data.context.children()[index])
                                .wrap(link);
                        } else if (file.error) {
                            var error = $('<span class="text-danger"/>').text(file.error);
                            $(data.context.children()[index])
                                .append('<br>')
                                .append(error);
                        }
                    });
                }).on('fileuploadfail', function (e, data) {
                    $.each(data.files, function (index) {
                        var error = $('<span class="text-danger"/>').text('File upload failed.');
                        $(data.context.children()[index])
                            .append('<br>')
                            .append(error);
                    });
                }).prop('disabled', !$.support.fileInput)
                    .parent().addClass($.support.fileInput ? undefined : 'disabled');
            });


*/
        }
    });
}


