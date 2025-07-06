import { Controller } from '@hotwired/stimulus';
import axios from 'axios';
/*
 * This is an example Stimulus controller!
 *
 * Any element with a data-controller="hello" attribute will cause
 * this controller to be executed. The name "hello" comes from the filename:
 *
 * Delete this file or adapt it for your use!
 */
export default class extends Controller {
    static targets = [];
    static values = {
        placeholder: {
            default: "--Select option--",
            type: String
        },
        url: {
            default: null,
            type: String,
        },
        changeProperty: {
            default: null,
            type: String
        },
    }
    connect() {
         $(this.element).select2({
            placeholder: this.placeholderValue
        });
        $(this.element).on('select2:select', this.onChange.bind(this));
    }

    initialize() {
        super.initialize();
    }

    disconnect() {
        super.disconnect();
        $(this.element).select2('destroy');
        $(this.element).off('select2:select', this.onChange.bind(this));
    }

    async onChange(e){
        const selectedVal = $(e).val();
        if(this.urlValue === null) return;

        const response = await axios.post(this.urlValue)
    }
}
