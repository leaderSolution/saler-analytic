// Use a global for the submit and return data rendering in the examples.
// Don't do this outside of the Editor examples!
let editor;

// Display an Editor form that allows the user to pick the CSV data to apply to each column
function selectColumns ( editor, csv, header ) {
    let selectEditor = new $.fn.dataTable.Editor();
    let fields = editor.order();

    for ( let i=0 ; i<fields.length ; i++ ) {
        let field = editor.field( fields[i] );

        selectEditor.add( {
            label: field.label(),
            name: field.name(),
            type: 'select',
            options: header,
            def: header[i]
        } );
    }

    selectEditor.create({
        title: 'Map CSV fields',
        buttons: 'Import '+csv.length+' records',
        message: 'Select the CSV column you want to use the data from for each field.',
        onComplete: 'none'
    });

    selectEditor.on('submitComplete', function (e, json, data, action) {
        // Use the host Editor instance to show a multi-row create form allowing the user to submit the data.
        editor.create( csv.length, {
            title: 'Confirm import',
            buttons: 'Submit',
            message: 'Click the <i>Submit</i> button to confirm the import of '+csv.length+' rows of data. Optionally, override the value for a field to set a common value by clicking on the field below.'
        } );

        for ( let i=0 ; i<fields.length ; i++ ) {
            let field = editor.field( fields[i] );
            let mapped = data[ field.name() ];

            for ( let j=0 ; j<csv.length ; j++ ) {
                field.multiSet( j, csv[j][mapped] );
            }
        }
    } );
}

$(document).ready(function() {
    // Regular editor for the table
    editor = new $.fn.dataTable.Editor( {
        ajax: "../php/staff.php",
        table: "#example",
        fields: [ {
            label: "Designation:",
            name: "designation"
        }, {
            label: "Region:",
            name: "region"
        }
        ]
    } );

    $('#example').DataTable( {
        dom: 'Bfrtip',
        ajax: "../php/staff.php",
        columns: [
            { data: 'designation' },
            { data: 'region' },

           //{ data: "salary", render: $.fn.dataTable.render.number( ',', '.', 0, '$' ) }
        ],
        select: true,
        buttons: [
            { extend: 'create', editor: editor },
            { extend: 'edit',   editor: editor },
            { extend: 'remove', editor: editor },
            {
                extend: 'csv',
                text: 'Export CSV',
                className: 'btn-space',
                exportOptions: {
                    orthogonal: null
                }
            },
            {
                extend: 'selectAll',
                className: 'btn-space'
            },
            'selectNone',
        ]
    } );
} );