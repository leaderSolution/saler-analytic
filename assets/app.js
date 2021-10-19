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

   let i;
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
    for(i = 0; i < checkBoxs.length; i++){
        console.log(checkBoxs[i].value)
        $('#'+checkBoxs[i].value ).on('click', (e) => {
            let id = e.target.value
            if(e.target.checked){
                sendRequest('/admin/verify-visit', {isActive: 1, visitID: id  })
            }else {
                sendRequest('/admin/verify-visit', {isActive: 0, visitID: id })
            }
        });
        
    }
    var buttons = document.getElementsByClassName("deleteVisit");

    for(i = 0; i < buttons.length; i++){
        console.log(buttons[i])
        $('#'+buttons[i].id ).on('click', (e) => {
            let id = e.target.id
           console.log(id)
            $.confirm({
                buttons: {
                    cancel: function () {
                        // here the button key 'hey' will be used as the text.
                        $.alert('Cancelled !".');
                    },

                    danger: {
                        text: 'Delete',
                        btnClass: 'btn-red',
                        action: function () {
                            sendRequest('/delete-visit', { visitID: id  })
                        }
                    }
                }
            });

        });

    }

    const addGoalFormDeleteLink = (goalFormTr) => {
        const removeFormButton = document.createElement('i')
        removeFormButton.classList.add('bi','bi-trash','fs-5')
        removeFormButton.innerText

        goalFormTr.append(removeFormButton);

        removeFormButton.addEventListener('click', (e) => {
            e.preventDefault()
            // remove the li for the tag form
            goalFormTr.remove();
        });
    }

    const addFormToCollection = (e) => {
        const collectionHolder = document.querySelector('.' + e.currentTarget.dataset.collectionHolderClass);

        const item = document.createElement('tr');
        console.log(item)
        item.innerHTML = collectionHolder
            .dataset
            .prototype
            .replace(
                /__name__/g,
                collectionHolder.dataset.index
            );

        collectionHolder.appendChild(item);

        collectionHolder.dataset.index++;
        // add a delete link to the new form
        addGoalFormDeleteLink(item);
    };
    const addBtns = document.getElementsByClassName('add_item_link');
    const goals = document.querySelectorAll('tr.goal_item')

    goals.forEach((goal) => {
        addGoalFormDeleteLink(goal)
    })

    for(let i = 0; i < addBtns.length; i++){
        console.log(addBtns[i])
        addBtns[i].addEventListener("click", addFormToCollection);

    }


    function sendRequest(url, data){
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


