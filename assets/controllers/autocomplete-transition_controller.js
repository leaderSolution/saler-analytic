import { Controller } from 'stimulus';
import { addFadeTransition } from '../util/add-transition';

export default class extends Controller {
    static targets = ['email', 'pickedEmail', 'results'];

    connect() {
        
    }
    
    
    fillInput(e){
        e.preventDefault();
        let innerTextLi = e.currentTarget.innerText
        this.pickedEmailTarget.value = innerTextLi.substr(0, innerTextLi.indexOf(',')); ;
        document.getElementById('listClt').innerHTML = ''
        
    }
}
