import { Controller } from '@hotwired/stimulus';
import {axiosGet} from "@ApiHelper";
import {addModalIntoBodyTag, alertError} from "@Common";

/*
* The following line makes this controller "lazy": it won't be downloaded until needed
* See https://symfony.com/bundles/StimulusBundle/current/index.html#lazy-stimulus-controllers
*/

/* stimulusFetch: 'lazy' */
export default class extends Controller {
    static targets = ['review', 'input', 'areaReview'];
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
    }

    // Add custom controller actions here
    // fooBar() { this.fooTarget.classList.toggle(this.bazClass) }

    disconnect() {
        // Called anytime its element is disconnected from the DOM
        // (on page change, when it's removed from or moved in the DOM, etc.)

        // Here you should remove all event listeners added in "connect()" 
        // this.fooTarget.removeEventListener('click', this._fooBar)
    }


    open(event){
        event.preventDefault();
        event.stopPropagation();
        axiosGet('/cms/gallery/core/open-list', {
            success: res => {
                document.body.insertAdjacentHTML('afterend', res)
                $(res).modal('show');
            },
            failed: res => {
                alertError('Sorry, looks like there are some errors detected, please try again.')
            },
            final: _ => {
            }
        });
    }

    listenPickedPicture({ detail: { val, path } }){
        if(this.hasInputTarget)
            this.inputTarget.value = path;
        if(this.hasAreaReviewTarget){
            this.areaReviewTarget.style.display = "block";
            this.areaReviewTarget.style.margin = "5px 0";
        }
        if(this.reviewTarget.tagName === "IMG")
            this.reviewTarget.src = path;
        else
            this.reviewTarget.style.backgroundImage = `url(${path})`;
    }
}
