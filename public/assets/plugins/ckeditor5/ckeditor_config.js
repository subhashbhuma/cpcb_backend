import {
    ClassicEditor,
    Alignment,
    Autoformat,
    AutoImage,
    AutoLink,
    Autosave,
    BalloonToolbar,
    Base64UploadAdapter,
    BlockQuote,
    BlockToolbar,
    Bold,
    Bookmark,
    Code,
    CodeBlock,
    Emoji,
    Essentials,
    FindAndReplace,
    FontBackgroundColor,
    FontColor,
    FontFamily,
    FontSize,
    FullPage,
    GeneralHtmlSupport,
    Heading,
    Highlight,
    HorizontalLine,
    HtmlComment,
    ImageBlock,
    ImageCaption,
    ImageEditing,
    ImageInline,
    ImageInsert,
    ImageInsertViaUrl,
    ImageResize,
    ImageStyle,
    ImageTextAlternative,
    ImageToolbar,
    ImageUpload,
    ImageUtils,
    Indent,
    IndentBlock,
    Italic,
    Link,
    LinkImage,
    List,
    ListProperties,
    MediaEmbed,
    Mention,
    PageBreak,
    Paragraph,
    PasteFromOffice,
    RemoveFormat,
    ShowBlocks,
    SourceEditing,
    SpecialCharacters,
    SpecialCharactersArrows,
    SpecialCharactersCurrency,
    SpecialCharactersEssentials,
    SpecialCharactersLatin,
    SpecialCharactersMathematical,
    SpecialCharactersText,
    Strikethrough,
    Style,
    Subscript,
    Superscript,
    Table,
    TableCaption,
    TableCellProperties,
    TableColumnResize,
    TableProperties,
    TableToolbar,
    TextPartLanguage,
    TextTransformation,
    Title,
    TodoList,
    Underline,
    WordCount,
    HtmlEmbed,
} from "ckeditor5";

const LICENSE_KEY = "GPL";

