/**
 * @license Copyright (c) 2003-2016, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';
	config.language_list = [ 'en:English:rtl', 'pt-br:Portuguese Brazil', 'si:Spanish' ];
	
	config.extraPlugins = 'embedbase'; 
	config.extraPlugins = 'embed'; 
	config.extraPlugins = 'html5audio'; 
	
	config.extraPlugins = 'simpleuploads'; 
	

config.filebrowserImageBrowseUrl = '/js/ckf/ckfinder.html?type=Images';
config.filebrowserImageUploadUrl = '/js/ckf/core/connector/php/connector.php?command=QuickUpload&type=Images';
config.filebrowserUploadUrl = '/js/ckf/core/connector/php/connector.php?command=QuickUpload&type=Files';
};
