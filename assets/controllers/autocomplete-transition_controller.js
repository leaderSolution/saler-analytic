import { Controller } from 'stimulus';
import { addFadeTransition } from '../util/add-transition';

export default class extends Controller {
    static targets = ['email', 'pickedEmail', 'results'];

    connect() {
        
    }
    
    
    fillInput(e){
        e.preventDefault();
        this.pickedEmailTarget.value = e.currentTarget.innerText;
        document.getElementById('listClt').innerHTML = ''
        
    }
}
