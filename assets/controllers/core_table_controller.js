import { Controller } from '@hotwired/stimulus';
import { initEventDeleteItem, initEventOpenModal} from  '@Core';
import {getComponent} from "@symfony/ux-live-component";
/* stimulusFetch: 'lazy' */
export default class extends Controller {
    static targets = ['tableBody', 'pagination', 'searchInput', 'filterSelect'];

    static values = {
        apiUrl: String,
        page: { type: Number, default: 1 },
        limit: { type: Number, default: 10 },
        search: { type: String, default: '' }, // keyword for search
        filter: { type: String, default: '' }, // filter value
    };

    async connect() {
        this.component = await getComponent(this.element)
        console.log('CoreTableController connected!');

        this.component.on('render:finished', (component) => {
            this.initLozad();
            initEventDeleteItem();
            initEventOpenModal()
        });
    }

    initLozad(){
        const observer = lozad('.lozad', {
            loaded: function (el) {
                el.classList.add('loaded');
            }
        });
        observer.observe();
    }
}