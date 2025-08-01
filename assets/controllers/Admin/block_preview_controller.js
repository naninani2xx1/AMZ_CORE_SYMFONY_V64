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
    static targets = ['background', 'image'];
    static values = {
        mainUrl: {
            type: String
        },
        origin: String
    };
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
        window.addEventListener('message', this.receiver.bind(this));
    }

    // Add custom controller actions here
    // fooBar() { this.fooTarget.classList.toggle(this.bazClass) }

    disconnect() {
        // Called anytime its element is disconnected from the DOM
        // (on page change, when it's removed from or moved in the DOM, etc.)

        // Here you should remove all event listeners added in "connect()" 
        // this.fooTarget.removeEventListener('click', this._fooBar)
        window.removeEventListener('message', this.receiver.bind(this));
    }

    receiver(event){
        if (event.origin !== this.originValue) return;
        if (event.data.type === 'pickedPicture') {
            this._listPickedPicture(event.data);
            console.log('Received from parent:', event.data);
        }
    }

    onChange(event){
        const val = event.target.textContent;
        const {prop} = event.params;
        this._call(prop, val);
    }

    sendTurnOnAlertToParent(data, typeAlert) {
        const message = { type: 'notify', message: data.message, typeAlert: typeAlert };
        window.parent.postMessage(message, this.originValue);
    }

    requestOpenGallery(event){
        const { eventName } = event.params;
        const message = { type: 'requestOpenGallery', message: 'request open gallery', eventName: eventName };
        window.parent.postMessage(message, this.originValue);
    }

    _listPickedPicture(data){
        const { path, event } = data;
        this.backgroundTarget.style.backgroundImage = `url('${path}')`;

        if(event === "picture@background"){
            this._call('background', path);
        }
    }

    _call(prop,val){
        const formData = new FormData();
        formData.append(prop, val);
        axiosPost({url: this.mainUrlValue, data: formData}, {
            success: res => {
                this.sendTurnOnAlertToParent(res, "success");
            },
            failed: res => {
                this.sendTurnOnAlertToParent(res, "failed");
            }
        })
    }
}
