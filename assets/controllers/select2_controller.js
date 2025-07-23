import { Controller } from '@hotwired/stimulus';
import axios from 'axios';
/*
* The following line makes this controller "lazy": it won't be downloaded until needed
* See https://symfony.com/bundles/StimulusBundle/current/index.html#lazy-stimulus-controllers
*/

/* stimulusFetch: 'lazy' */
export default class extends Controller {
    static targets = [];
    static values = {
        placeholder: {
            default: "--Select option--",
            type: String
        },
        dropdownParent: {
            type: String
        },
        hiddenSearch: {
            type: Boolean,
            default: false
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
         this.select2 = $(this.element).select2({
            placeholder: this.placeholderValue,
            allowClear: true,
            minimumResultsForSearch:  this.hiddenSearchValue ? -1 : 0
        });
         if(this.hasDropdownParentValue)
             this.select2.dropdownParent = $(this.dropdownParentValue)
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
        const selectedVal = $(e.currentTarget).val();
        const customEvent = new CustomEvent("select2:change", {
            detail: {
                selectedValue: selectedVal,
            },
            bubbles: true,
        });

        this.element.dispatchEvent(customEvent);

        if(this.urlValue === null) return

        const response = await axios.post(this.urlValue)
    }
}
