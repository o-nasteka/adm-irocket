/**
 * @license Copyright (c) 2003-2015, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	config.language = 'ru';
	// config.uiColor = '#AADC6E';
    config.height = '350px';
    CKEDITOR.disableAutoInline = true;

    // automatically remove formating
    CKEDITOR.config.forcePasteAsPlainText = true;
    CKEDITOR.config.pasteFromWordRemoveStyles = true;
    CKEDITOR.config.pasteFromWordRemoveFontStyles = true;

    // при нажатии enter добавляем br
    config.enterMode = CKEDITOR.ENTER_BR;
    config.shiftEnterMode = CKEDITOR.ENTER_BR;

    // отключить  Advanced Content Filter – Automatic Mode //
    config.allowedContent = true;
    // отключить  Advanced Content Filter – Automatic Mode //

    // accept all div classes
    config.extraAllowedContent = 'div(*)';
    config.extraAllowedContent = 'span(*)';
    config.extraAllowedContent = 'onclick(*)';
    config.extraAllowedContent = 'a href(*)';

    config.protectedSource.push( /<script[\s\S]*?script>/g ); /* script tags */

    config.protectedSource.push(/<[a-z]*[a-z\s\=\"\']*><\/[\s\S][^/]*?>/g); /* all */

    CKEDITOR.dtd.$removeEmpty.span = 0;


    config.filebrowserBrowseUrl = '/ckeditor/kcfinder/browse.php?opener=ckeditor&type=files';
    config.filebrowserImageBrowseUrl = '/ckeditor/kcfinder/browse.php?opener=ckeditor&type=images';
    config.filebrowserFlashBrowseUrl = '/ckeditor/kcfinder/browse.php?opener=ckeditor&type=flash';
    config.filebrowserUploadUrl = '/ckeditor/kcfinder/upload.php?opener=ckeditor&type=files';
    config.filebrowserImageUploadUrl = '/ckeditor/kcfinder/upload.php?opener=ckeditor&type=images';
    config.filebrowserFlashUploadUrl = '/ckeditor/kcfinder/upload.php?opener=ckeditor&type=flash';



};
