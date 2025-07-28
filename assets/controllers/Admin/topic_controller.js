import { Controller } from '@hotwired/stimulus';
import {getComponent} from "@symfony/ux-live-component";
import { axiosPost } from '@ApiHelper';
import {alertError, alertSuccess} from '@Common';

/*
* The following line makes this controller "lazy": it won't be downloaded until needed
* See https://symfony.com/bundles/StimulusBundle/current/index.html#lazy-stimulus-controllers
*/

/* stimulusFetch: 'lazy' */
export default class extends Controller {

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
        this.userComponent = await getComponent(document.getElementById('TableTopicLive'));
    }

    // Add custom controller actions here
    // fooBar() { this.fooTarget.classList.toggle(this.bazClass) }

    disconnect() {
        // Called anytime its element is disconnected from the DOM
        // (on page change, when it's removed from or moved in the DOM, etc.)

        // Here you should remove all event listeners added in "connect()"
        // this.fooTarget.removeEventListener('click', this._fooBar)
        this.userComponent = null;
    }

    onSubmit(event){
        const url = this.element.action;
        let formData = new FormData(this.element);

        event.target.activeProgress();
        axiosPost({url, data: formData}, {
            success: res => {
                alertSuccess({html: res.message, timer: 3000});
                $('#app-modal').modal('hide');
                this.userComponent.render();
            },
            failed: res => {
                if(res.status === 422)
                    alertError(res.data.message);
            },
            final: _ => event.target.activeProgress(false)
        })
    }
}
