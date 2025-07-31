import { Controller } from '@hotwired/stimulus';
import { axiosPost} from '@ApiHelper';
import  { alertError, alertSuccess, alertProcessing } from '@Common'
/*
* The following line makes this controller "lazy": it won't be downloaded until needed
* See https://symfony.com/bundles/StimulusBundle/current/index.html#lazy-stimulus-controllers
*/
/* stimulusFetch: 'lazy' */
export default class extends Controller {
    static targets = ['dotStatus', 'form'];
    static classes = []

    // Constants for better maintainability
    static statusClasses = {
        "2": "bg-success",
        "1": "bg-warning",
    };
    initialize() {
        // Called once when the controller is first instantiated (per element)

        // Here you can initialize variables, create scoped callables for event
        // listeners, instantiate external libraries, etc.
        // this._fooBar = this.fooBar.bind(this)
    }

    connect() {
        // Called every time the controller is connected to the DOM
        // (on page load, when it's added to the DOM, moved in the DOM, etc.)

        // Here you can add event listeners on the element or target elements,
        // add or remove classes, attributes, dispatch custom events, etc.
        // this.fooTarget.addEventListener('click', this._fooBar)
        this.element.addEventListener("select2:change", this.handleSelectChange.bind(this));
    }

    // Add custom controller actions here
    // fooBar() { this.fooTarget.classList.toggle(this.bazClass) }

    disconnect() {
        // Called anytime its element is disconnected from the DOM
        // (on page change, when it's removed from or moved in the DOM, etc.)

        // Here you should remove all event listeners added in "connect()" 
        // this.fooTarget.removeEventListener('click', this._fooBar)
        this.element.removeEventListener("select2:change", this.handleSelectChange.bind(this));
    }

    handleSelectChange(e){
        const { selectedValue } = e.detail;

        const allClasses = Object.values(this.constructor.statusClasses);
        this.dotStatusTarget.classList.remove(...allClasses);

        const newClass = this.constructor.statusClasses[selectedValue];
        if (newClass) {
            this.dotStatusTarget.classList.add(newClass);
        }
    }

    add(e){
        const $this = e.currentTarget;
        const formData = new FormData(this.formTarget);
        alertProcessing();
        axiosPost({
            url: this.formTarget.action,
            data: formData
        }, {
            success: res => {
                const callback = () => {
                    location.href = res.redirect;
                }
                alertSuccess({html: res.message, timer: 3000, callback})
            },
            failed: res => {
                alertError()
            },
            final: _ => {
            }
        });
    }
}
