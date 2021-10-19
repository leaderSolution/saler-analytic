import { Controller } from 'stimulus';
import { Modal } from 'bootstrap';
import { useDispatch } from 'stimulus-use';


export default class extends Controller {
    static targets = ['modal', 'modalBody'];
    static values = {
        formUrl: String,
    }
    modal = null;

    async openModal(event) {
        console.log(this.formUrlValue);
        this.modalBodyTarget.innerHTML = 'Loading...';
        this.modal = new Modal(this.modalTarget);
        console.log($(this.modalBodyTarget).find('form'))
        this.modal.show();

        this.modalBodyTarget.innerHTML = await $.ajax(this.formUrlValue);
    }
    connect() {
        useDispatch(this, {debug: true});
    }
    async submitForm(event) {
        event.preventDefault();
        const $form = $(this.modalBodyTarget).find('form');
        try {
            this.modalBodyTarget.innerHTML = await $.ajax({
            url: this.formUrlValue,
            method: $form.prop('method'),
            data: $form.serialize(),
        });
            
            this.modal.hide();
            window.location.reload();
            this.dispatch('success');

        }catch(e){
            this.modalBodyTarget.innerHTML = e.responseText;
        }
    }

    modalHidden() {
        console.log('it was hidden!');
    }
}
