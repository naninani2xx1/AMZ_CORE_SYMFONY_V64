import {addModalIntoBodyTag, alertError, alertSuccess} from "@Common";
import {axiosGet, axiosPost} from "@ApiHelper";
import {getComponent} from "@symfony/ux-live-component";


/** Register event core **/
const buttonsOpenModal = document.querySelectorAll('[data-amz-btn-open-modal]');
buttonsOpenModal.forEach(button => {
    button.addEventListener('click', event => {
        event.preventDefault();
        const $this = event.currentTarget;
        let action;

        if($this.tagName === "BUTTON"){
           action = $this.dataset.action;
           $this.activeProgress();
        }
        if($this.tagName === "A")
            action = $this.href;

        axiosGet(action, {
            success: res => {
                addModalIntoBodyTag(res);
                $('#app-modal').modal('show');
            },
            failed: res => {
                alertError('Sorry, looks like there are some errors detected, please try again.')
            },
            final: _ => {
                if($this.tagName === "BUTTON")
                    $this.activeProgress(false);
            }
        });
    });
});
let selectedDOMInputGallery;
let selectedDOMViewGallery;
const buttonsOpenGallery = document.querySelectorAll('[data-amz-btn-open-gallery="true"]');
buttonsOpenGallery.forEach(button => {
    button.addEventListener('click', event => {
        event.preventDefault();
        event.stopPropagation();
        const $this = event.currentTarget;
        if($this.getAttribute('data-amz-input-pick-gallery') === undefined) return;
        selectedDOMInputGallery = document.querySelector($this.getAttribute('data-amz-input-pick-gallery'))
        selectedDOMViewGallery = document.querySelector($this.getAttribute('data-amz-place-view-gallery'))
        axiosGet('/cms/gallery/core/open-list', {
            success: res => {
                addModalIntoBodyTag(res);
                $('#app-modal').modal('show');
            },
            failed: res => {
                alertError('Sorry, looks like there are some errors detected, please try again.')
            },
            final: _ => {
            }
        });
    });
});

/** Register listen event **/
document.addEventListener('picked-gallery', event => {
    const { val, path } = event.detail;
    selectedDOMInputGallery.value = val;
    selectedDOMInputGallery.value = val;
    selectedDOMViewGallery.style.backgroundImage = `url('${path}')`;
})

/** remove item live component **/
const buttonsRemove = document.querySelectorAll('[data-amz-btn-remove]');
buttonsRemove?.forEach(btn => {
    btn?.addEventListener('click', async event => {
        event.preventDefault();
        const liveComponentDOM = btn.closest('[data-controller]')
        const component = await getComponent(liveComponentDOM);

        axiosPost({url: btn.getAttribute('data-action')}, {
            success: res => {
                alertSuccess({html: res.message, timer: 3000});
                component.render();
            },
            failed: res => alertError(),
        })
    })
})
