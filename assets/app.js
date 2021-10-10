/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.scss';
import './styles/login.css'
import 'datatables.net'
import 'datatables.net-bs5'
// You can specify which plugins you need
import 'bootstrap';
require('bootstrap-icons/font/bootstrap-icons.css');
require('jquery-confirm')
import 'bootstrap-autocomplete'
// start the Stimulus application
import './bootstrap';

$(document).ready(function() {

   // Setup - add a text input to each footer cell
    $('#example thead tr').clone(true).appendTo( '#example thead' );

    $('#example thead tr:eq(1) th').each( function (i) {
        var title = $(this).text();
        
        if(title.toUpperCase() !== "ACTIONS"){
            $(this).html( '<input class="form-control" type="text" placeholder="Search '+title+'" />' );
            $( 'input', this ).on( 'keyup change', function () {
                if ( table.column(i).search() !== this.value ) {
                    table
                        .column(i)
                        .search( this.value )
                        .draw();
                }
            } );
        }else {
            
            $(this).empty()
        }
        
        
 
        
    } );
   
    
 
    var table = $('#example').DataTable( {
        orderCellsTop: true,
        fixedHeader: true
    } );
    var checkBoxs = document.getElementsByClassName("visitActive");
    console.log(checkBoxs)
    for(var i=0; i < checkBoxs.length; i++){
        console.log(checkBoxs[i].value)
        $('#'+checkBoxs[i].value ).on('click', (e) => {
            let id = e.target.value
            if(e.target.checked){
                activate('/admin/verify-visit', {isActive: 1, visitID: id  })
            }else {
                activate('/admin/verify-visit', {isActive: 0, visitID: id })
            }
        });
        
    }
   
   
  
    function activate(url, data){
        $.post(url, data)
        .done(function (data) {
          $.alert('Done !!')
        })
        .fail(function() {
          $.alert( "error" );
        })
        .always(function() {
          setTimeout(function(){location.reload()}, 2000);
        });
    }

} );


