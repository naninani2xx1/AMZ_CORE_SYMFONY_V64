import { Controller } from '@hotwired/stimulus';
import { axiosPost } from "@ApiHelper";

export default class extends Controller {
    static targets = ['tableBody', 'pagination', 'searchInput', 'filterSelect'];

    static values = {
        apiUrl: String,
        page: { type: Number, default: 1 },
        limit: { type: Number, default: 10 },
        search: { type: String, default: '' }, // keyword for search
        filter: { type: String, default: '' }, // filter value
    };

    connect() {
        console.log('CoreTableController connected!');
        this.fetchData().then(r => {}); // call API when controller is initialized
    }

    async fetchData() {
        try {
            const params = {
                page: this.pageValue,
                limit: this.limitValue,
                search: this.searchValue,
                filter: this.filterValue,
            };
            const options = {
                url: this.urlValue,
                data: params,
            };
            axiosPost(options, {
                success: res => {

                },
                error: res => {},
                final: res => {}
            })

        } catch (error) {
            this.tableBodyTarget.innerHTML = '<tr><td colspan="5">Error loading data.</td></tr>';
        }
    }

    search(event) {
        this.searchValue = event.target.value;
        this.pageValue = 1; // Reset về trang 1 khi tìm kiếm
        this.fetchData().then(r => {});
    }

    filter(event) {
        this.filterValue = event.target.value;
        this.pageValue = 1; // Reset về trang 1 khi filter
        this.fetchData().then(r => {});
    }

    changePage(event) {
        this.pageValue = parseInt(event.target.dataset.page);
        this.fetchData().then(r => {});
    }
}