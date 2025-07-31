/**
 * @license Copyright (c) 2003-2023, CKSource Holding sp. z o.o. All rights reserved.
 * For licensing, see https://ckeditor.com/legal/ckeditor-oss-license
 */

CKEDITOR.editorConfig = function( config ) {
    config.filebrowserBrowseUrl = '/cms/ckeditor/open-popup'; // Keep your file browser URL
    config.skin = 'office2013';
    config.allowedContent = true; // Allow all content (no filtering)
    config.removeDialogTabs = ''; // Ensure all dialog tabs are available

    // Comprehensive toolbar configuration for "full option" CKEditor
    // config.toolbar = [
    //     [
    //         "Cut",
    //         "Copy",
    //         "-",
    //         "Undo",
    //         "Redo",
    //         "-",
    //         "Link",
    //         "Unlink",
    //         "-",
    //         "CopyFormatting",
    //         "RemoveFormat",
    //         "-",
    //         "Source",
    //         "-", // Dấu phân cách
    //         "CreateDiv", // Tạo div
    //         "-", // Dấu phân cách
    //         "Table", // Bảng
    //         "-", // Dấu phân cách
    //         "ShowBlocks", // Hiển thị khối
    //         "-", // Dấu phân cách
    //         "Maximize", // Tăng cỡ,
    //         "Picture"
    //     ],
    //     "/",
    //     [
    //         "Bold",
    //         "Italic",
    //         "Underline",
    //         "Strike",
    //         "-",
    //         "TextColor",
    //         "BGColor",
    //         "-",
    //         "NumberedList",
    //         "BulletedList",
    //         "-",
    //         "Outdent",
    //         "Indent",
    //         "-",
    //         "Blockquote",
    //         "HorizontalRule",
    //         "-",
    //         "JustifyLeft",
    //         "JustifyCenter",
    //         "JustifyRight",
    //         "JustifyBlock",
    //         "-",
    //         "Format", // Thêm nút cho Format
    //         "Image", // Thêm nút cho Format
    //     ],
    //
    // ];
    // Enable extra plugins for full functionality
    config.extraPlugins = 'image, justify';

    // Remove only the magicline plugin as per your original config
    config.removePlugins = 'sourcearea';

    // Optional: Set height and width for better editor experience
    config.height = 400;
    config.width = '100%';

    // Optional: Enable auto-grow for dynamic height adjustment
    config.autoGrow_onStartup = true;
    config.autoGrow_minHeight = 200;
    config.autoGrow_maxHeight = 600;
};