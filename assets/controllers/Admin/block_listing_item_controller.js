import {Controller} from '@hotwired/stimulus';
import {axiosPost} from '@ApiHelper';
/*
* The following line makes this controller "lazy": it won't be downloaded until needed
* See https://symfony.com/bundles/StimulusBundle/current/index.html#lazy-stimulus-controllers
*/

/* stimulusFetch: 'lazy' */
export default class extends Controller {
    static targets = ['image', 'title', 'subTitle', 'description', 'customUrl', 'background'];
    static values = {
        mainUrl: {
            type: String
        },
        deleteUrl: {
            type: String
        },
        origin: String,
        uuid: String,
        eventNameForImage: String
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
        window.addEventListener('message', this.receiver.bind(this));
        window.addEventListener(this.eventNameForImageValue,  this.receiverImage.bind(this))
    }

    // Add custom controller actions here
    // fooBar() { this.fooTarget.classList.toggle(this.bazClass) }

    disconnect() {
        // Called anytime its element is disconnected from the DOM
        // (on page change, when it's removed from or moved in the DOM, etc.)

        // Here you should remove all event listeners added in "connect()" 
        // this.fooTarget.removeEventListener('click', this._fooBar)
        window.removeEventListener('message', this.receiver.bind(this));
        window.removeEventListener(this.eventNameForImageValue,  this.receiverImage.bind(this))

    }

    receiverImage({detail}){
        const {path} = detail;
        if(!this.hasImageTarget) return;
        if(this.imageTarget.tagName === "IMG")
            this.imageTarget.src = `${path}`;
        else
            this.imageTarget.style.backgroundImage = `url('${path}')`;
    }

    receiver(event){
        if (event.origin !== this.originValue) return;
        if (event.data.type === 'updateProperty' && this.uuidValue === event.data.uuid) {
            const { description } = event.data;
            const parser = new DOMParser();
            const doc = parser.parseFromString(description, "text/html");
            this.descriptionTarget.innerHTML = ``;
            this.descriptionTarget.appendChild(doc.body.firstChild)
        }
    }

    requestOpenCkeditor(event){
        const { uuid, prop } = event.params;
        const message = { type: 'requestOpenCkeditorItemBlock', message: 'request open ckeditor', prop: prop, uuid: uuid};
        window.parent.postMessage(message, this.originValue);
    }

    sendTurnOnAlertToParent(data, typeAlert) {
        const message = { type: 'notify', message: data.message, typeAlert: typeAlert };
        window.parent.postMessage(message, this.originValue);
    }

    requestOpenGallery(event){
        const { eventName, eventRegister } = event.params;
        const message = { type: 'requestOpenGallery', message: 'request open gallery', eventName, eventRegister };
        window.parent.postMessage(message, this.originValue);
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


    saveItem(event){
        let data = {};
        data[this.element.id] = {
            uuid: this.element.id,
        }

        const targetNames = this.constructor.targets;
        targetNames.forEach((targetName) => {
            const hasTarget = this[`has${targetName.charAt(0).toUpperCase() + targetName.slice(1)}Target`];
            if (hasTarget) {
                const target = this[`${targetName}Target`];
                if(target.tagName === "IMG")
                    data[this.element.id][targetName] = target.src;
                else
                    data[this.element.id][targetName] = target.textContent;
            } else {
                console.log(`Target ${targetName} not found in DOM`);
            }
        });
        this._call('listingItem', JSON.stringify(data));
    }

    removeItem(event){
        this.element.remove();
        if(this.hasDeleteUrlValue){
            axiosPost({url: this.deleteUrlValue}, {
                success: res => {
                    this.sendTurnOnAlertToParent( res,'success');
                },
                failed: res => {
                    console.log(res);
                }
            })
        }
    }
}
