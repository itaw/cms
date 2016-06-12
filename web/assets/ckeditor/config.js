/**
 * @license Copyright (c) 2003-2015, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
    config.entites = false;
    config.basicEntities = false;
    config.protectedSource.push(/\{\{\s.+\s\}\}/g);
    config.protectedSource.push(/\{%\s.+\s%\}/g);

    //remove buttons
    config.removeButtons = 'Save,NewPage,Preview,Print,Templates';
};
