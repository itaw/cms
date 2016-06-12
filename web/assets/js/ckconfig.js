CKEDITOR.editorConfig = function( config ) {
    config.entites = false;
    config.basicEntities = false;
    config.protectedSource.push(/\{\{\s.+\s\}\}/g);
    config.protectedSource.push(/\{%\s.+\s%\}/g);
};
