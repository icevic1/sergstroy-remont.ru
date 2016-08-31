/**
 * @license Copyright (c) 2003-2013, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.html or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';
	//config.skins = 'BootstrapCK-Skin';
	CKEDITOR.config.enterMode = CKEDITOR.ENTER_BR;
	CKEDITOR.dtd.$removeEmpty['i'] = false;
	CKEDITOR.autoParagraph = false;
	CKEDITOR.fillEmptyBlocks = false;
	CKEDITOR.enterMode = CKEDITOR.ENTER_BR;
	CKEDITOR.shiftEnterMode = CKEDITOR.ENTER_P;
	CKEDITOR.allowedContent = true; // don't filter my data
	extraAllowedContent: 'br(*)';
	//config.allowedContent = true; // don't filter my data
	//extraAllowedContent: 'br(*)';
};
