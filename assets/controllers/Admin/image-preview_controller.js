import { Controller } from '@hotwired/stimulus';
import 'bootstrap';
export default class extends Controller {
    static targets = ['input', 'preview', 'wrapper'];

    connect() {
        const currentImage = this.previewTarget.getAttribute('src');
        if (currentImage) {
            this.wrapperTarget.style.display = 'block';
        }
    }

    preview() {
        const file = this.inputTarget.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = (e) => {
                this.previewTarget.src = e.target.result;
                this.wrapperTarget.style.display = 'block';
            };
            reader.readAsDataURL(file);
        }
    }
}
