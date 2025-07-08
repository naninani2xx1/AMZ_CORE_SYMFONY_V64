import { Controller } from '@hotwired/stimulus';
import {axiosPost} from "@ApiHelper";
/**
 * Allows you to dispatch a "modal:close" JavaScript event to close it.
 *
 * This is useful inside a LiveComponent, where you can emit a browser event
 * to open or close the modal.
 *
 * See templates/components/BootstrapModal.html.twig to see how this is
 * attached to Bootstrap modal.
 */
/* stimulusFetch: 'lazy' */
export default class extends Controller {
    static values = {
        eventNameSuccess: {
            default: 'form-success',
            type: String
        }
    };

    static outlets = [''];

    connect() {
        $(this.element).on('hidden.bs.modal', this.toggleModal.bind(this));
    }

    disconnect() {
        $(this.element).off('hidden.bs.modal', this.toggleModal.bind(this));
    }

    toggleModal(){
        this.element.innerHTML = ``;
    }

    _getController(selector){
        const domEle = document.querySelector(selector);
        return this.application.getControllerForElementAndIdentifier(domEle);
    }

    submitDefault(e){
        e.preventDefault();
        const { selector, eventName  } = e.params, element = document.querySelector(selector), form = e.currentTarget;
        const formData = new FormData(form), submitEle = form.querySelector('button[type="submit"]');
        if (!element) {
            console.error(`Element not found for selector: ${selector}`);
            return;
        }
        const options = {
            url: form.action,
            data: formData,
        };
        const handle = {
            success: res => {
                $(this.element).modal('hide');
                // push event to controller listening to handle.
                const event = new CustomEvent(eventName, {
                    detail: res,
                    bubbles: true
                })
                element.dispatchEvent(event);
            },
            final: _ => {
                submitEle.activeProgress(false);
            }
        };
        submitEle.activeProgress();
        axiosPost(options, handle)
    }
}
