import { Controller } from '@hotwired/stimulus';
import { getComponent } from '@symfony/ux-live-component';
import { axiosPost } from '@ApiHelper';
import { alertError, alertSuccess } from '@Common';

export default class extends Controller {
    async connect() {
        const blockTable = document.getElementById('TableBlockLive')
            ?? document.getElementById('TableBlockChildLive');

        if (blockTable) {
            this.blockComponent = await getComponent(blockTable);
        }
    }

    disconnect() {
        this.blockComponent = null;
    }

    onSubmit(event) {
        event.preventDefault();
        const url = this.element.action;
        const formData = new FormData(this.element);

        event.target.activeProgress();

        axiosPost({ url, data: formData }, {
            success: res => {
                alertSuccess({ html: res.message, timer: 3000 });
                $('#app-modal').modal('hide');
                if (this.blockComponent) this.blockComponent.render();
            },
            failed: res => {
                if (res.status === 422) alertError(res.data.message);
            },
            final: _ => event.target.activeProgress(false)
        });
    }
}
