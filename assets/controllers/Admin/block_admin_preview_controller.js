import { Controller } from '@hotwired/stimulus';
import {getComponent} from "@symfony/ux-live-component";
import {axiosGet, axiosPost} from '@ApiHelper';
import {addModalIntoBodyTag, alertError, alertSuccess} from '@Common';

/*
* The following line makes this controller "lazy": it won't be downloaded until needed
* See https://symfony.com/bundles/StimulusBundle/current/index.html#lazy-stimulus-controllers
*/

/* stimulusFetch: 'lazy' */
export default class extends Controller {
    static targets = ['iframe'];
    static values = {
        origin: String,
        requestOpenGallery: String
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

        if (event.data.type === 'notify') {
            console.log('Received from iframe:', event.data);
            this.handleNotifyAlert(event.data);
        }else if(event.data.type === "requestOpenGallery"){
            console.log('Received from iframe:', event.data);
            this.requestOpenGallery(event.data);
        }
    }

    sendToIframe(payload) {
        this.iframeTarget.contentWindow.postMessage(payload, this.originValue);
    }

    handleNotifyAlert(data){
        const { typeAlert, message } = data;
        if(typeAlert === "success")
            alertSuccess({html: message, timer: 3000});
        else if(typeAlert === "failed")
            alertError();
    }

    requestOpenGallery(data){
        axiosGet(this.requestOpenGalleryValue+ "?event=" + data.eventName, {
            success: res => {
                addModalIntoBodyTag(res);
                $('#app-modal').modal('show');
            },
            failed: res => {}
        })
    }

    listenPickedPicture(event){
        let data = event.detail;
        data.type = "pickedPicture";
        this.sendToIframe(data);
    }
}
