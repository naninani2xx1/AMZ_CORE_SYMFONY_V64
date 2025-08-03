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
        super.initialize();
    }
    connect() {
        super.connect();
    }

    disconnect() {
        super.disconnect();
    }

    /** the function used on the modals edit props of block */
    onSubmit(event){
        const $this = event.currentTarget;
        const form = $this.closest('form');
        for (let instance in CKEDITOR.instances){
            CKEDITOR.instances[instance].updateElement();
        }

        const data = new FormData(form);
        axiosPost({url: form.action, data}, {
            success: res => {
                alertSuccess({html: res.message, timer: 3000}, r  => {});
                $('#app-modal').modal('hide');
                let payload = res.payload;
                payload.type = "updateProperty";
                window.dispatchEvent(new CustomEvent("block@update", { detail: payload }));
            },
            failed: res => {
                console.log(res)
            }
        })
    }

}