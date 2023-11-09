/**
 * @license Copyright (c) 2003-2012, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.html or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	config.toolbar = [
	{ name: 'document', groups: [ 'mode', 'document', 'doctools' ], items: [ 'Source', '-', 'Save', 'NewPage', 'Preview', 'Print', '-', 'Templates' ] },
	{ name: 'clipboard', groups: [ 'clipboard', 'undo' ], items: [ 'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo' ] },
	{ name: 'editing', groups: [ 'find', 'selection', 'spellchecker' ], items: [ 'Find', 'Replace', '-', 'SelectAll', '-', 'Scayt' ] },
	{ name: 'forms', items: [ 'Form', 'Checkbox', 'Radio', 'TextField', 'Textarea', 'Select', 'Button', 'ImageButton', 'HiddenField' ] },
	
	{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ], items: [ 'Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'CopyFormatting', 'RemoveFormat' ] },
	{ name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi' ], items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', 'CreateDiv', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', 'BidiLtr', 'BidiRtl', 'Language' ] },
	{ name: 'links', items: [ 'Link', 'Unlink', 'Anchor' ] },
	{ name: 'insert', items: [ 'Image', 'Flash', 'Table', 'HorizontalRule', 'Smiley', 'SpecialChar', 'PageBreak', 'Iframe' ] },
	
	{ name: 'styles', items: [ 'Styles', 'Format', 'Font', 'FontSize' ] },
	{ name: 'colors', items: [ 'TextColor', 'BGColor' ] },
	{ name: 'tools', items: [ 'Maximize', 'ShowBlocks'] },
	{ name: 'others', items: [ '-' ] },
	{ name: 'about', items: [ 'btgrid' ] }
];

	config.extraPlugins = 'widgetselection';
	config.extraPlugins = 'lineutils';
	config.extraPlugins = 'widget';
	config.extraPlugins = 'btgrid';
	config.height = 500; 
	config.removeButtons = 'Save,NewPage,Form,Checkbox,Radio,TextField,Textarea,Select,Button,ImageButton,HiddenField,Language,BidiRtl,BidiLtr,PageBreak,Flash,About,Preview,Print,Smiley';
	config.protectedSource.push(/<(style)[^>]*>.*<\/style>/ig);
	config.protectedSource.push(/<(script)[^>]*>.*<\/script>/ig); 
	config.protectedSource.push(/<i[^>]*>.*<\/i>/g);
	
	config.protectedSource.push(/<header[^>]*><\/header>/g);
	config.protectedSource.push(/<footer[^>]*><\/footer>/g);
	config.protectedSource.push(/<section[^>]*><\/section>/g);
	config.protectedSource.push(/<article[^>]*><\/article>/g);
	config.protectedSource.push(/<aside[^>]*><\/aside>/g);
	config.allowedContent = true;
};