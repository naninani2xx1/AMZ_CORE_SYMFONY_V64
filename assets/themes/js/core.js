import {addModalIntoBodyTag, alertError, alertSuccess} from "@Common";
import {axiosGet, axiosPost} from "@ApiHelper";
import {getComponent} from "@symfony/ux-live-component";


/** Register event core **/
const handleEventOpenModal = (event) => {
    event.preventDefault();
    const $this = event.currentTarget;
    let action;

    if ($this.tagName === "BUTTON") {
        action = $this.dataset.action;
        $this.activeProgress();
    }
    if ($this.tagName === "A") {
        action = $this.href;
    }

    axiosGet(action, {
        success: res => {
            addModalIntoBodyTag(res);
            $('#app-modal').modal('show');
        },
        failed: res => {
            alertError('Sorry, looks like there are some errors detected, please try again.');
        },
        final: _ => {
            if ($this.tagName === "BUTTON") {
                $this.activeProgress(false);
            }
        }
    });
};
const initEventOpenModal = () => {
    const buttonsOpenModal = document.querySelectorAll('[data-amz-btn-open-modal]');
    buttonsOpenModal.forEach(button => {
        button?.removeEventListener('click', handleEventOpenModal);
        button?.addEventListener('click', handleEventOpenModal)
    });

}
/** remove item live component **/
const handle =  async (event) => {
    event.preventDefault();
    const btn = event.currentTarget;
    const liveComponentDOM = btn.closest('[data-controller="core-table live"]')
    const component = await getComponent(liveComponentDOM);

    axiosPost({url: btn.getAttribute('data-action')}, {
        success: res => {
            alertSuccess({html: res.message, timer: 3000});
            component.render();
        },
        failed: res => {
            console.log(res)
            alertError();
        },
    })
}
const initEventDeleteItem = () => {
    const buttonsRemove = document.querySelectorAll('[data-amz-btn-remove]');
    buttonsRemove?.forEach(btn => {
        btn?.removeEventListener('click', handle);
        btn?.addEventListener('click', handle)
    })
}
initEventOpenModal();
initEventDeleteItem();
export {initEventDeleteItem, initEventOpenModal}