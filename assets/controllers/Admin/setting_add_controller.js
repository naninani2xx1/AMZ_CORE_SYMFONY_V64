import { Controller } from '@hotwired/stimulus';
import { axiosPost } from '@ApiHelper';
import {alertError, alertSuccess} from '@Common';
import {getComponent} from "@symfony/ux-live-component";

/*
* The following line makes this controller "lazy": it won't be downloaded until needed
* See https://symfony.com/bundles/StimulusBundle/current/index.html#lazy-stimulus-controllers
*/

/* stimulusFetch: 'lazy' */
export default class extends Controller {
    static targets = ['settingValue', 'listItem'];
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
        this.settingComponent = await getComponent(document.getElementById('TableSettingLive'));
    }

    // Add custom controller actions here
    // fooBar() { this.fooTarget.classList.toggle(this.bazClass) }

    disconnect() {
        // Called anytime its element is disconnected from the DOM
        // (on page change, when it's removed from or moved in the DOM, etc.)

        // Here you should remove all event listeners added in "connect()" 
        // this.fooTarget.removeEventListener('click', this._fooBar)
        this.settingComponent = null;
    }

    onSettingTypeChange(event){
        const selectedValue = event.target.value;
        const url = this.element.action;

        let formData = new FormData();
        formData.append(event.target.name, selectedValue);

        axiosPost({url, data: formData},{
            success: res => {

            },
            failed: res => {
                if(res.status === 422){
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(res.data, 'text/html');

                    const divElement = doc.querySelector('[data-Admin--setting-add-target="settingValue"]');
                    this.settingValueTarget.innerHTML = divElement.innerHTML;
                }
            },
            final: _ => {}
        });
    }

    onSubmit(event){
        const url = this.element.action;
        let formData = new FormData(this.element);

        event.target.activeProgress();
        axiosPost({url, data: formData}, {
            success: res => {
                alertSuccess({html: res.message, timer: 3000});
                $('#app-modal').modal('hide');
                this.settingComponent.render();
            },
            failed: res => {
                if(res.status === 422)
                   alertError(res.data.message);
            },
            final: _ => event.target.activeProgress(false)
        })
    }

    addItem(event){
        const { template } = event.params;
        const collectionHolder = this.listItemTarget;

        const item = document.createElement('div');

        item.innerHTML = template.replace(
                /__name__/g,
                event.target.dataset.index
            );

        collectionHolder.appendChild(item);

        event.target.dataset.index++;
    }

    removeItem(event){
        event.target.closest('.setting-val-item').remove();
    }
}
