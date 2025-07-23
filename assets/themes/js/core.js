import {addModalIntoBodyTag, alertError} from "@Common";
import {axiosGet} from "@ApiHelper";

/** Register event core **/
const buttonsOpenModal = document.querySelectorAll('[data-amz-btn-open-modal]');
buttonsOpenModal.forEach(button => {
    button.addEventListener('click', event => {
        event.preventDefault();
        const $this = event.currentTarget;
        const { action } = $this.dataset;
        $this.activeProgress();
        axiosGet(action, {
            success: res => {
                addModalIntoBodyTag(res);
                $('#app-modal').modal('show');
            },
            failed: res => {
                alertError('Sorry, looks like there are some errors detected, please try again.')
            },
            final: _ => {
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