const editorConfig = {
    licenseKey: LICENSE_KEY,

    toolbar: {
        items: [
            "htmlEmbed",
            "sourceEditing",
            "showBlocks",
            "findAndReplace",
            "textPartLanguage",
            "|",
            "heading",
            "style",
            "|",
            "fontSize",
            "fontFamily",
            "fontColor",
            "fontBackgroundColor",
            "|",
            "bold",
            "italic",
            "underline",
            "strikethrough",
            "subscript",
            "superscript",
            "code",
            "removeFormat",
            "|",
            "emoji",
            "specialCharacters",
            "horizontalLine",
            "pageBreak",
            "link",
            "bookmark",
            "insertImage",
            "insertImageViaUrl",
            "mediaEmbed",
            "insertTable",
            "highlight",
            "blockQuote",
            "codeBlock",
            "|",
            "alignment",
            "|",
            "bulletedList",
            "numberedList",
            "todoList",
            "outdent",
            "indent",
            "|",
            "undo",
            "redo",
        ],
        shouldNotGroupWhenFull: true,
    },

    plugins: [
        Alignment,
        Autoformat,
        AutoImage,
        AutoLink,
        Autosave,
        BalloonToolbar,
        Base64UploadAdapter,
        BlockQuote,
        BlockToolbar,
        Bold,
        Bookmark,
        Code,
        CodeBlock,
        Emoji,
        Essentials,
        FindAndReplace,
        FontBackgroundColor,
        FontColor,
        FontFamily,
        FontSize,
        FullPage,
        GeneralHtmlSupport,
        Heading,
        Highlight,
        HorizontalLine,
        HtmlComment,
        ImageBlock,
        ImageCaption,
        ImageEditing,
        ImageInline,
        ImageInsert,
        ImageInsertViaUrl,
        ImageResize,
        ImageStyle,
        ImageTextAlternative,
        ImageToolbar,
        ImageUpload,
        ImageUtils,
        Indent,
        IndentBlock,
        Italic,
        Link,
        LinkImage,
        List,
        ListProperties,
        MediaEmbed,
        Mention,
        PageBreak,
        Paragraph,
        PasteFromOffice,
        RemoveFormat,
        ShowBlocks,
        SourceEditing,
        SpecialCharacters,
        SpecialCharactersArrows,
        SpecialCharactersCurrency,
        SpecialCharactersEssentials,
        SpecialCharactersLatin,
        SpecialCharactersMathematical,
        SpecialCharactersText,
        Strikethrough,
        Style,
        Subscript,
        Superscript,
        Table,
        TableCaption,
        TableCellProperties,
        TableColumnResize,
        TableProperties,
        TableToolbar,
        TextPartLanguage,
        TextTransformation,
        Title,
        TodoList,
        Underline,
        WordCount,
    ],

    htmlSupport: {
        allow: [
            {
                name: /.*/,
                attributes: true,
                classes: true,
                styles: true,
            },
        ],
    },

    htmlEmbed: {
        showPreviews: true,
        sanitizeHtml: (inputHtml) => {
            return { html: inputHtml, hasChanged: false };
        },
    },

    fullPage: {
        allowRenderStylesFromHead: true,
    },

    fontFamily: {
        supportAllValues: true,
    },

    fontSize: {
        options: [10, 12, 14, "default", 18, 20, 22],
        supportAllValues: true,
    },

    heading: {
        options: [
            {
                model: "paragraph",
                title: "Paragraph",
                class: "ck-heading_paragraph",
            },
            {
                model: "heading1",
                view: "h1",
                title: "Heading 1",
                class: "ck-heading_heading1",
            },
            {
                model: "heading2",
                view: "h2",
                title: "Heading 2",
                class: "ck-heading_heading2",
            },
            {
                model: "heading3",
                view: "h3",
                title: "Heading 3",
                class: "ck-heading_heading3",
            },
            {
                model: "heading4",
                view: "h4",
                title: "Heading 4",
                class: "ck-heading_heading4",
            },
            {
                model: "heading5",
                view: "h5",
                title: "Heading 5",
                class: "ck-heading_heading5",
            },
            {
                model: "heading6",
                view: "h6",
                title: "Heading 6",
                class: "ck-heading_heading6",
            },
        ],
    },

    image: {
        toolbar: [
            "toggleImageCaption",
            "imageTextAlternative",
            "|",
            "imageStyle:inline",
            "imageStyle:wrapText",
            "imageStyle:breakText",
            "|",
            "resizeImage",
        ],
    },

    link: {
        addTargetToExternalLinks: true,
        defaultProtocol: "https://",
    },

    list: {
        properties: {
            styles: true,
            startIndex: true,
            reversed: true,
        },
    },

    menuBar: {
        isVisible: false,
    },

    placeholder: "Type or paste your content here!",

    table: {
        contentToolbar: [
            "tableColumn",
            "tableRow",
            "mergeTableCells",
            "tableProperties",
            "tableCellProperties",
        ],
    },

    removePlugins: ["Title"],
};

window.editors = [];
$(document).ready(function () {
    // Check if the element exists
    if ($("#page-editor").length) {
        // Loop through each element with the class 'custom-editor'
        $("#page-editor").each(function (index) {
            ClassicEditor.create(this, editorConfig)
                .then((editor) => {
                    window.editors.push({
                        editor,
                        name: $(this).attr("name"),
                        id: $(this).attr("id"),
                    });

                    // Set the height dynamically or perform other operations
                    editor.editing.view.change((writer) => {
                        writer.setStyle(
                            "min-height",
                            "300px",
                            editor.editing.view.document.getRoot(),
                        );
                        writer.setStyle(
                            "max-height",
                            "400px",
                            editor.editing.view.document.getRoot(),
                        );
                    });

                    if ($("#page-editor").val() !== "") {
                        editor.data.set($("#page-editor").val());
                    }
                })
                .catch((error) => {
                    console.error(error);
                });
        });
    }

    // Check if the element exists
    if ($("#hi-page-editor").length) {
        // Loop through each element with the class 'custom-editor'
        $("#hi-page-editor").each(function (index) {
            ClassicEditor.create(this, editorConfig)
                .then((editor) => {
                    window.editors.push({
                        editor,
                        name: $(this).attr("name"),
                        id: $(this).attr("id"),
                    });

                    // Set the height dynamically or perform other operations
                    editor.editing.view.change((writer) => {
                        writer.setStyle(
                            "min-height",
                            "300px",
                            editor.editing.view.document.getRoot(),
                        );
                        writer.setStyle(
                            "max-height",
                            "400px",
                            editor.editing.view.document.getRoot(),
                        );
                    });

                    if ($("#hi-page-editor").val() !== "") {
                        editor.data.set($("#hi-page-editor").val());
                    }
                })
                .catch((error) => {
                    console.error(error);
                });
        });
    }
});
