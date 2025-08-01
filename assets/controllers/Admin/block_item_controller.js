import { Controller } from '@hotwired/stimulus';
import { axiosPost } from "@ApiHelper";
import { alertSuccess, alertError } from '@Common';
import { getComponent } from "@symfony/ux-live-component";
/*
* The following line makes this controller "lazy": it won't be downloaded until needed
* See https://symfony.com/bundles/StimulusBundle/current/index.html#lazy-stimulus-controllers
*/

/* stimulusFetch: 'lazy' */
export default class extends Controller {
    static values = {
        url: String,
    };
    static outlets = [''];
    initialize() {
        // Called once when the controller is first instantiated (per element)

        // Here you can initialize variables, create scoped callables for event
        // listeners, instantiate external libraries, etc.
        // this._fooBar = this.fooBar.bind(this)
    }

    async connect() {
        // Called every time the controller is connected to the DOM
        // (on page load, when it's added to the DOM, moved in the DOM, etc.)

        // Here you can add event listeners on the element or target elements,
        // add or remove classes, attributes, dispatch custom events, etc.
        // this.fooTarget.addEventListener('click', this._fooBar)
        this.component =  await getComponent(document.getElementById('TableBlockLive'));
    }

    // Add custom controller actions here
    // fooBar() { this.fooTarget.classList.toggle(this.bazClass) }

    disconnect() {
        // Called anytime its element is disconnected from the DOM
        // (on page change, when it's removed from or moved in the DOM, etc.)

        // Here you should remove all event listeners added in "connect()" 
        // this.fooTarget.removeEventListener('click', this._fooBar)
    }

    getUrl(){
        return this.urlValue;
    }

    sortOrder(event){
        const data = new FormData();
        data.append(event.params.prop, event.target.value);
        axiosPost({url: this.getUrl(), data}, {
            success: res => {
                alertSuccess({html: "Sort updated", timer: 3000});
                this.component.render();
            },
            error: res => {alertError()}
        })
    }
}
